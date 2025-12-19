<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2_ins/lib/_include.php');
#---
ResponseLibraryClass::setHeaderJson();
$responseLibraryObject = new ResponseLibraryClass();
$responseData = array();
$actionString = nvl(getPostValue("actionString"),"");
#---
if($modeString=="server"){
	$responseLibraryObject->setResponseUserErrorData("only_local_mode_excutable");
	responseJson();
}#if
#---
#fnOpenDB();
#---
if($actionString=="CREATE_FILE"){
	$rowData = null;
	$varList = "";
	$varListArray = null;
	$varListArrayLength = 0;
	$varListArrayIndex = 0;
	$varListItemString = 0;
	$procVarListArray = null;
	$procVarListArrayLength = 0;
	$procVarListArrayIndex = 0;
	$procVarListItemString = "";
	$procVarNamePrefixString = 
	$procVarNameSuffixString = "";
	$procVarNameString = "";
	$procVarValueString = "";
	$procInfo = null;
	$replaceVarListArray = null;
	$replaceVarListArrayLength = 0;
	$replaceVarListArrayIndex = 0;
	$replaceVarListItemString = "";
	$replaceVarNamePrefixString = "";
	$replaceVarNameSuffixString = "";
	$replaceVarKeyNameString = "";
	$replaceVarNameString = "";
	$replaceVarValueString = "";
	$replaceInfo = null;
	$tgtFilePathString = "";
	$tgtFileFullPathString = "";
	$tgtFileContentString = "";
	$saveFolderPath = "";
	$saveFullFolderPath = "";
	$saveFolderDirPath = "";
	$saveFilenameWithExt = "";
	$sourceFolderPath = "";
	#---
	$rowData = array();
	$varList = nvl(getPostValue("varList"),"");
	$tgtFilePathString = trim(nvl(getPostValue("tgtFilePathString"),""));
	#---
	if($varList=="" || $tgtFilePathString==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	$varList = str_replace("\r\n","\n",$varList);
	$varListArray = explode("\n",$varList);
	$varListArrayLength = count($varListArray);
	#---
	$procVarListArray = array();
	$replaceVarListArray = array();
	foreach ($varListArray as $varListArrayIndex => $varListItemString) {
		if(str_starts_with($varListItemString,"proc.")){
			array_push($procVarListArray,$varListItemString);
		}else if(str_starts_with($varListItemString,"replace.")){
			array_push($replaceVarListArray,$varListItemString);
		}#if
	}#foreach
	$procVarListArrayLength = count($procVarListArray);
	$replaceVarListArrayLength = count($replaceVarListArray);
	#---
	if($procVarListArrayLength > 0){
		$procInfo = array();
		#---
		foreach ($procVarListArray as $procVarListArrayIndex => $procVarListItemString) {
			list($procVarNameString,$procVarValueString) = splitBySubstring($procVarListItemString,"=");
			list($procVarNamePrefixString,$procVarNameSuffixString) = splitBySubstring($procVarNameString,".");
			#---
			$procVarNameSuffixString = trim(nvl($procVarNameSuffixString));
			#---
			$procInfo[$procVarNameSuffixString] = nvl($procVarValueString);
		}#foreach
		#---
		$saveFolderPath = trim(nvl($procInfo["saveFolderPath"]));
		$sourceFolderPath = trim(nvl($procInfo["sourceFolderPath"]));
	}#if
	#---
	if($saveFolderPath==""){
		$responseLibraryObject->setResponseUserErrorData("need_param_saveFolderPath");
		responseJson();
	}#if
	if(!is_dir($saveFolderPath)){
		$responseLibraryObject->setResponseUserErrorData("not_exist_saveFolderPath");
		responseJson();
	}#if
	#---
	if($sourceFolderPath==""){
		$responseLibraryObject->setResponseUserErrorData("need_param_sourceFolderPath");
		responseJson();
	}#if
	if(!is_dir($sourceFolderPath)){
		$responseLibraryObject->setResponseUserErrorData("not_exist_sourceFolderPath");
		responseJson();
	}#if
	#---
	if($replaceVarListArrayLength > 0){
		$replaceInfo = array();
		#---
		foreach ($replaceVarListArray as $replaceVarListArrayIndex => $replaceVarListItemString) {
			list($replaceVarNameString,$replaceVarValueString) = splitBySubstring($replaceVarListItemString,"=");
			list($replaceVarNamePrefixString,$replaceVarNameSuffixString) = splitBySubstring($replaceVarNameString,".");
			#---
			$replaceVarNameSuffixString = trim(nvl($replaceVarNameSuffixString));
			#---
			$replaceInfo[$replaceVarNameSuffixString] = nvl($replaceVarValueString);
		}#foreach
	}#if
	#---
	$tgtFileFullPathString = FileUtilLibraryClass::getForceCombinedSubFoldersPath($sourceFolderPath,$tgtFilePathString);
	if(!file_exists($tgtFileFullPathString)){
		$responseLibraryObject->setResponseUserErrorData("not_exist_file");
		responseJson();
	}#if
	# 파일 내용 읽어서, 변수에 저장 처리	
	$tgtFileContentString = FileUtilLibraryClass::getFileContentText($tgtFileFullPathString);
	# for debug start
	#$rowData["tgtFileFullPathString"] = $tgtFileFullPathString;
	#$rowData["tgtFileContentString"] = $tgtFileContentString;
	# for debug end
	#---
	#파일 내용을 치환 처리
	if($replaceVarListArrayLength > 0 && $replaceInfo!=null && nvl($tgtFileContentString)!=""){
		foreach ($replaceInfo as $replaceVarNameString => $replaceVarValueString) {
			$replaceVarKeyNameString = "{{replaceVarNameString}}";
			$replaceVarKeyNameString = str_replace("replaceVarNameString",$replaceVarNameString,$replaceVarKeyNameString);
			#---
			$tgtFileContentString = str_replace($replaceVarKeyNameString,$replaceVarValueString,$tgtFileContentString);
		}#foreach
	}#if
	# for debug start
	#$rowData["replaced_tgtFileFullPathString"] = $tgtFileFullPathString;
	#$rowData["replaced_tgtFileContentString"] = $tgtFileContentString;
	# for debug end
	#---
	$saveFullFolderPath = FileUtilLibraryClass::getForceCombinedSubFoldersPath($saveFolderPath,$tgtFilePathString);
	$saveFolderDirPath = FileUtilLibraryClass::getFileDirPathOnly($saveFullFolderPath);
	$saveFilenameWithExt = FileUtilLibraryClass::getFileNameWithExt($saveFullFolderPath);
	FileUtilLibraryClass::makeForceFolderPath($saveFolderDirPath);
	FileUtilLibraryClass::writeFileContentTextByFilepathAndFilename($saveFolderDirPath,$saveFilenameWithExt,$tgtFileContentString);
	# for debug start
	#$rowData["saveFullFolderPath"] = $saveFullFolderPath;
	#$rowData["saveFolderDirPath"] = $saveFolderDirPath;
	#$rowData["saveFilenameWithExt"] = $saveFilenameWithExt;
	# for debug end
	#---
	#$rowData["datakey"] = "data";
	#---
	$responseData['rowData'] = $rowData;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else{
	$responseLibraryObject->setUnknownErrorResponseData();
	responseJson();
}#if
#---
function releaseResource(){
	global $responseLibraryObject;
	#---
	$responseLibraryObject = null;
	#---
	#fnCloseDB();
}
function responseJson(){
	global $responseLibraryObject;
	$dataJsonString = json_encode($responseLibraryObject->getResponseData());
	echo $dataJsonString;
	releaseResource();
	exit();
}
?>