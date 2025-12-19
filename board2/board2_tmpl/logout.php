<?php include $_SERVER['DOCUMENT_ROOT']."/{{cms.prefix}}/lib/_include.php"; ?>
<?php
	$_SESSION["{{cms.prefix}}loginId"] = "";
	#---
	header('Location: /{{cms.prefix}}/login.php');
?>