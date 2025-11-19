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
	$envVarMap["pluginsWebPath"] = "/plugins";
	$envVarMap["debugMode"] = false;
}else{
	$envVarMap["dbUsername"] = "";
	$envVarMap["dbUserpwd"] = "";
	$envVarMap["dbHostname"] = "";
	$envVarMap["dbName"] = "";
	$envVarMap["appPath"] = $_SERVER['DOCUMENT_ROOT']."/board2";
	$envVarMap["appWebPath"] = "/board2";
	$envVarMap["pluginsWebPath"] = "/plugins";
	$envVarMap["debugMode"] = true;
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