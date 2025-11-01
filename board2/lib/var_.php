<?php
$modeString = getServerModeString();
$envVarMap = array();
#---
if($modeString=="server"){
	$envVarMap["dbUsername"] = "";
	$envVarMap["dbUserpwd"] = "";
	$envVarMap["dbHostname"] = "";
	$envVarMap["dbName"] = "";
	$envVarMap["appPath"] = $_SERVER['DOCUMENT_ROOT']."/board2";
	$envVarMap["appWebPath"] = "/board2";
}else{
	$envVarMap["dbUsername"] = "";
	$envVarMap["dbUserpwd"] = "";
	$envVarMap["dbHostname"] = "";
	$envVarMap["dbName"] = "";
	$envVarMap["appPath"] = $_SERVER['DOCUMENT_ROOT']."/board2";
	$envVarMap["appWebPath"] = "/board2";
}
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