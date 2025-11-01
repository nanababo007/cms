<?php
	if(!session_id()) {session_start();}#if
	include $_SERVER['DOCUMENT_ROOT']."/board2/lib/var.php";
	include $envVarMap["appPath"]."/lib/func.php";
	include $envVarMap["appPath"]."/lib/paging.php";
?>