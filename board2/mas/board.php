<?php
include($_SERVER["DOCUMENT_ROOT"].'/board/lib/func.php');
#---
fnOpenDB();
#---
$boardList = fnDBGetList("SELECT * FROM tb_board");
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

</body>
</html>