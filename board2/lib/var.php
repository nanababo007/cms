<?php
$siteLoginId = "test";
$siteLoginPw = "1234";
$modeString = getServerModeString();
$mobileFlag = getMobileFlag();
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
    $envVarMap["linkAllowedHostsGlobalValue"] = "naver.com,daum.net,cafe24.com,tistory.com,.ac.kr,.org,.or.kr,go.kr,youtube.com,github.com,namu.wiki,youtu.be";
	#--- paging
	$envVarMap["mobilePagingBlockSize"] = 2;
	$envVarMap["pcPagingBlockSize"] = 5;
	if($mobileFlag){
		$envVarMap["pagingBlockSize"] = $envVarMap["mobilePagingBlockSize"];
	}else{
		$envVarMap["pagingBlockSize"] = $envVarMap["pcPagingBlockSize"];
	}#if
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
	$envVarMap["linkAllowedHostsGlobalValue"] = "localhost";
	#--- paging
	$envVarMap["mobilePagingBlockSize"] = 2;
	$envVarMap["pcPagingBlockSize"] = 5;
	if($mobileFlag){
		$envVarMap["pagingBlockSize"] = $envVarMap["mobilePagingBlockSize"];
	}else{
		$envVarMap["pagingBlockSize"] = $envVarMap["pcPagingBlockSize"];
	}#if
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
function getMobileFlag() {
	# 모바일 기기 식별을 위한 키워드 목록
	$mobileAgents = [
		"iPhone", "iPod", "Android", "BlackBerry", "Windows CE", 
		"Nokia", "WebOS", "Opera Mini", "SonyEricsson", "Opera Mobi"
	];
	$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
	# 키워드 매칭 검사
	foreach ($mobileAgents as $agent) {
		if (stripos($userAgent, $agent) !== false) {
			return true;
		}#if
	}#foreach
	return false;
}
?>
