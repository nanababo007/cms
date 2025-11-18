<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
#---
$pageNumber = intval(nvl(getArrayValue($_REQUEST,"pageNumber"),"1"));
$pageSize = intval(nvl(getArrayValue($_REQUEST,"pageSize"),"10"));
$blockSize = intval(nvl(getArrayValue($_REQUEST,"blockSize"),"10"));
#---
fnOpenDB();
#---
$sqlBody = "
	FROM tb_board_info a
";
$sqlCount = "
	SELECT count(*)
	${sqlBody}
";
$boardListTotalCount = fnDBGetIntValue($sqlCount);
$boardListTotalCount = 328; #test
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
	${sqlBody}
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
	<meta charset="utf-8">
	<title>멀티게시판</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="/board2/cmn/cmn.css">
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
	<th>제목</th>
	<th>등록자</th>
	<th>등록일</th>
</tr>
<?php
if($boardListTotalCount > 0){
	foreach ($boardList as $index => $row) {
?>
<tr>
	<td align="center"><?php echo $row["bd_seq"]; ?></td>
	<td align="left"><?php echo $row["bd_nm"]; ?></td>
	<td align="center"><?php echo $row["regdate_str"]; ?></td>
	<td align="center"><?php echo $row["reguser"]; ?></td>
</tr>
<?php
	}#foreach
}#if
?>
</table>

<?php
	fnPrintPagingHtml($pagingInfoMap);
?>

<script>
function goPage(pageNumber){
	location.href = 'board.php?pageNumber='+pageNumber;
}
</script>

</body>
</html>