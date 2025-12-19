<?php
/*
$fileUploadLibraryObject = null;
$executeFileUploadResultAllObject = null;
$executeFileUploadProcedureResultObject = null;
$executeFileUploadResultObject = null;
#---
$fileUploadLibraryObject = new FileUploadLibraryClass("fileFormName1","portpolio","file","/fileupload_onsuccess_movepath",false);
$executeFileUploadResultAllObject = $fileUploadLibraryObject->executeFileUpload();
$executeFileUploadProcedureResultObject = $executeFileUploadResultAllObject['executeProcedureResultObject'];
$executeFileUploadResultObject = $executeFileUploadResultAllObject['executeUploadResultObject'];
if($executeFileUploadProcedureResultObject->getResultFlagString()!="success"){pageExit();}#if
echo "<br /><br />* 파일 업로드 결과 정보<br /><br />";
print_r($executeFileUploadResultObject);
releasePageObjects();
#$fileUploadLibraryObject = null; //releasePageObjects(); 메서드 내에 코드 위치.
*/
class FileUploadLibraryClass
{
	private $optionMap = null;
	private $fileFormName = "";
	private $saveUploadFolder = "";
	private $fileSavePrefixString = "";
	private $fileUploadSuccessMovePathString = "";
	#---
	public function __construct(
		$fileFormName = "",
		$saveUploadFolder="temp",
		$fileSavePrefixString="file",
		$fileUploadSuccessMovePathString="",
		$debugFlag=false,
		$moveFlag=true
	){
		$this->optionMap = array();
		$this->optionMap['DEBUG_FLAG'] = $debugFlag;
		$this->optionMap['MOVE_FLAG'] = $moveFlag;
		$this->fileFormName = $fileFormName;
		$this->saveUploadFolder = $saveUploadFolder;
		$this->fileSavePrefixString = $fileSavePrefixString;
		$this->fileUploadSuccessMovePathString = $fileUploadSuccessMovePathString;
	}
	public function __destruct() {
		$this->optionMap = null;
	}
	#---
	public function executeFileUpload(){
		$returnValue = null;
		$uploadExecuteResultObject = null;
		$exceptionLibraryObject = null;
		#---
		if($this->optionMap['DEBUG_FLAG']){
			echo "* request files<br /><br />";print_r($_FILES);echo "<br /><br />";
			echo "* request post<br /><br />";print_r($_POST);echo "<br /><br />";
		}#if
		#---
		$uploadExecuteResultObject = array();
		$exceptionLibraryObject = $this->executeFileUploadProcedure(
			$uploadExecuteResultObject,
			$this->saveUploadFolder,
			$this->fileSavePrefixString
		);
		if($this->optionMap['DEBUG_FLAG']){
			if($exceptionLibraryObject->getResultFlagString()=="success"){
				debugString("파일 업로드 처리 결과 (성공)","업로드 처리 성공!!");
			}else{
				debugString("파일 업로드 처리 결과 (실패)",$exceptionLibraryObject->getResultMessage());
			}#if
		}else{
			if($this->optionMap['MOVE_FLAG']){
				if($exceptionLibraryObject->getResultFlagString()=="success"){
					if(nvl($this->fileUploadSuccessMovePathString)!=""){
						alertGo("파일 업로드가 정상적으로 처리 되었습니다.",$this->fileUploadSuccessMovePathString);
					}#if
				}else{
					alertBack($exceptionLibraryObject->getResultMessage());
				}#if
			}#if
		}#if
		#---
		$returnValue = array();
		$returnValue['executeProcedureResultObject'] = $exceptionLibraryObject;
		$returnValue['executeUploadResultObject'] = $uploadExecuteResultObject;
		return $returnValue;
	}
	# 함수 처리 결과값은 참조 파라미터 $uploadExecuteResultObject 로 반환되고, 
	# 함수 반환값은 오류 객체 반환 $exceptionLibraryObject
	public function executeFileUploadProcedure(&$uploadExecuteResultObject,$saveUploadFolder="temp",$fileSavePrefixString="file"){
		global $envVarMap;
		$exceptionLibraryObject = null;
		$allowedMaxFileUploadSizeForMegaByte = 0;
		$allowedMaxFileUploadSize = 0;
		$allowedExtensionsString = "";
		$allowedExtensionsArray = null;
		$fileUploadItemInfo = null;
		$fileUploadItemExtension = "";
		$fileUploadItemFile = null;
		$fileUploadItemName = "";
		$fileUploadItemType = "";
		$fileUploadItemSize = 0;
		$fileUploadItemTmpName = "";
		$fileUploadItemError = "";
		$fileUploadItemResultFileSaveNamePrefix = "";
		$fileUploadItemResultFileSaveName = "";
		$fileUploadItemResultFileSaveDirectory = "";
		$fileUploadItemResultFileSaveDirectoryPath = "";
		$fileUploadItemResultFileSaveWebPath = "";
		#---
		extract($envVarMap);
		$exceptionLibraryObject = new ExceptionLibraryClass();
		$allowedMaxFileUploadSizeForMegaByte = $fileUploadAllowedMaxFileUploadSizeForMegaByteGlobalValue;
		$allowedMaxFileUploadSize = $fileUploadAllowedMaxFileUploadSizeGlobalValue;
		$allowedExtensionsString = $fileUploadAllowedExtensionsStringGlobalValue;
		$allowedExtensionsArray = explode(',', $allowedExtensionsString);
		$fileUploadItemResultFileSaveNamePrefix = $fileSavePrefixString;
		#---
		$fileUploadItemFile = $_FILES[$this->fileFormName];
		$fileUploadItemName = trim(nvl($fileUploadItemFile['name']));
		$fileUploadItemType = $fileUploadItemFile['type'];
		$fileUploadItemSize = $fileUploadItemFile['size'];
		$fileUploadItemTmpName = $fileUploadItemFile['tmp_name'];
		$fileUploadItemError = $fileUploadItemFile['error'];
		#---
		if($fileUploadItemName==""){
			$exceptionLibraryObject->setErrorInformation('error', 'no_file', '업로드 파일이 없습니다.');
			return $exceptionLibraryObject;
		}#if
		#---
		$uploadExecuteResultObject['fileUploadItemFile'] = $fileUploadItemFile;
		$uploadExecuteResultObject['fileUploadItemName'] = $fileUploadItemName;
		$uploadExecuteResultObject['fileUploadItemType'] = $fileUploadItemType;
		$uploadExecuteResultObject['fileUploadItemSize'] = $fileUploadItemSize;
		$uploadExecuteResultObject['fileUploadItemTmpName'] = $fileUploadItemTmpName;
		$uploadExecuteResultObject['fileUploadItemError'] = $fileUploadItemError;
		$uploadExecuteResultObject['fileUploadItemSaveName'] = '';
		$uploadExecuteResultObject['fileUploadItemSaveWebPath'] = '';
		#---
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($fileUploadItemError === UPLOAD_ERR_OK) {
				if(isset($fileUploadItemName)){
					$fileUploadItemInfo = pathinfo($fileUploadItemName);
					$fileUploadItemExtension = trim(strtolower(getArrayValue($fileUploadItemInfo,'extension')));
					if($this->optionMap['DEBUG_FLAG']){
						debugString("fileUploadItemExtension", $fileUploadItemExtension);
					}#if
					#---
					if($fileUploadItemExtension==""){
						$exceptionLibraryObject->setErrorInformation('error', 'not_allowed_extension_error', '업로드 불가능 확장자');
						return $exceptionLibraryObject;
					}#if
					if(!in_array($fileUploadItemExtension, $allowedExtensionsArray)) {
						$exceptionLibraryObject->setErrorInformation('error', 'not_allowed_extension_error', '업로드 불가능 확장자');
						return $exceptionLibraryObject;
					}#if
					#---
					debugString("fileUploadItemSize",$fileUploadItemSize);
					debugString("allowedMaxFileUploadSize",$allowedMaxFileUploadSize);
					if($fileUploadItemSize > $allowedMaxFileUploadSize) {
						$exceptionLibraryObject->setErrorInformation('error', 'file_upload_size_limit_error', '파일 업로드 용량 제한 오류 ('.$allowedMaxFileUploadSizeForMegaByte.' MB)');
						return $exceptionLibraryObject;
					}#if
					#---
					$fileUploadItemResultFileSaveName = $fileUploadItemResultFileSaveNamePrefix.md5(microtime()).'.'.$fileUploadItemExtension;
					$fileUploadItemResultFileSaveMiddleDatePath = "/".FileUtilLibraryClass::getMiddleDatePath();
					$fileUploadItemResultFileSaveDirectory = $fileUploadRootDirectoryGlobalValue."/".$saveUploadFolder.$fileUploadItemResultFileSaveMiddleDatePath;
					$fileUploadItemResultFileSaveDirectoryPath = $fileUploadItemResultFileSaveDirectory
						.'/'.$fileUploadItemResultFileSaveName;
					$fileUploadItemResultFileSaveWebPath = $fileUploadRootWebPathGlobalValue."/".$saveUploadFolder.$fileUploadItemResultFileSaveMiddleDatePath.
						'/'.$fileUploadItemResultFileSaveName;
					#---
					if(!file_exists($fileUploadItemResultFileSaveDirectory)){
						$makeDir = mkdir($fileUploadItemResultFileSaveDirectory,0755,true);
						if (!$makeDir) {
							$exceptionLibraryObject->setErrorInformation('error', 'fail_create_upload_folder_error', '업로드 폴더 생성 실패');
							return $exceptionLibraryObject;
						}#if
					}#if
					if($this->optionMap['DEBUG_FLAG']){
						debugString("fileUploadItemResultFileSaveName", $fileUploadItemResultFileSaveName);
						debugString("fileUploadItemResultFileSaveDirectory", $fileUploadItemResultFileSaveDirectory);
						debugString("fileUploadItemResultFileSaveDirectoryPath", $fileUploadItemResultFileSaveDirectoryPath);
						debugString("fileUploadItemResultFileSaveWebPath", $fileUploadItemResultFileSaveWebPath);
						debugString("FileUtilLibraryClass::getMiddleDatePath", FileUtilLibraryClass::getMiddleDatePath());
					}#if
					if (move_uploaded_file($fileUploadItemTmpName, $fileUploadItemResultFileSaveDirectoryPath)) {
						$uploadExecuteResultObject['fileUploadImagesFileSaveName'] = $fileUploadItemResultFileSaveName;
						$uploadExecuteResultObject['fileUploadImagesFileSaveWebPath'] = $fileUploadItemResultFileSaveWebPath;
						$uploadExecuteResultObject['fileUploadImagesFileSaveDirectory'] = $fileUploadItemResultFileSaveDirectory;
						$uploadExecuteResultObject['fileUploadImagesFileSaveDirectoryPath'] = $fileUploadItemResultFileSaveDirectoryPath;
						#---
						$uploadExecuteResultObject['fileUploadItemSaveName'] = $fileUploadItemResultFileSaveDirectoryPath;
						$uploadExecuteResultObject['fileUploadItemSaveWebPath'] = $fileUploadItemResultFileSaveWebPath;
						#---
						$exceptionLibraryObject->setErrorInformation('success', '', '파일 업로드 성공');
						return $exceptionLibraryObject;
					} else {
						$exceptionLibraryObject->setErrorInformation('error', 'file_upload_error', '파일 업로드 오류');
						return $exceptionLibraryObject;
					}#if
				} else {
					$exceptionLibraryObject->setErrorInformation('error', 'no_submit_file_error', '전송 파일 부재 오류');
					return $exceptionLibraryObject;
				}#if
			} else {
				$exceptionLibraryObject->setErrorInformation('error', 'file_upload_error', '파일 업로드 오류');
				return $exceptionLibraryObject;
			}#if
		}else{
			$exceptionLibraryObject->setErrorInformation('error', 'request_method_error', '요청 방법 오류 (post only)');
			return $exceptionLibraryObject;
		}#if
		#---
		$uploadExecuteResultObject['exceptionLibraryObject'] = $exceptionLibraryObject;
		return $exceptionLibraryObject;
	}
}
?>