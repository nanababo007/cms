<?php
include($_SERVER["DOCUMENT_ROOT"].'/board/lib/func.php');
#---
fnOpenDB();
#---
$sqlBody = "
	FROM tb_board
";
$boardListTotalCount = fnDBGetIntValue("
	SELECT count(*)
	${sqlBody}
");
$boardList = fnDBGetList("
	SELECT * 
	${sqlBody}
");
#---
fnCloseDB();
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title>멀티게시판</title>
</head>
<body>
<h2>게시판 관리</h2>

<table>
<tr>
	<th>번호</th>
	<th>제목</th>
	<th>등록자</th>
	<th>등록일</th>
</tr>
<?php
foreach ($boardList as $index => $row) {
?>
<tr>
	<td><?php echo $row["aaaaa"]; ?></td>
	<td><?php echo $row["aaaaa"]; ?></td>
	<td><?php echo $row["aaaaa"]; ?></td>
	<td><?php echo $row["aaaaa"]; ?></td>
</tr>
<?php
}//foreach
?>
</table>

</body>
</html>