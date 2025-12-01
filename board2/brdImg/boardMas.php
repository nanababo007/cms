<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pagingListInfo.php');
#---
$thisPageMnSeq = 24;
$sqlSearchPart = "";
$sqlSearchPartIndex = 0;
$mnSeq = nvl(getRequestValue("mnSeq"),"");
$bdSeq = nvl(getRequestValue("bdSeq"),"");
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
$boardFixList = null;
$boardFixListCount = 0;
#---
fnOpenDB();
setDisplayMenuList();
#---
if($schTitle!=""){
	$sqlSearchPart .= fnGetSqlWhereAndString($sqlSearchPartIndex);
	$sqlSearchPart .= " a.bd_nm like '%${schTitle}%' ";
	$sqlSearchPartIndex++;
}#if
if($schContent!=""){
	$sqlSearchPart .= fnGetSqlWhereAndString($sqlSearchPartIndex);
	$sqlSearchPart .= " a.bd_content like '%${schContent}%' ";
	$sqlSearchPartIndex++;
}#if
#---
$sqlBodyPart = "
	FROM tb_board_img_info a
	${sqlSearchPart}
";
#---
$sqlFix = "
	select
		a.*
	from (
		SELECT
			a.bd_seq
			,a.bd_nm
			,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
			,a.regdate
			,a.reguser
		${sqlBodyPart}
		where bd_fix_yn = 'Y'
	) a
	ORDER BY a.bd_seq DESC
";
$boardFixList = fnDBGetList($sqlFix);
$boardFixListCount = getArrayCount($boardFixList);
#---
$sqlCount = "
	SELECT count(*)
	${sqlBodyPart}
";
$boardListTotalCount = fnDBGetIntValue($sqlCount);
#$boardListTotalCount = 328; #test
#---
$pagingInfoMap = fnCalcPaging($pageNumber,$boardListTotalCount,$pageSize,$blockSize);
debugArray("pagingInfoMap",$pagingInfoMap);
#---
$sqlMain = "
	SELECT
		a.bd_seq
		,a.bd_nm
		,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
		,a.regdate
		,a.reguser
	${sqlBodyPart}
	ORDER BY a.bd_seq DESC
	LIMIT {{limitStartNumber}}, {{limitEndNumber}}
";
$sqlMain = fnGetPagingQuery($sqlMain,$pagingInfoMap);
debugString("sqlMain",str_replace("\n","<br />",$sqlMain));
$boardList = fnDBGetList($sqlMain);
$boardListCount = getArrayCount($boardList);
#---
fnCloseDB();
?>
<!doctype html>
<html lang="ko">
<head>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/head.php'); ?>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/top.php'); ?>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutStart.php'); ?>

<h2>이미지 게시판 관리 <span class="menu-navi-class"><?php echo getMenuPathString($thisPageMnSeq); ?></span></h2>

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
	<th>게시판 이름</th>
	<td><input type="text" id="schTitle" value="<?php echo $schTitle; ?>" /></td>
	<th>게시판 내용</th>
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
	<col width="18%" />
	<col width="18%" />
	<col width="18%" />
</colgroup>
<tr>
	<th>번호</th>
	<th>게시판 이름</th>
	<th>게시글 제목</th>
	<th>등록자</th>
	<th>등록일</th>
</tr>
<?php
if($boardFixListCount > 0){
	foreach ($boardFixList as $index => $row) {
?>
<tr>
	<td align="center">고정</td>
	<td align="left">
		<a href="javascript:goView('<?php echo $row["bd_seq"]; ?>');"><?php echo $row["bd_nm"]; ?></a> 
		<a href="javascript:copyBoardSeq('<?php echo $row["bd_seq"]; ?>');" style="color:gray;">(게시판 번호 : <?php echo $row["bd_seq"]; ?>)</a>
	</td>
	<td align="center">
		<a href="javascript:goBoardArticleList('<?php echo $row["bd_seq"]; ?>');">보기</a> |
		<a href="javascript:copyBoardArticleListUrl('<?php echo $row["bd_seq"]; ?>');">경로복사</a>
	</td>
	<td align="center"><?php echo $row["regdate_str"]; ?></td>
	<td align="center"><?php echo $row["reguser"]; ?></td>
</tr>
<?php
	}#foreach
}#if
?>
<?php
if($boardListTotalCount > 0){
	foreach ($boardList as $index => $row) {
?>
<tr>
	<td align="center"><?php echo $pagingInfoMap["startRowNumberForPage"] - $index; ?></td>
	<td align="left">
		<a href="javascript:goView('<?php echo $row["bd_seq"]; ?>');"><?php echo $row["bd_nm"]; ?></a> 
		<a href="javascript:copyBoardSeq('<?php echo $row["bd_seq"]; ?>');" style="color:gray;">(게시판 번호 : <?php echo $row["bd_seq"]; ?>)</a>
	</td>
	<td align="center">
		<a href="javascript:goBoardArticleList('<?php echo $row["bd_seq"]; ?>');">보기</a> |
		<a href="javascript:copyBoardArticleListUrl('<?php echo $row["bd_seq"]; ?>');">경로복사</a>
	</td>
	<td align="center"><?php echo $row["regdate_str"]; ?></td>
	<td align="center"><?php echo $row["reguser"]; ?></td>
</tr>
<?php
	}#foreach
}#if
?>
<?php if($boardListTotalCount == 0){ ?>
<tr>
	<td align="center" colspan="5">등록된 게시판이 없습니다.</td>
</tr>
<?php }//if ?>
</table>

<div align="right" style="margin-top:10px;">
	<input type="button" value="등록" onclick="goWrite();" />
</div>

<?php fnPrintPagingHtml($pagingInfoMap); ?>

<form name="paramForm" method="get">
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
</form>

<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutEnd.php'); ?>

<script>
var paramFormObject = document.paramForm;
//---
function goPage(pageNumber){
	paramFormObject.pageNumber.value = pageNumber;
	paramFormObject.action = 'boardMas.php';
	paramFormObject.submit();
}
function goView(bdSeq=''){
	paramFormObject.bdSeq.value = bdSeq;
	paramFormObject.action = 'boardMasView.php';
	paramFormObject.submit();
}
function goWrite(bdSeq=''){
	paramFormObject.bdSeq.value = bdSeq;
	paramFormObject.action = 'boardMasWrite.php';
	paramFormObject.submit();
}
function goBoardArticleList(bdSeq=''){
	var openWin = null;
	var url = '';
	url += 'boardDtl.php';
	url += '?bdSeq='+bdSeq;
	openWin = window.open(url,'_blank');
	openWin.focus();
}
function copyBoardArticleListUrl(bdSeq=''){
	var url = '';
	url += '<?php echo $envVarMap["appWebPath"]; ?>';
	url += '/brdImg/boardDtl.php';
	url += '?bdSeq='+bdSeq;
	prompt('게시글 관리 경로 문자열을 복사해 주세요.',url);
}
function goSearch(){
	paramFormObject.schTitle.value = $('#schTitle').val();
	paramFormObject.schContent.value = $('#schContent').val();
	paramFormObject.pageNumber.value = '1';
	paramFormObject.action = 'boardMas.php';
	paramFormObject.submit();
}
function resetSearch(){
	if(confirm('검색을 초기화 하시겠습니까?')){
		$('#schTitle').val('');
		$('#schContent').val('');
		goSearch();
	}//if
}
function copyBoardSeq(bdSeq=''){
	prompt('게시판 번호를 복사해 주세요.',bdSeq);
}
</script>

</body>
</html>