<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/menu/menuLibraryInclude.php');
#---
$thisPageMnSeq = 2;
$sqlSearchPart = "";
$sqlSearchPartIndex = 0;
$sqlSearchPart2 = "";
$sqlSearchPart2Index = 0;
$mnSeq = nvl(getRequestValue("mnSeq"),"");
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
#---
fnOpenDB();
setDisplayMenuList();
#---
if($schTitle!=""){
	$sqlSearchPartIndex = 1;
	$sqlSearchPart .= fnGetSqlWhereAndString($sqlSearchPartIndex);
	$sqlSearchPart .= " b.mn_del_yn = 'N' and a.mn_nm like '%${schTitle}%' ";
	$sqlSearchPartIndex++;
}#if
if($schContent!=""){
	$sqlSearchPart2Index = 1;
	$sqlSearchPart2 .= fnGetSqlWhereAndString($sqlSearchPart2Index);
	$sqlSearchPart2 .= " m.mn_del_yn = 'N' and m.mn_content like '%${schContent}%' ";
	$sqlSearchPart2Index++;
}#if
#---
$sqlHeadPart = "
	WITH RECURSIVE menu_cte AS (
		SELECT 
			mn_seq, mn_nm, p_mn_seq, 0 AS mn_depth_no, 
			mn_nm AS mn_path, mn_ord
		FROM tb_board_menu_info
		WHERE p_mn_seq = 0 /* 루트 메뉴 */
		and mn_del_yn = 'N'
		UNION ALL
		SELECT 
			m.mn_seq, m.mn_nm, m.p_mn_seq, c.mn_depth_no + 1 AS mn_depth_no,
			CONCAT(c.mn_path, ' - ', m.mn_nm) AS mn_path,
			CONCAT(c.mn_ord, '_', m.mn_ord) AS mn_ord
		FROM tb_board_menu_info m
		INNER JOIN menu_cte c 
			 ON m.p_mn_seq = c.mn_seq
		where m.mn_del_yn = 'N'
		${sqlSearchPart2}
	)
";
$sqlBodyPart = "
	FROM menu_cte a
	inner join tb_board_menu_info b
		on a.mn_seq = b.mn_seq
	where b.mn_del_yn = 'N'
	${sqlSearchPart}
";
#---
$sqlCount = "
	${sqlHeadPart}
	SELECT count(*)
	${sqlBodyPart}
";
$menuListTotalCount = fnDBGetIntValue($sqlCount);
#$menuListTotalCount = 328; #test
#---
$pagingInfoMap = fnCalcPaging($pageNumber,$menuListTotalCount,$pageSize,$blockSize);
debugArray("pagingInfoMap",$pagingInfoMap);
#---
$sqlMain = "
	${sqlHeadPart}
	SELECT
		a.*,
		b.mn_url,
		b.mn_url_target,
		b.mn_use_yn
	${sqlBodyPart}
	ORDER BY mn_ord
	LIMIT {{limitStartNumber}}, {{limitEndNumber}}
";
$sqlMain = fnGetPagingQuery($sqlMain,$pagingInfoMap);
debugString("sqlMain",str_replace("\n","<br />",$sqlMain));
$menuList = fnDBGetList($sqlMain);
$menuListCount = getArrayCount($menuList);
#---
fnCloseDB();
?>
<!doctype html>
<html lang="ko">
<head>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/head.php'); ?>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pagingListInfo.php'); ?>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/top.php'); ?>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutStart.php'); ?>

<h2>메뉴 관리 <span class="menu-navi-class"><?php echo getMenuPathString($thisPageMnSeq); ?></span></h2>

<table class="search-table-class">
<colgroup>
	<col width="15%" />
	<col width="35%" />
	<col width="15%" />
	<col width="35%" />
</colgroup>
<tr>
	<td colspan="4"><strong>검색</strong></td>
</tr>
<tr>
	<th>메뉴 이름</th>
	<td><input type="text" id="schTitle" value="<?php echo $schTitle; ?>" /></td>
	<th>메뉴 내용</th>
	<td><input type="text" id="schContent" value="<?php echo $schContent; ?>" /></td>
</tr>
<tr class="last-row-class">
	<td colspan="4" align="right">
		<button onclick="javascript:goSearch();">검색</button>
		<button onclick="javascript:resetSearch();" style="color:red;">초기화</button>
	</td>
</tr>
</table>

<?php fnPrintPagingListInfo($pagingInfoMap); ?>

<table class="board-table-class">
<colgroup>
	<col width="10%" />
	<col width="*" />
	<col width="25%" />
</colgroup>
<tr>
	<th>번호</th>
	<th>메뉴명</th>
	<th>기능</th>
</tr>
<?php
if($menuListTotalCount > 0){
	foreach ($menuList as $index => $row) {
		#--- 행 스타일 문자열
		$rowStyleString = "";
		if(nvl($row["mn_use_yn"],"Y")!="Y"){
			$rowStyleString = "color:gray;";
		}//if
		#--- 단계 1인 메뉴의 행 배경색 설정
		$depth1RowStyle = "";
		if(intval(nvl($row["mn_depth_no"],"0"))==0){
			$depth1RowStyle = "background-color:#eaeaea;";
		}#if
?>
<tr style="<?php echo $depth1RowStyle; ?>">
	<td align="center"><?php echo $pagingInfoMap["startRowNumberForPage"] - $index; ?></td>
	<td align="left">
		<a href="javascript:goModify('<?php echo $row["mn_seq"]; ?>');" style="<?php echo $rowStyleString; ?>"><?php echo fnMenuGetPrefixString($row["mn_depth_no"]).$row["mn_nm"]; ?></a> 
		<a href="javascript:copyMnSeq('<?php echo $row["mn_seq"]; ?>');" style="color:gray;">(메뉴번호 : <?php echo $row["mn_seq"]; ?>)</a>
		<br /><span style="<?php echo $rowStyleString; ?>"><?php echo nvl($row["mn_use_yn"],"Y")=="Y" ? "[사용]" : "[미사용]"; ?> 메뉴경로 : <?php echo $row["mn_path"]; ?></span>
	</td>
	<td align="center">
		<a href="javascript:goWrite('<?php echo $row["p_mn_seq"]; ?>');">등록</a> | 
		<a href="javascript:goWrite('<?php echo $row["mn_seq"]; ?>');">하위등록</a> | 
		<a href="javascript:goMenuLink('<?php echo $row["mn_url"]; ?>','_blank');">링크</a> | 
		<a href="javascript:goDelete('<?php echo $row["mn_seq"]; ?>');" style="color:red;">삭제</a>
		<br /><a href="javascript:goMoveUp('<?php echo $row["mn_seq"]; ?>');">상위이동</a> | 
		<a href="javascript:goMoveDown('<?php echo $row["mn_seq"]; ?>');">하위이동</a>
	</td>
</tr>
<?php
	}#foreach
}#if
?>
<?php if($menuListTotalCount == 0){ ?>
<tr>
	<td align="center" colspan="3">등록된 메뉴가 없습니다.</td>
</tr>
<?php }//if ?>
</table>

<div align="right" style="margin-top:10px;">
	<input type="button" value="등록" onclick="goWrite();" />
</div>

<?php fnPrintPagingHtml($pagingInfoMap); ?>

<form name="paramForm" method="get">
<input type="hidden" name="regMnSeq" value="" />
<input type="hidden" name="modMnSeq" value="" />
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
</form>

<form name="procForm" method="post" action="menuManProc.php">
<input type="hidden" name="actionString" value="" />
<input type="hidden" name="delMnSeq" value="" />
<input type="hidden" name="moveMnSeq" value="" />
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
</form>

<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutEnd.php'); ?>

<script>
var paramFormObject = document.paramForm;
var procFormObject = document.procForm;
//---
function goPage(pageNumber){
	paramFormObject.pageNumber.value = pageNumber;
	paramFormObject.action = 'menuMan.php';
	paramFormObject.submit();
}
function goWrite(regMnSeq=''){
	paramFormObject.regMnSeq.value = regMnSeq;
	paramFormObject.action = 'menuManWrite.php';
	paramFormObject.submit();
}
function goModify(modMnSeq=''){
	paramFormObject.modMnSeq.value = modMnSeq;
	paramFormObject.action = 'menuManWrite.php';
	paramFormObject.submit();
}
function goDelete(mnSeq=''){
	if(confirm('삭제 하시겠습니까?')){
		procFormObject.actionString.value = 'delete';
		procFormObject.delMnSeq.value = mnSeq;
		procFormObject.submit();
	}//if
}
function goSearch(){
	paramFormObject.schTitle.value = $('#schTitle').val();
	paramFormObject.schContent.value = $('#schContent').val();
	paramFormObject.pageNumber.value = '1';
	paramFormObject.action = 'menuMan.php';
	paramFormObject.submit();
}
function resetSearch(){
	if(confirm('검색을 초기화 하시겠습니까?')){
		$('#schTitle').val('');
		$('#schContent').val('');
		goSearch();
	}//if
}
function goMenuLink(mnUrl='',mnUrlTarget=''){
	if(mnUrl===''){return;}//if
	//---
	if(mnUrlTarget==='' || mnUrlTarget==='_self'){
		location.href = mnUrl;
	}else{
		var popupWin = null;
		popupWin = window.open(mnUrl,mnUrlTarget);
		popupWin.focus();
	}//if
}
function copyMnSeq(mnSeq=''){
	prompt('메뉴번호를 복사해 주세요.',mnSeq);
}
function goMoveUp(mnSeq=''){
	if(mnSeq===''){return;}//if
	//---
	if(confirm('메뉴를 상위로 이동하시겠습니까?')){
		procFormObject.actionString.value = 'menuMoveUp';
		procFormObject.moveMnSeq.value = mnSeq;
		procFormObject.submit();
	}//if
}
function goMoveDown(mnSeq=''){
	if(mnSeq===''){return;}//if
	//---
	if(confirm('메뉴를 하위로 이동하시겠습니까?')){
		procFormObject.actionString.value = 'menuMoveDown';
		procFormObject.moveMnSeq.value = mnSeq;
		procFormObject.submit();
	}//if
}
</script>

</body>
</html>