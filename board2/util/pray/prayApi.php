<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
#---
ResponseLibraryClass::setHeaderJson();
$responseLibraryObject = new ResponseLibraryClass();
$responseData = array();
$actionString = nvl(getPostValue("actionString"),"");
#---
fnOpenDB();
#---
if($actionString=="GET_HIST_LIST"){
	pageExit();
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
	pageExit();
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
}else if($actionString=="INSERT_HIST_DATA"){
	$prhSeq = "";
	$prSeq = nvl(getPostValue("prSeq"),"");
	$prNo = nvl(getPostValue("prNo"),"");
	#---
	if($prSeq=="" or $prNo==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	$sql = "
		insert into tb_board_pray_hist (
			pr_seq,
			pr_no,
			prh_date
		) values (
			${prSeq},
			'${prNo}',
			NOW(3)
		)
	";
	$affectedQueryCount = fnDBUpdate($sql);
	#---
	$sql = "SELECT LAST_INSERT_ID()";
	$prhSeq = nvl(fnDBGetStringValue($sql));
	#---
	$responseData['affectedQueryCount'] = $affectedQueryCount;
	$responseData['prhSeq'] = $prhSeq;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else if($actionString=="UPDATE_REPLY_DATA"){
	pageExit();
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
	pageExit();
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
}else if($actionString=="FIX_REPLY_DATA"){
	pageExit();
	#---
	$bdrSeq = nvl(getPostValue("bdrSeq"),"");
	$bdrFixYN = nvl(getPostValue("bdrFixYN"),"N");
	#---
	if($bdrSeq==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	$sql = "
		update tb_board_reply set
			bdr_fix_yn = '${bdrFixYN}'
		where bdr_seq like '${bdrSeq}'
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
function pageExit(){
	releaseResource();
	exit();
}
?>