<?php include $_SERVER['DOCUMENT_ROOT']."/board2/lib/_include.php"; ?>
<?php
	$_SESSION["loginId"] = "";
	#---
	header('Location: /board2/login.php');
?>