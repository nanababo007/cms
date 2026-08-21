<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
#---
ResponseLibraryClass::setHeaderJson();
$responseLibraryObject = new ResponseLibraryClass();
$responseData = array();
$actionString = nvl(getPostValue("actionString"),"");
#---
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLoginApi.php');
#---
fnOpenDB();
#---
if($actionString=="GET_REPLY_LIST"){
	exitPage();
	#---
	$bdaSeq = nvl(getPostValue("bdaSeq"),"");
	#---
	if($bdaSeq==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	$sql = "
		select
			a.*
		from (
			select
				a.bdr_seq
				,a.bda_seq
				,a.bdr_content
				,ifnull(a.bdr_fix_yn,'N') as bdr_fix_yn
				,'Y' as list_bdr_fix_yn
				,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
				,STR_TO_DATE(a.regdate, '%Y-%m-%d %H:%i:%s') as regdatetime_str
				,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
				,STR_TO_DATE(a.moddate, '%Y-%m-%d %H:%i:%s') as moddatetime_str
				,a.regdate
				,a.reguser
				,a.moddate
				,a.moduser
			from tb_board_reply a
			where bda_seq = ${bdaSeq}
			and a.bdr_fix_yn = 'Y'
			union all
			select
				a.bdr_seq
				,a.bda_seq
				,a.bdr_content
				,ifnull(a.bdr_fix_yn,'N') as bdr_fix_yn
				,'N' as list_bdr_fix_yn
				,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
				,STR_TO_DATE(a.regdate, '%Y-%m-%d %H:%i:%s') as regdatetime_str
				,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
				,STR_TO_DATE(a.moddate, '%Y-%m-%d %H:%i:%s') as moddatetime_str
				,a.regdate
				,a.reguser
				,a.moddate
				,a.moduser
			from tb_board_reply a
			where bda_seq = ${bdaSeq}
		) a
		order by 
			case when a.list_bdr_fix_yn = 'Y' then 1 else 2 end asc,
			a.bdr_seq desc
	";
	$listData = fnDBGetList($sql);
	#---
	$responseData['listData'] = $listData;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else if($actionString=="GET_REPLY_ROW"){
	exitPage();
	#---
	$bdrSeq = nvl(getPostValue("bdrSeq"),"");
	#---
	if($bdrSeq==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	$sql = "
		select * 
		from tb_board_reply
		where bdr_seq = ${bdrSeq}
	";
	$rowData = fnDBGetRow($sql);
	#---
	$responseData['rowData'] = $rowData;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else if($actionString=="INSERT_REPLY_DATA"){
	exitPage();
	#---
	$bdaSeq = nvl(getPostValue("bdaSeq"),"");
	$bdrContent = nvl(getPostValue("bdrContent"),"");
	#---
	if($bdaSeq=="" or $bdrContent==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	$sql = "
		insert into tb_board_reply (
			bda_seq,
			bdr_content,
			regdate,
			reguser
		) values (
			${bdaSeq},
			'${bdrContent}',
			NOW(3),
			'admin'
		)
	";
	$affectedQueryCount = fnDBUpdate($sql);
	#---
	$sql = "SELECT LAST_INSERT_ID()";
	$bdrSeq = nvl(fnDBGetStringValue($sql));
	#---
	$responseData['affectedQueryCount'] = $affectedQueryCount;
	$responseData['bdrSeq'] = $bdrSeq;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else if($actionString=="UPDATE_REPLY_DATA"){
	exitPage();
	#---
	$bdrSeq = nvl(getPostValue("bdrSeq"),"");
	$bdrContent = nvl(getPostValue("bdrContent"),"");
	#---
	if($bdrSeq=="" or $bdrContent==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	fnHistInsertBoardReply($bdrSeq);
	#---
	$sql = "
		update tb_board_reply set
			bdr_content = '${bdrContent}',
			moddate = NOW(3),
			moduser = 'admin'
		where bdr_seq like '${bdrSeq}'
	";
	$affectedQueryCount = fnDBUpdate($sql);
	#---
	$responseData['affectedQueryCount'] = $affectedQueryCount;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else if($actionString=="DELETE_REPLY_DATA"){
	exitPage();
	#---
	$bdrSeq = nvl(getPostValue("bdrSeq"),"");
	#---
	if($bdrSeq==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	fnHistInsertBoardReply($bdrSeq);
	#---
	$sql = "
		delete from tb_board_reply
		where bdr_seq like '${bdrSeq}'
	";
	$affectedQueryCount = fnDBUpdate($sql);
	#---
	$responseData['affectedQueryCount'] = $affectedQueryCount;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else if($actionString=="FIX_BOARD_DATA"){
	$bdSeq = nvl(getPostValue("bdSeq"),"");
	$bdFixYN = nvl(getPostValue("bdFixYN"),"N");
	#---
	if($bdSeq==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	$sql = "
		update tb_board_img_info set
			bd_fix_yn = '${bdFixYN}'
		where bd_seq like '${bdSeq}'
	";
	$affectedQueryCount = fnDBUpdate($sql);
	#---
	$responseData['affectedQueryCount'] = $affectedQueryCount;
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
	fnCloseDB();
}
function responseJson(){
	global $responseLibraryObject;
	$dataJsonString = json_encode($responseLibraryObject->getResponseData());
	echo $dataJsonString;
	releaseResource();
	exit();
}
function exitPage(){
	releaseResource();
	exit();
}
?>