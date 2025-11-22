<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdMas/boardLibraryInclude.php');
#---
$bdSeq = nvl(getRequestValue("bdSeq"),"");
$bdaSeq = nvl(getRequestValue("bdaSeq"),"");
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$pageTitleString = "";
$boardInfo = null;
$boardListTotalCount = 0;
$pagingInfoMap = null;
$boardList = null;
$boardListCount = null;
#---
debugString("bdSeq",$bdSeq);
#---
fnOpenDB();
#---
$boardInfo = fnBoardGetInfo($bdSeq);
if($boardInfo==null){alertBack("게시판 정보가 존재하지 않습니다.");}#if
debugArray("boardInfo",$boardInfo);
#---
$pageTitleString = getArrayValue($boardInfo,"bd_nm")." | 멀티게시판";
#---
$sqlSearchPart = "";
$sqlSearchPart .= "where a.bd_seq = ${bdSeq} ";
#---
$sqlBodyPart = "
	FROM tb_board_article a
	${sqlSearchPart}
";
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
<h2>게시글 관리 (<?php echo getArrayValue($boardInfo,"bd_nm"); ?>)</h2>

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
if($boardListTotalCount > 0){
	foreach ($boardList as $index => $row) {
?>
<tr>
	<td align="center"><?php echo $row["bda_seq"]; ?></td>
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
<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
<input type="hidden" name="bdaSeq" value="<?php echo $bdaSeq; ?>" />
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
</form>

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
</script>

</body>
</html>