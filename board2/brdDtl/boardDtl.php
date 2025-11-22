<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
#---
$bdSeq = nvl(getRequestValue("bdSeq"),"");
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
#---
fnOpenDB();
#---
$sqlSearchPart = "";
#---
$sqlBodyPart = "
	FROM tb_board_info a
	${sqlSearchPart}
";
#---
$sqlCount = "
	SELECT count(*)
	${sqlBodyPart}
	order by a.regdate desc
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
<h2>게시판 관리</h2>

<table class="board-table-class">
<colgroup>
	<col width="10%" />
	<col width="*" />
	<col width="20%" />
	<col width="20%" />
</colgroup>
<tr>
	<th>번호</th>
	<th>게시판 이름</th>
	<th>등록자</th>
	<th>등록일</th>
</tr>
<?php
if($boardListTotalCount > 0){
	foreach ($boardList as $index => $row) {
?>
<tr>
	<td align="center"><?php echo $row["bd_seq"]; ?></td>
	<td align="left"><a href="javascript:goView('<?php echo $row["bd_seq"]; ?>');"><?php echo $row["bd_nm"]; ?></a></td>
	<td align="center"><?php echo $row["regdate_str"]; ?></td>
	<td align="center"><?php echo $row["reguser"]; ?></td>
</tr>
<?php
	}#foreach
}#if
?>
<?php if($boardListTotalCount == 0){ ?>
<tr>
	<td align="center" colspan="4">등록된 게시글이 없습니다.</td>
</tr>
<?php }//if ?>
</table>

<div align="right" style="margin-top:10px;">
	<input type="button" value="등록" onclick="goWrite();" />
</div>

<?php fnPrintPagingHtml($pagingInfoMap); ?>

<form name="paramForm" method="get">
<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
</form>

<script>
var paramFormObject = document.paramForm;
//---
function goPage(pageNumber){
	paramFormObject.pageNumber.value = pageNumber;
	paramFormObject.action = 'board.php';
	paramFormObject.submit();
}
function goView(bdSeq=''){
	paramFormObject.bdSeq.value = bdSeq;
	paramFormObject.action = 'boardView.php';
	paramFormObject.submit();
}
function goWrite(bdSeq=''){
	paramFormObject.bdSeq.value = bdSeq;
	paramFormObject.action = 'boardWrite.php';
	paramFormObject.submit();
}
</script>

</body>
</html>