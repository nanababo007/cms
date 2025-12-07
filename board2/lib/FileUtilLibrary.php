<?php
/*
	$middleDatePathString = FileUtilLibraryClass::getMiddleDatePath();
	$fileListArray = FileUtilLibraryClass::getFileList($dirname);
	$thumbnailImageResultArray = FileUtilLibraryClass::getThumbnailImage($filePathString="",$imageFileNewWidth=0,$debugFlag=false);
	$thumbnailImagePath = FileUtilLibraryClass::getThumbnailPath($filePathString="",$thumbnailSizeString="");
	$fileExtentionString = FileUtilLibraryClass::getFileExtention($filePathString="");
	$fileNameOnlyString = FileUtilLibraryClass::getFileNameOnly($filePathString="");
	$fileNameWithExtString = FileUtilLibraryClass::getFileNameWithExt($filePathString="");
	$fileDirPathOnlyString = FileUtilLibraryClass::getFileDirPathOnly($filePathString="");
	$fileTypeString = FileUtilLibraryClass::getFileTypeString($filePathString=""); # 파일종류 : img / txt / msoffice / zip / none / doc / etc
	$templateString = FileUtilLibraryClass::getReplaceTemplateVarValue("template string - {{varName}}","varName","varValue");
	$templateFileText = FileUtilLibraryClass::getFileContentText("c:\\a.txt");
	$templateFileText = FileUtilLibraryClass::getFileContentTextByFilepathAndFilename("c:\\folderpath\\","a.txt");
	FileUtilLibraryClass::writeFileContentText("c:\\a.txt","file write content");
	FileUtilLibraryClass::writeFileContentTextByFilepathAndFilename("c:\\folderpath\\","a.txt","file write content");
	$editFolderPath = FileUtilLibraryClass::getCombinedSubFoldersPath("c:\\folderpath\\","subFolderName1","subFolderName2"); # 경로 체크 함 (상대경로에서 사용하면 빈값반환)
	FileUtilLibraryClass::makeFolderPath("c:\\folderpath\\","subFolderName1","subFolderName2");
	$editFolderPath = FileUtilLibraryClass::getForceCombinedSubFoldersPath("c:\\folderpath\\","subFolderName1","subFolderName2"); # 경로 체크 안함
	FileUtilLibraryClass::makeForceFolderPath("c:\\folderpath\\","subFolderName1","subFolderName2");
	FileUtilLibraryClass::copyFile("c:\\folderpath\\orgFile.txt","c:\\folderpath2\\destFile.txt");
*/
/*
	$thumbnailImageFileName = "";
	$thumbnailImageFilePath = "";
	$thumbnailImageWidth = 0;
	$thumbnailImageHeight = 0;
	$thumbnailImageNewWidth = 0;
	$thumbnailImageDebugOption = false;
	$filePathString = "";
	$thumbnailImageResultArray = null;
	#---
	$thumbnailImageDebugOption = false;
	$filePathString = "/file/path/test.jpg";
	$thumbnailImageNewWidth = 300;
	$thumbnailImageResultArray = FileUtilLibraryClass::getThumbnailImage(
		$filePathString,
		$thumbnailImageNewWidth,
		$thumbnailImageDebugOption
	);
	if($thumbnailImageResultArray!=null){
		list(
		   $thumbnailImageFileName,
		   $thumbnailImageFilePath,
		   $thumbnailImageWidth,
		   $thumbnailImageHeight
		) = $thumbnailImageResultArray;
	}#if
*/
class FileUtilLibraryClass
{
	# return : '2025/01/01'
	public static function getMiddleDatePath(){
		$returnValue = "";
		#---
		$returnValue = date("Y/m/d");
		#---
		return $returnValue;
	}
    # 폴더 / 디렉토리 목록 가져오기
	public static function getFileList($dirname){
		$result_array = array();
		if(!file_exists($dirname)){return $result_array;}#if
		$handle = opendir($dirname);
		while ($file = readdir($handle)) {
			if($file == '.'||$file == '..') continue;
			if (is_file($dirname.$file)){array_push($result_array,$file);}#if
		}#while
		closedir($handle);
		sort($result_array);
		return $result_array;
	}
	/*
		$thumbnailImageFileName = "";
		$thumbnailImageFilePath = "";
		$thumbnailImageWidth = 0;
		$thumbnailImageHeight = 0;
		$thumbnailImageNewWidth = 0;
		$thumbnailImageDebugOption = false;
		$filePathString = "";
		$thumbnailImageResultArray = null;
		#---
		$thumbnailImageDebugOption = false;
		$filePathString = "/file/path/test.jpg";
		$thumbnailImageNewWidth = 300;
		$thumbnailImageResultArray = FileUtilLibraryClass::getThumbnailImage(
			$filePathString,
			$thumbnailImageNewWidth,
			$thumbnailImageDebugOption
		);
		if($thumbnailImageResultArray!=null){
			list(
			   $thumbnailImageFileName,
			   $thumbnailImageFilePath,
			   $thumbnailImageWidth,
			   $thumbnailImageHeight
			) = $thumbnailImageResultArray;
		}#if
	*/
	public static function getThumbnailImage($filePathString="",$imageFileNewWidth=0,$debugFlag=false){
		if($debugFlag){
			debugString("","");
			debugString("getThumbnailImage","function debug start =======");
		}#if
		#---
		$returnArray = null;
		$thumbnailInfoArray = null;
		$fileExtentionString = "";
		$allowImageFileExtentionArray = null;
		$thumbnailImageFileWidth = 0;
		$thumbnailImageFileHeight = 0;
		$thumbnailImageFileType = "";
		$thumbnailImageFileAttr = "";
		$thumbnailImageFileName = "";
		$thumbnailImageFilePath = "";
		$thumbnailImageWidth = 0;
		$thumbnailImageHeight = 0;
		$originalImageFileObject = null;
		$newImageFileObject = null;
		$imageFileNewHeight=0;
		$imageFileNewWidthPercent = 0.0;
		$newImageFileQualityNumber = 0;
		$newImageFilenameTemplateString = "";
		$fileNameString = "";
		$fileFolderString = "";
		#---
		debugString("FileUtilLibraryClass.getThumbnailImage filePathString",$filePathString);
		if($filePathString==""){return null;}#if
		if(!file_exists($filePathString)){return null;}#if
		$thumbnailInfoArray = array();
		$fileExtentionString = FileUtilLibraryClass::getFileExtention($filePathString);
		$allowImageFileExtentionArray = array("png","jpg","gif");
		if(!in_array($fileExtentionString,$allowImageFileExtentionArray)){return null;}#if
		$newImageFileQualityNumber = 100;
		#---
		list(
			$thumbnailImageFileWidth,
			$thumbnailImageFileHeight,
			$thumbnailImageFileType,
			$thumbnailImageFileAttr
		) = getimagesize($filePathString);
		if($thumbnailImageFileWidth>$imageFileNewWidth) {
			$imageFileNewWidthPercent = $imageFileNewWidth / $thumbnailImageFileWidth;
			$imageFileNewHeight = floor($thumbnailImageFileHeight * $imageFileNewWidthPercent);
			if($debugFlag){
				debugString("thumbnailImageFileWidth", $thumbnailImageFileWidth);
				debugString("thumbnailImageFileHeight", $thumbnailImageFileHeight);
				debugString("imageFileNewWidth", $imageFileNewWidth);
				debugString("imageFileNewHeight", $imageFileNewHeight);
				debugString("imageFileNewWidthPercent", $imageFileNewWidthPercent);
			}#if
			#---
			if($imageFileNewWidth==0 or $imageFileNewHeight==0){return null;}#if
			$newImageFileObject = imagecreatetruecolor($imageFileNewWidth,$imageFileNewHeight);
			if($fileExtentionString=="jpg"){
				$originalImageFileObject = imagecreatefromjpeg($filePathString);
			}else if($fileExtentionString=="png"){
				$originalImageFileObject = imagecreatefrompng($filePathString);
			}else if($fileExtentionString=="gif"){
				$originalImageFileObject = imagecreatefromgif($filePathString);
			}else{
				imagedestroy($newImageFileObject);
				return null;
			}#if
			imagecopyresampled(
				$newImageFileObject,
				$originalImageFileObject,
				0,0,0,0,
				$imageFileNewWidth,
				$imageFileNewHeight,
				$thumbnailImageFileWidth,
				$thumbnailImageFileHeight
			);
			#---
			$fileNameString = basename($filePathString);
			$fileNameString = str_replace(".png","",$fileNameString);
			$fileNameString = str_replace(".jpg","",$fileNameString);
			$fileNameString = str_replace(".gif","",$fileNameString);
			$fileFolderString = dirname($filePathString);
			#---
			$newImageFilenameTemplateString = "{{originalFilename}}_{{imageFileNewWidth}}x{{imageFileNewWidth}}.jpg";
			$newImageFilenameTemplateString = str_replace("{{originalFilename}}",$fileNameString,$newImageFilenameTemplateString);
			$newImageFilenameTemplateString = str_replace("{{imageFileNewWidth}}",$imageFileNewWidth,$newImageFilenameTemplateString);
			$thumbnailImageFileName = $newImageFilenameTemplateString;
			$thumbnailImageFilePath = $fileFolderString.DIRECTORY_SEPARATOR.$thumbnailImageFileName;
			imagejpeg($newImageFileObject,$thumbnailImageFilePath,$newImageFileQualityNumber);
			#---
			imagedestroy($originalImageFileObject);
			imagedestroy($newImageFileObject);
		}#if
		#---
		$thumbnailImageWidth = $imageFileNewWidth;
		$thumbnailImageHeight = $imageFileNewHeight;
		$returnArray = array(
			$thumbnailImageFileName,
			$thumbnailImageFilePath,
			$thumbnailImageWidth,
			$thumbnailImageHeight
		);
		if($debugFlag){
			debugString("-----------------------------", "");
			debugString("originalFilename",$fileNameString);
			debugString("originalFileFolder",$fileFolderString);
			debugString("thumbnailImageFileName",$thumbnailImageFileName);
			debugString("thumbnailImageFilePath",$thumbnailImageFilePath);
			debugString("thumbnailImageWidth",$thumbnailImageWidth);
			debugString("thumbnailImageHeight",$thumbnailImageHeight);
		}#if
		if($debugFlag){
			debugString("getThumbnailImage","function debug end =======");
			debugString("","");
		}#if
		return $returnArray;
	}
	public static function getFileExtention($filePathString=""){
		$returnString = "";
		$pathPartsArray = null;
		$fileExtensionString = "";
		#---
		if($filePathString==""){return "";}#if
		$pathPartsArray = pathinfo($filePathString);
		$fileExtensionString = getArrayValue($pathPartsArray,'extension');
		#---
		$returnString = trim(strtolower(nvl($fileExtensionString)));
		return $returnString;
	}
	public static function getReplaceTemplateVarValue(&$templateString="",$templateVarName="",$templateVarValue=""){
		$returnString = "";
		$editTemplateString = "";
		$editTemplateVarName = "";
		#---
		$templateVarName = trim($templateVarName);
		#---
		if($templateString!="" and $templateVarName!=""){
			$editTemplateString = $templateString;
			$editTemplateVarName = "{{".$templateVarName."}}";
			$editTemplateString = str_replace($editTemplateVarName,$templateVarValue,$editTemplateString);
		}#if
		#---
		$returnString = $editTemplateString;
		return $returnString;
	}
	public static function getFileContentTextByFilepathAndFilename($templateFolderPathString="",$templateFilenameString="",$charset="utf-8"){
		$returnText = "";
		$errorReturnText = "";
		$templatePathString = "";
		$templateFileText = "";
		#---
		$templateFolderPathString = trim($templateFolderPathString);
		$templateFolderPathString = str_replace("\\","/",$templateFolderPathString);
		$templateFilenameString = trim($templateFilenameString);
		$templateFilenameString = str_replace("\\","/",$templateFilenameString);
		#---
		if($templateFolderPathString=="" or $templateFilenameString==""){return $errorReturnText;}#if
		if(str_ends_with($templateFolderPathString,"/")){
			$templatePathString = $templateFolderPathString.$templateFilenameString;
		}else{
			$templatePathString = $templateFolderPathString."/".$templateFilenameString;
		}#if
		if(!file_exists($templatePathString)){return $errorReturnText;}#if
		$templateFileText = self::getFileContentText($templatePathString);
		#---
		$returnText = $templateFileText;
		return $returnText;
	}
	public static function getFileContentText($templateFilePathString="",$charset="utf-8"){
		$returnText = "";
		$templateFileContentText = "";
		$editTemplateFilePathString = "";
		#---
		$templateFilePathString = trim($templateFilePathString);
		if(file_exists($templateFilePathString)){
			$editTemplateFilePathString = $templateFilePathString;
			$editTemplateFilePathString = str_replace("\\","/",$editTemplateFilePathString);
			$editTemplateFilePathString = "file:///".$editTemplateFilePathString;
			$templateFileContentText = file_get_contents($editTemplateFilePathString, true);
		}#if
		#---
		$returnText = $templateFileContentText;
		return $returnText;
	}
	public static function writeFileContentTextByFilepathAndFilename(
		$templateFolderPathString="",$templateFilenameString="",$templateFileText="",$charset="utf-8"
	){
		$templatePathString = "";
		#---
		$templateFolderPathString = trim($templateFolderPathString);
		$templateFolderPathString = str_replace("\\","/",$templateFolderPathString);
		$templateFilenameString = trim($templateFilenameString);
		$templateFilenameString = str_replace("\\","/",$templateFilenameString);
		#---
		if($templateFolderPathString=="" or $templateFilenameString==""){return;}#if
		if(str_ends_with($templateFolderPathString,"/")){
			$templatePathString = $templateFolderPathString.$templateFilenameString;
		}else{
			$templatePathString = $templateFolderPathString."/".$templateFilenameString;
		}#if
		if(!file_exists($templateFolderPathString)){return;}#if
		self::writeFileContentText($templatePathString,$templateFileText);
	}
	public static function writeFileContentText($templateFilePathString="",$templateFileText="",$charset="utf-8"){
		$editTemplateFilePathString = "";
		#---
		$templateFilePathString = trim($templateFilePathString);
		$editTemplateFilePathString = $templateFilePathString;
		$editTemplateFilePathString = str_replace("\\","/",$editTemplateFilePathString);
		$editTemplateFilePathString = "file:///".$editTemplateFilePathString;
		file_put_contents($editTemplateFilePathString,$templateFileText);
	}
	public static function makeFolderPath(
		$templateFolderPathString="",$templateSub1FolderPath="",$templateSub2FolderPath=""
		,$templateSub3FolderPath="",$templateSub4FolderPath="",$templateSub5FolderPath=""
		,$templateSub6FolderPath="",$templateSub7FolderPath="",$templateSub8FolderPath=""
		,$templateSub9FolderPath="",$templateSub10FolderPath=""
	){
		$templatePathString = "";
		#---
		$templatePathString = self::getCombinedSubFoldersPath(
			$templateFolderPathString,$templateSub1FolderPath,$templateSub2FolderPath
			,$templateSub3FolderPath,$templateSub4FolderPath,$templateSub5FolderPath
			,$templateSub6FolderPath,$templateSub7FolderPath,$templateSub8FolderPath
			,$templateSub9FolderPath,$templateSub10FolderPath
		);
		if($templatePathString==""){return;}#if
		if(file_exists($templatePathString)){return;}#if
		#---
		mkdir($templatePathString, 0755, true);
	}
	public static function getCombinedSubFoldersPath(
		$templateFolderPathString="",$templateSub1FolderPath="",$templateSub2FolderPath=""
		,$templateSub3FolderPath="",$templateSub4FolderPath="",$templateSub5FolderPath=""
		,$templateSub6FolderPath="",$templateSub7FolderPath="",$templateSub8FolderPath=""
		,$templateSub9FolderPath="",$templateSub10FolderPath=""
	){
		$returnString = "";
		$errorReturnString = "";
		$templatePathString = "";
		$templateSubFolderPathString = "";
		$templateSubFolderPathArray = null;
		$templateSubFolderPathItemString = "";
		#---
		$templateFolderPathString = trim($templateFolderPathString);
		$templateFolderPathString = str_replace("\\","/",$templateFolderPathString);
		if($templateFolderPathString==""){return $errorReturnString;}#if
		if(!is_dir($templateFolderPathString) or !file_exists($templateFolderPathString)){return $errorReturnString;}#if
		#---
		$templateSubFolderPathArray = array();
		if($templateSub1FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub1FolderPath);}#if
		if($templateSub2FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub2FolderPath);}#if
		if($templateSub3FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub3FolderPath);}#if
		if($templateSub4FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub4FolderPath);}#if
		if($templateSub5FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub5FolderPath);}#if
		if($templateSub6FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub6FolderPath);}#if
		if($templateSub7FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub7FolderPath);}#if
		if($templateSub8FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub8FolderPath);}#if
		if($templateSub9FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub9FolderPath);}#if
		if($templateSub10FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub10FolderPath);}#if
		foreach($templateSubFolderPathArray as &$templateSubFolderPathItemString){
			$templateSubFolderPathItemString = trim($templateSubFolderPathItemString);
			$templateSubFolderPathItemString = str_replace("\\","/",$templateSubFolderPathItemString);
		}#foreach
		#---
		$templateSubFolderPathString = implode("/",$templateSubFolderPathArray);
		$templateSubFolderPathString = str_replace("//","/",$templateSubFolderPathString);
		$templateSubFolderPathString = str_replace("//","/",$templateSubFolderPathString);
		$templateSubFolderPathString = str_replace("//","/",$templateSubFolderPathString);
		#---
		if(str_ends_with($templateFolderPathString,"/")){
			$templatePathString = $templateFolderPathString.$templateSubFolderPathString;
		}else{
			$templatePathString = $templateFolderPathString."/".$templateSubFolderPathString;
		}#if
		$returnString = $templatePathString;
		return $returnString;
	}
	public static function makeForceFolderPath(
		$templateFolderPathString="",$templateSub1FolderPath="",$templateSub2FolderPath=""
		,$templateSub3FolderPath="",$templateSub4FolderPath="",$templateSub5FolderPath=""
		,$templateSub6FolderPath="",$templateSub7FolderPath="",$templateSub8FolderPath=""
		,$templateSub9FolderPath="",$templateSub10FolderPath=""
	){
		$templatePathString = "";
		#---
		$templatePathString = self::getForceCombinedSubFoldersPath(
			$templateFolderPathString,$templateSub1FolderPath,$templateSub2FolderPath
			,$templateSub3FolderPath,$templateSub4FolderPath,$templateSub5FolderPath
			,$templateSub6FolderPath,$templateSub7FolderPath,$templateSub8FolderPath
			,$templateSub9FolderPath,$templateSub10FolderPath
		);
		if($templatePathString==""){return;}#if
		if(file_exists($templatePathString)){return;}#if
		#---
		mkdir($templatePathString, 0755, true);
	}
	public static function getForceCombinedSubFoldersPath(
		$templateFolderPathString="",$templateSub1FolderPath="",$templateSub2FolderPath=""
		,$templateSub3FolderPath="",$templateSub4FolderPath="",$templateSub5FolderPath=""
		,$templateSub6FolderPath="",$templateSub7FolderPath="",$templateSub8FolderPath=""
		,$templateSub9FolderPath="",$templateSub10FolderPath=""
	){
		$returnString = "";
		$errorReturnString = "";
		$templatePathString = "";
		$templateSubFolderPathString = "";
		$templateSubFolderPathArray = null;
		$templateSubFolderPathItemString = "";
		#---
		$templateFolderPathString = trim($templateFolderPathString);
		$templateFolderPathString = str_replace("\\","/",$templateFolderPathString);
		if($templateFolderPathString==""){return $errorReturnString;}#if
		#---
		$templateSubFolderPathArray = array();
		if($templateSub1FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub1FolderPath);}#if
		if($templateSub2FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub2FolderPath);}#if
		if($templateSub3FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub3FolderPath);}#if
		if($templateSub4FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub4FolderPath);}#if
		if($templateSub5FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub5FolderPath);}#if
		if($templateSub6FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub6FolderPath);}#if
		if($templateSub7FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub7FolderPath);}#if
		if($templateSub8FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub8FolderPath);}#if
		if($templateSub9FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub9FolderPath);}#if
		if($templateSub10FolderPath!=""){array_push($templateSubFolderPathArray,$templateSub10FolderPath);}#if
		foreach($templateSubFolderPathArray as &$templateSubFolderPathItemString){
			$templateSubFolderPathItemString = trim($templateSubFolderPathItemString);
			$templateSubFolderPathItemString = str_replace("\\","/",$templateSubFolderPathItemString);
		}#foreach
		#---
		$templateSubFolderPathString = implode("/",$templateSubFolderPathArray);
		$templateSubFolderPathString = str_replace("//","/",$templateSubFolderPathString);
		$templateSubFolderPathString = str_replace("//","/",$templateSubFolderPathString);
		$templateSubFolderPathString = str_replace("//","/",$templateSubFolderPathString);
		#---
		if(str_ends_with($templateFolderPathString,"/")){
			$templatePathString = $templateFolderPathString.$templateSubFolderPathString;
		}else{
			$templatePathString = $templateFolderPathString."/".$templateSubFolderPathString;
		}#if
		$returnString = $templatePathString;
		return $returnString;
	}
	public static function copyFile($orgFilePathString="",$destFilePathString=""){
		$returnBool = false;
		$errorReturnBool = false;
		$isCopySuccessBool = false;
		#---
		$orgFilePathString = trim($orgFilePathString);
		$destFilePathString = trim($destFilePathString);
		#---
		if($orgFilePathString==""){return $errorReturnBool;}#if
		if($destFilePathString==""){return $errorReturnBool;}#if
		if(!file_exists($orgFilePathString)){return $errorReturnBool;}#if
		#---
		if(copy($orgFilePathString,$destFilePathString)){$isCopySuccessBool = true;}#if
		#---
		$returnBool = $isCopySuccessBool;
		return $returnBool;
	}
	public static function getFileNameOnly($filePathString=""){
		$returnString = "";
		$pathPartsArray = null;
		$fileNameOnlyString = "";
		#---
		if($filePathString==""){return "";}#if
		$pathPartsArray = pathinfo($filePathString);
		$fileNameOnlyString = getArrayValue($pathPartsArray,'filename');
		#---
		$returnString = trim(nvl($fileNameOnlyString));
		return $returnString;
	}
	public static function getFileNameWithExt($filePathString=""){
		$returnString = "";
		$pathPartsArray = null;
		$fileFileNameWithExtString = "";
		#---
		if($filePathString==""){return "";}#if
		$pathPartsArray = pathinfo($filePathString);
		$fileFileNameWithExtString = getArrayValue($pathPartsArray,'basename');
		#---
		$returnString = trim(nvl($fileFileNameWithExtString));
		return $returnString;
	}
	public static function getFileDirPathOnly($filePathString=""){
		$returnString = "";
		$pathPartsArray = null;
		$fileFileDirPathOnlyString = "";
		#---
		if($filePathString==""){return "";}#if
		$pathPartsArray = pathinfo($filePathString);
		$fileFileDirPathOnlyString = getArrayValue($pathPartsArray,'dirname');
		#---
		$returnString = trim(nvl($fileFileDirPathOnlyString));
		return $returnString;
	}
	# 파일종류 : img / txt / msoffice / zip / doc / none / etc
	public static function getFileTypeString($filePathString=""){
		$returnString = "";
		$pathPartsArray = null;
		$fileExtensionString = "";
		$fileTypeString = "";
		#---
		if($filePathString==""){return null;}#if
		$pathPartsArray = pathinfo($filePathString);
		$fileExtensionString = trim(strtolower(getArrayValue($pathPartsArray,'extension')));
		if($fileExtensionString==""){return "none";}#if
		#---
		if(strpos("jpg,png,gif",$fileExtensionString)!==false){
			$fileTypeString = "img";
		}else if(strpos("txt",$fileExtensionString)!==false){
			$fileTypeString = "txt";
		}else if(strpos("doc,docx,xls,xlsx,ppt,pptx",$fileExtensionString)!==false){
			$fileTypeString = "msoffice";
		}else if(strpos("zip,7z",$fileExtensionString)!==false){
			$fileTypeString = "zip";
		}else if(strpos("pdf",$fileExtensionString)!==false){
			$fileTypeString = "doc";
		}else{
			$fileTypeString = "etc";
		}#if
		#---
		$returnString = $fileTypeString;
		return $returnString;
	}
	# 원본 경로 문자열을 받아서, 썸네일 파일 경로 반환
	# thumbnailSizeString : "300" 등 사이즈 문자열
	public static function getThumbnailPath($filePathString="",$thumbnailSizeString=""){
		$returnString = "";
		$pathPartsArray = null;
		$fileNameWithExtString = "";
		$fileNameOnlyString = "";
		$fileFileExtString = "";
		$fileFilePathOnlyString = "";
		$fileTypeString = "";
		$thumbnailPathString = "";
		$fileNewPathNamyOnly = "";
		#---
		$thumbnailSizeString = trim(nvl((string)$thumbnailSizeString));
		if($filePathString==""){return "";}#if
		if($thumbnailSizeString==""){return "";}#if
		$filePathString = str_replace("\\","/",$filePathString);
		#---
		$pathPartsArray = pathinfo($filePathString);
		$fileNameWithExtString = trim(nvl(getArrayValue($pathPartsArray,'basename')));
		$fileNameOnlyString = trim(nvl(getArrayValue($pathPartsArray,'filename')));
		$fileFileExtString = trim(nvl(getArrayValue($pathPartsArray,'extension')));
		$fileFilePathOnlyString = trim(nvl(getArrayValue($pathPartsArray,'dirname')));
		#---
		$fileTypeString = self::getFileTypeString($filePathString);
		if($fileTypeString=="img"){
			$fileNewPathNamyOnly = self::getForceCombinedSubFoldersPath($fileFilePathOnlyString,$fileNameOnlyString);
			$thumbnailPathString = "{{fileNewPathNamyOnly}}_{{thumbnailSizeString}}x{{thumbnailSizeString}}.jpg";
			$thumbnailPathString = str_replace("{{fileNewPathNamyOnly}}",$fileNewPathNamyOnly,$thumbnailPathString);
			$thumbnailPathString = str_replace("{{thumbnailSizeString}}",$thumbnailSizeString,$thumbnailPathString);
		}#if
		#---
		$returnString = trim(nvl($thumbnailPathString));
		return $returnString;
	}
}
?>