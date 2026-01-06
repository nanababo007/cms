<?php
$siteLoginId = "test";
$siteLoginPw = "1234";
$modeString = getServerModeString();
$envVarMap = array();
$globalResourceArray = array();
$displayMenuList = null;
$currentMenuSeq = 0;
$currentMenuInfo = null;
$currentTopMenuSeq = 0;
$currentTopMenuInfo = null;
$thisPageMnSeq = 0;
#---
if($modeString=="server"){
	$envVarMap["dbUsername"] = "root";
	$envVarMap["dbUserpwd"] = "";
	$envVarMap["dbHostname"] = "localhost";
	$envVarMap["dbName"] = "test";
	$envVarMap["appPath"] = $_SERVER['DOCUMENT_ROOT']."/board2";
	$envVarMap["appWebPath"] = "/board2";
	$envVarMap["pluginsWebPath"] = "/plugins";
	$envVarMap["debugMode"] = false;
	#--- file upload
	$envVarMap["fileUploadRootDirectoryGlobalValue"] = "/server_upload_path";
	$envVarMap["fileUploadRootWebPathGlobalValue"] = "/upload_web_path";
	$envVarMap["fileUploadAllowedMaxFileUploadSizeForMegaByteGlobalValue"] = 10;
	$envVarMap["fileUploadAllowedMaxFileUploadSizeGlobalValue"] = $envVarMap["fileUploadAllowedMaxFileUploadSizeForMegaByteGlobalValue"] * 1024 * 1024;
	$envVarMap["fileUploadAllowedExtensionsStringGlobalValue"] = "jpg,jpeg,png,gif,txt,doc,docx,xls,xlsx,ppt,pptx,pdf,zip";
}else{
	$envVarMap["dbUsername"] = "root";
	$envVarMap["dbUserpwd"] = "";
	$envVarMap["dbHostname"] = "localhost";
	$envVarMap["dbName"] = "test";
	$envVarMap["appPath"] = $_SERVER['DOCUMENT_ROOT']."/board2";
	$envVarMap["appWebPath"] = "/board2";
	$envVarMap["pluginsWebPath"] = "/plugins";
	$envVarMap["debugMode"] =  false;
	#--- file upload
	$envVarMap["fileUploadRootDirectoryGlobalValue"] = "C:/xampp/htdocs/data";
	$envVarMap["fileUploadRootWebPathGlobalValue"] = "/data";
	$envVarMap["fileUploadAllowedMaxFileUploadSizeForMegaByteGlobalValue"] = 10;
	$envVarMap["fileUploadAllowedMaxFileUploadSizeGlobalValue"] = $envVarMap["fileUploadAllowedMaxFileUploadSizeForMegaByteGlobalValue"] * 1024 * 1024;
	$envVarMap["fileUploadAllowedExtensionsStringGlobalValue"] = "jpg,jpeg,png,gif,txt,doc,docx,xls,xlsx,ppt,pptx,pdf,zip";
}#if
#---
function getServerModeString(){
	$returnValue = "";
	$httpHostString = "";
	$serverModeString = "";
	#---
	$httpHostString = $_SERVER['HTTP_HOST'];
	if(strpos($httpHostString,"localhost") !== false) {
		$serverModeString = "local";
	}else{
		$serverModeString = "server";
	}#if
	#---
	$returnValue = $serverModeString;
	return $returnValue;
}
?>
