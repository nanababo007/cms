<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/menu/menuLibraryInclude.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pagingListInfo.php');
#---
$pageTitleString = "";
$pageMTitleString = "";
$isDisplayRegBtnOnPagingListInfo = false;
$thisPageMnSeq = 2;
$sqlSearchPart = "";
$sqlSearchPartIndex = 0;
$sqlSearchPart2 = "";
$sqlSearchPart2Index = 0;
$mnSeq = nvl(getRequestValue("mnSeq"),"");
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = 100;
$blockSize = intval(nvl(getRequestValue("blockSize"),$envVarMap["pagingBlockSize"]));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
#---
$pageTitleString = "메뉴 관리";
$pageMTitleString = "메뉴 관리";
$isDisplayRegBtnOnPagingListInfo = true;
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
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/headM.php'); ?>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pageListMStyle.php'); ?>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutStartM.php'); ?>

<div class="board-container">
	<!-- 검색창 -->
	<div class="input-group mb-4">
		<input type="text" id="schTitle" value="<?php echo $schTitle; ?>" class="form-control" placeholder="검색어를 입력하세요">
		<input type="hidden" id="schContent" value="<?php echo $schContent; ?>" />
		<button class="btn btn-outline-secondary" type="button" onclick="javascript:goSearch();">검색</button>
		<button class="btn btn-outline-secondary" type="button" onclick="javascript:resetSearch();" style="color:red;">초기화</button>
	</div>
	
	<!-- 목록 상단 정보 영역 -->
	<?php fnMobilePrintPagingListInfo($pagingInfoMap); ?>
	
	<!-- 게시글 목록 (반응형 리스트) -->
	<div class="list-group">
		<!-- 개별 게시글 유닛 -->
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
		<a name="mnSeqPos<?php echo $row["mn_seq"]; ?>"></a>
		<div class="post-item">
			<a href="javascript:goModify('<?php echo $row["mn_seq"]; ?>');" class="post-title"><?php echo fnMenuGetPrefixString($row["mn_depth_no"]); ?> <?php echo $pagingInfoMap["startRowNumberForPage"] - $index; ?>. <?php echo $row["mn_nm"]; ?></a>
			<div class="post-info">
				<span>메뉴경로: <?php echo $row["mn_path"]; ?></span>
				<br /><span>메뉴번호: <?php echo $row["mn_seq"]; ?></span> | 
				<span>사용여부: <?php echo nvl($row["mn_use_yn"],"Y")=="Y" ? "사용" : "미사용"; ?></span>
			</div>
			<div class="post-info">
				<a href="javascript:goWrite('<?php echo $row["p_mn_seq"]; ?>');">등록</a> | 
				<a href="javascript:goWrite('<?php echo $row["mn_seq"]; ?>');">하위등록</a> | 
				<a href="javascript:goMenuLink('<?php echo $row["mn_url"]; ?>','_blank');">링크</a> | 
				<a href="javascript:goDelete('<?php echo $row["mn_seq"]; ?>');" style="color:red;">삭제</a>
				<br /><a href="javascript:goMoveUp('<?php echo $row["mn_seq"]; ?>');">상위이동</a> | 
				<a href="javascript:goMoveDown('<?php echo $row["mn_seq"]; ?>');">하위이동</a>
			</div>
		</div>
		<?php
			}#foreach
		}#if
		?>
		<?php if($menuListTotalCount == 0){ ?>
		<div class="post-item">
			등록된 게시글이 없습니다.
		</div>
		<?php }//if ?>
	</div>

	<?php fnMobilePrintPagingHtml($pagingInfoMap); ?>
</div>

<?php if(isset($isDisplayRegBtnOnPagingListInfo) and $isDisplayRegBtnOnPagingListInfo){ ?>
<button type="button" class="btn btn-primary write-btn" onclick="goWrite();">메뉴등록 📝</button>
<?php } #if ?>

<script src="/board2/cmn/bootstrap5/js/bootstrap.bundle.min.js"></script>

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

<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutEndM.php'); ?>

<script>
var paramFormObject = document.paramForm;
var procFormObject = document.procForm;
//---
function goPage(pageNumber){
	paramFormObject.pageNumber.value = pageNumber;
	paramFormObject.action = 'menuManM.php';
	paramFormObject.submit();
}
function goWrite(regMnSeq=''){
	paramFormObject.regMnSeq.value = regMnSeq;
	paramFormObject.action = 'menuManWriteM.php';
	paramFormObject.submit();
}
function goModify(modMnSeq=''){
	paramFormObject.modMnSeq.value = modMnSeq;
	paramFormObject.action = 'menuManWriteM.php';
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
	paramFormObject.action = 'menuManM.php';
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