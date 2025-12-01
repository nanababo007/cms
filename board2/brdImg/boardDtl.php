<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdImg/boardMasLibraryInclude.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pagingListInfo.php');
#---
$thisPageMnSeq = 25;
$pageTitleString = "";
$boardInfo = null;
$boardListTotalCount = 0;
$pagingInfoMap = null;
$boardList = null;
$boardListCount = null;
$sqlSearchPart = "";
$sqlSearchPartIndex = 0;
$mnSeq = nvl(getRequestValue("mnSeq"),"");
$bdSeq = nvl(getRequestValue("bdSeq"),"");
$bdaSeq = nvl(getRequestValue("bdaSeq"),"");
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
$boardDtlFixList = null;
$boardDtlFixListCount = 0;
#---
debugString("bdSeq",$bdSeq);
#---
fnOpenDB();
setDisplayMenuList();
#---
$boardInfo = fnBoardGetInfo($bdSeq);
if($boardInfo==null){alertBack("게시판 정보가 존재하지 않습니다.");}#if
debugArray("boardInfo",$boardInfo);
#---
$pageTitleString = getArrayValue($boardInfo,"bd_nm")." | 멀티게시판";
#---
$sqlSearchPart .= "where a.bd_seq = ${bdSeq} ";
$sqlSearchPartIndex = 1;
if($schTitle!=""){
	$sqlSearchPart .= fnGetSqlWhereAndString($sqlSearchPartIndex);
	$sqlSearchPart .= " a.bda_title like '%${schTitle}%' ";
	$sqlSearchPartIndex++;
}#if
if($schContent!=""){
	$sqlSearchPart .= fnGetSqlWhereAndString($sqlSearchPartIndex);
	$sqlSearchPart .= " a.bda_content like '%${schContent}%' ";
	$sqlSearchPartIndex++;
}#if
#---
$sqlBodyPart = "
	FROM tb_board_img_article a
";
#---
$sqlFix = "
	select
		a.*
	from (
		SELECT
			a.bda_seq
			,a.bd_seq
			,a.bda_title
			,a.bda_view_cnt
			,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
			,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
			,a.regdate
			,a.reguser
			,a.moddate
			,a.moduser
		${sqlBodyPart}
		where a.bd_seq = ${bdSeq}
		and bda_fix_yn = 'Y'
	) a
	ORDER BY a.bda_seq DESC
";
$boardDtlFixList = fnDBGetList($sqlFix);
$boardDtlFixListCount = getArrayCount($boardDtlFixList);
#---
$sqlCount = "
	SELECT count(*)
	${sqlBodyPart}
	${sqlSearchPart}
";
$boardListTotalCount = fnDBGetIntValue($sqlCount);
#$boardListTotalCount = 328; #test
#---
$pagingInfoMap = fnCalcPaging($pageNumber,$boardListTotalCount,$pageSize,$blockSize);
debugArray("pagingInfoMap",$pagingInfoMap);
#---
$sqlMain = "
	select
		a.*
	from (
		SELECT
			a.bda_seq
			,a.bd_seq
			,a.bda_title
			,a.bda_view_cnt
			,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
			,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
			,a.regdate
			,a.reguser
			,a.moddate
			,a.moduser
		${sqlBodyPart}
		${sqlSearchPart}
	) a
	order by a.bda_seq desc
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

<h2>게시글 관리 (<?php echo getArrayValue($boardInfo,"bd_nm"); ?>) <span class="menu-navi-class"><?php echo getMenuPathString($thisPageMnSeq); ?></span></h2>

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
	<th>게시글 제목</th>
	<td><input type="text" id="schTitle" value="<?php echo $schTitle; ?>" /></td>
	<th>게시글 내용</th>
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
	<col width="20%" />
	<col width="20%" />
	<col width="20%" />
</colgroup>
<tr>
	<th>번호</th>
	<th>제목</th>
	<th>조회수</th>
	<th>등록자</th>
	<th>등록일</th>
</tr>
<?php
if($boardDtlFixListCount > 0){
	foreach ($boardDtlFixList as $index => $row) {
?>
<tr>
	<td align="center">고정</td>
	<td align="left"><a href="javascript:goView('<?php echo $row["bda_seq"]; ?>');"><?php echo $row["bda_title"]; ?></a></td>
	<td align="center"><?php echo $row["bda_view_cnt"]; ?></td>
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
	<td align="left"><a href="javascript:goView('<?php echo $row["bda_seq"]; ?>');"><?php echo $row["bda_title"]; ?></a></td>
	<td align="center"><?php echo $row["bda_view_cnt"]; ?></td>
	<td align="center"><?php echo $row["regdate_str"]; ?></td>
	<td align="center"><?php echo $row["reguser"]; ?></td>
</tr>
<?php
	}#foreach
}#if
?>
<?php if($boardListTotalCount == 0){ ?>
<tr>
	<td align="center" colspan="5">등록된 게시글이 없습니다.</td>
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
<input type="hidden" name="bdaSeq" value="<?php echo $bdaSeq; ?>" />
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
	paramFormObject.action = 'boardDtl.php';
	paramFormObject.submit();
}
function goView(bdaSeq=''){
	paramFormObject.bdaSeq.value = bdaSeq;
	paramFormObject.action = 'boardDtlView.php';
	paramFormObject.submit();
}
function goWrite(bdaSeq=''){
	paramFormObject.bdaSeq.value = bdaSeq;
	paramFormObject.action = 'boardDtlWrite.php';
	paramFormObject.submit();
}
function goSearch(){
	paramFormObject.schTitle.value = $('#schTitle').val();
	paramFormObject.schContent.value = $('#schContent').val();
	paramFormObject.pageNumber.value = '1';
	paramFormObject.action = 'boardDtl.php';
	paramFormObject.submit();
}
function resetSearch(){
	if(confirm('검색을 초기화 하시겠습니까?')){
		$('#schTitle').val('');
		$('#schContent').val('');
		goSearch();
	}//if
}
</script>

</body>
</html>