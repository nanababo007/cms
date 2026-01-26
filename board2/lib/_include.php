<?php
	if(!session_id()) {session_start();}#if
	include $_SERVER['DOCUMENT_ROOT']."/board2/lib/var.php";
	include $envVarMap["appPath"]."/lib/func.php";
	include $envVarMap["appPath"]."/lib/dateLibrary.php";
	include $envVarMap["appPath"]."/lib/dbClassLibrary.php";
	include $envVarMap["appPath"]."/lib/paging.php";
	include $envVarMap["appPath"]."/lib/responseLibrary.php";
	include $envVarMap["appPath"]."/lib/exceptionLibrary.php";
	include $envVarMap["appPath"]."/lib/FileUtilLibrary.php";
	include $envVarMap["appPath"]."/lib/FileUploadLibrary.php";
	#---
	include $envVarMap["appPath"]."/lib/apps/historyAppsLibrary.php";
?>