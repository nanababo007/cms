<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdImg/boardMasLibraryInclude.php');
#---
$boardInfo = null;
$actionString = getPostValue("actionString");
$pageNumber = intval(nvl(getPostValue("pageNumber"),"1"));
$pageSize = intval(nvl(getPostValue("pageSize"),"10"));
$blockSize = intval(nvl(getPostValue("blockSize"),"10"));
$mnSeq = nvl(getPostValue("mnSeq"),"");
$bdSeq = nvl(getPostValue("bdSeq"));
$schTitle = nvl(getPostValue("schTitle"),"");
$schContent = nvl(getPostValue("schContent"),"");
#---
debugString("actionString",$actionString);
debugString("pageNumber",$pageNumber);
debugString("pageSize",$pageSize);
debugString("blockSize",$blockSize);
debugArray("request values",$_REQUEST);
debugString("schTitle",$schTitle);
debugString("schContent",$schContent);
#---
fnOpenDB();
#---
if($bdSeq==""){alertBack("정보가 부족 합니다.");}#if
$boardInfo = fnBoardGetInfo($bdSeq);
if($boardInfo==null){alertBack("게시판 정보가 존재하지 않습니다.");}#if
debugArray("boardInfo",$boardInfo);
#---
if($actionString=="write"){
	$bdaTitle = nvl(getPostValue("bdaTitle"));
	$bdaContent = nvl(getPostValue("bdaContent"));
	$bdaFixYn = trim(nvl(getPostValue("bdaFixYn"),"N"));
	#---
	if($bdaTitle==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		insert into tb_board_img_article (
			bda_title
			,bda_content
			,bd_seq
			,bda_fix_yn
			,regdate
			,reguser
		)values(
			'${bdaTitle}'
			,'${bdaContent}'
			,'${bdSeq}'
			,'${bdaFixYn}'
			,NOW(3)
			,'admin'
		)
	";
	fnDBUpdate($sql);
	#---
	$sql = "SELECT LAST_INSERT_ID()";
	$bdaSeq = nvl(fnDBGetStringValue($sql));
	#---
	uploadFilesOfThisPage($bdaSeq);
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=1";
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	$moveUrlParam .= "&bdSeq=".$bdSeq;
	$moveUrlParam .= "&bdaSeq=".$bdaSeq;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	alertGo("처리 되었습니다.","boardDtl.php".$moveUrlParam);
}else if($actionString=="modify"){
	$bdaSeq = nvl(getPostValue("bdaSeq"));
	$bdaTitle = nvl(getPostValue("bdaTitle"));
	$bdaContent = nvl(getPostValue("bdaContent"));
	$bdaFixYn = trim(nvl(getPostValue("bdaFixYn"),"N"));
	#---
	debugString("bdaSeq",$bdaSeq);
	debugString("bdaTitle",$bdaTitle);
	debugString("bdaContent",$bdaContent);
	#---
	if($bdaSeq==""){alertBack("정보가 부족 합니다.");}#if
	if($bdaTitle==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		update tb_board_img_article set
			bda_title = '${bdaTitle}'
			,bda_content = '${bdaContent}'
			,bda_fix_yn = '${bdaFixYn}'
			,moddate = NOW(3)
			,moduser = 'admin'
		where bda_seq = ${bdaSeq}
	";
	fnDBUpdate($sql);
	#---
	uploadFilesOfThisPage($bdaSeq);
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=".$pageNumber;
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	$moveUrlParam .= "&bdSeq=".$bdSeq;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	alertGo("처리 되었습니다.","boardDtl.php".$moveUrlParam);
}else if($actionString=="delete"){
	$bdaSeq = nvl(getPostValue("bdaSeq"));
	#---
	if($bdaSeq==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		select count(*) as cnt
		from tb_board_img_reply
		where bda_seq = ${bdaSeq}
	";
	$replyCount = fnDBGetIntValue($sql);
	if($replyCount > 0){alertBack("댓글이 존재 합니다.\\n댓글을 모두 삭제 해주세요.");}#if
	#---
	$sql = "
		select count(*) as cnt
		from tb_board_img_article_file
		where bda_seq = ${bdaSeq}
	";
	$fileCount = fnDBGetIntValue($sql);
	if($fileCount > 0){alertBack("파일이 존재 합니다.\\n파일을 모두 삭제 해주세요.");}#if
	#---
	$sql = "
		delete from tb_board_img_article
		where bda_seq = ${bdaSeq}
	";
	#---
	fnDBUpdate($sql);
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=".$pageNumber;
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	$moveUrlParam .= "&bdSeq=".$bdSeq;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	alertGo("처리 되었습니다.","boardDtl.php".$moveUrlParam);
}else if($actionString=="deleteFile"){
	$bdafSeq = nvl(getPostValue("bdafSeq"));
	#---
	if($bdafSeq==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		delete from tb_board_img_article_file
		where bdaf_seq = ${bdafSeq}
	";
	fnDBUpdate($sql);
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=".$pageNumber;
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	$moveUrlParam .= "&bdSeq=".$bdSeq;
	$moveUrlParam .= "&bdaSeq=".nvl(getPostValue("bdaSeq"));;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	alertGo("처리 되었습니다.","boardDtlWrite.php".$moveUrlParam);
}else{
	alertBack("잘못된 접근 입니다.");
}#if
#---
exitPage();
#---
function uploadFilesOfThisPage($bdaSeq=""){
	$sql = "";
	#---
	$bdaFileCount = 12;
	$bdaFileIndex = 0;
	$bdaFileNumber = 0;
	$uploadFileInfo = null;
	$fileFormName = "";
	#---
	$fileUploadItemName = "";
	$filePathString = "";
	$fileWebPathString = "";
	$thumbnailPathString = "";
	$thumbnailWebPathString = "";
	#---
	if($bdaSeq==""){return;}#if
	#---
	for($bdaFileIndex=0;$bdaFileIndex<$bdaFileCount;$bdaFileIndex++){
		$bdaFileNumber = $bdaFileIndex + 1;
		$fileFormName = "file".$bdaFileNumber;
		$uploadFileInfo = uploadFileOfThisPage($fileFormName,"board2Img");
		#---
		if($uploadFileInfo==null){continue;}#if
		#---
		if($uploadFileInfo!=null){
			$fileUploadItemName = trim(nvl($uploadFileInfo["fileUploadItemName"]));
			$filePathString = trim(nvl($uploadFileInfo["filePathString"]));
			$fileWebPathString = trim(nvl($uploadFileInfo["fileWebPathString"]));
			$thumbnailPathString = trim(nvl($uploadFileInfo["thumbnailPathString"]));
			$thumbnailWebPathString = trim(nvl($uploadFileInfo["thumbnailWebPathString"]));
			#---
			$sql = "
				SELECT count(*) 
				FROM tb_board_img_article_file
				where bda_seq = ${bdaSeq}
				and bdaf_kind_name = '${fileFormName}'
			";
			$checkFileInfo = fnDBGetIntValue($sql);
			#---
			if($checkFileInfo==0){
				$sql = "
					insert into tb_board_img_article_file (
						bda_seq
						,bdaf_filename
						,bdaf_save_filename
						,bdaf_save_thumbnail
						,bdaf_kind_name
						,regdate
						,reguser
					)values(
						'${bdaSeq}'
						,'${fileUploadItemName}'
						,'${fileWebPathString}'
						,'${thumbnailWebPathString}'
						,'${fileFormName}'
						,NOW(3)
						,'admin'
					)
				";
				fnDBUpdate($sql);
			}else{
				$sql = "
					update tb_board_img_article_file set
						bdaf_filename = '${fileUploadItemName}'
						,bdaf_save_filename = '${fileWebPathString}'
						,bdaf_save_thumbnail = '${thumbnailWebPathString}'
						,moddate = NOW(3)
						,moduser = 'admin'
					where bda_seq = ${bdaSeq}
					and bdaf_kind_name = '${fileFormName}'
				";
				fnDBUpdate($sql);
			}#if
		}#if
	}#for
	#---
	$uploadFileInfo = null;
}
function uploadFileOfThisPage($fileFormName="",$saveFolderName=""){
	global $envVarMap;
	#---
	$returnObject = null;
	$fileUploadLibraryObject = null;
	$executeFileUploadResultAllObject = null;
	$executeFileUploadProcedureResultObject = null;
	$executeFileUploadResultObject = null;
	#---
	$thumbnailImageFileName = "";
	$thumbnailImageFilePath = "";
	$thumbnailImageWidth = 0;
	$thumbnailImageHeight = 0;
	$thumbnailImageNewWidth = 0;
	$thumbnailImageDebugOption = false;
	$filePathString = "";
	$fileWebPathString = "";
	$fileUploadItemName = "";
	$thumbnailPathString = "";
	$thumbnailWebPathString = "";
	$thumbnailImageResultArray = null;
	#---
	if($fileFormName==""){return null;}#if
	if($saveFolderName==""){return null;}#if
	#---
	$fileUploadLibraryObject = new FileUploadLibraryClass($fileFormName,$saveFolderName,"file","",$envVarMap["debugMode"],false);
	$executeFileUploadResultAllObject = $fileUploadLibraryObject->executeFileUpload();
	$executeFileUploadProcedureResultObject = $executeFileUploadResultAllObject['executeProcedureResultObject'];
	$executeFileUploadResultObject = $executeFileUploadResultAllObject['executeUploadResultObject'];
	#---
	if($executeFileUploadProcedureResultObject->getResultFlagString()=="success"){
		$thumbnailImageNewWidth = 300;
		$thumbnailImageDebugOption = $envVarMap["debugMode"];
		$filePathString = trim(nvl($executeFileUploadResultObject["fileUploadImagesFileSaveDirectoryPath"]));
		$fileWebPathString = trim(nvl($executeFileUploadResultObject["fileUploadImagesFileSaveWebPath"]));
		$fileUploadItemName = trim(nvl($executeFileUploadResultObject["fileUploadItemName"]));
		#---
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
			$thumbnailPathString = FileUtilLibraryClass::getThumbnailPath($filePathString,(string)$thumbnailImageNewWidth);
			$thumbnailWebPathString = FileUtilLibraryClass::getThumbnailPath($fileWebPathString,(string)$thumbnailImageNewWidth);
		}#if
		#---
		$returnObject = array();
		$returnObject["fileUploadItemName"] = $fileUploadItemName;
		$returnObject["filePathString"] = $filePathString;
		$returnObject["fileWebPathString"] = $fileWebPathString;
		$returnObject["thumbnailPathString"] = $thumbnailPathString;
		$returnObject["thumbnailWebPathString"] = $thumbnailWebPathString;
	}#if
	#---
	debugArray("* 파일 업로드 결과 정보",$executeFileUploadResultObject);
	debugArray("* 썸네일 파일 결과 정보",$thumbnailImageResultArray);
	debugString("* 파일 업로드 결과 정보 (업로드 파일 디렉토리 경로 정보)",$filePathString);
	debugString("* 파일 업로드 결과 정보 (업로드 파일 웹 경로 정보)",$fileWebPathString);
	debugString("* 파일 업로드 결과 정보 (업로드 파일 썸네일 디렉토리 경로 정보)",$thumbnailPathString);
	debugString("* 파일 업로드 결과 정보 (업로드 파일 썸네일 웹 경로 정보)",$thumbnailWebPathString);
	debugArray("* 파일 업로드 결과 정보 (returnObject)",$returnObject);
	#---
	$fileUploadLibraryObject = null;
	#---
	return $returnObject;
}
function releasePageObjects(){
	global $uploadFileInfo;
	#---
	$uploadFileInfo = null;
	fnCloseDB();
}
function exitPage(){
	releasePageObjects();
	fnExit();
}
?>