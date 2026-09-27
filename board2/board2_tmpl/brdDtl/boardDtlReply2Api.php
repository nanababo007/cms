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
if($actionString=="GET_REPLY2_LIST"){
	$bdaSeq = nvl(getPostValue("bdaSeq"),"");
	$bdrSeq = nvl(getPostValue("bdrSeq"),"");
	#---
	if($bdrSeq==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	$sql = "
		select
			a.*
		from (
			select
				a.bdr2_seq
				,a.bdr_seq
				,a.bda_seq
				,a.bdr2_content
				,ifnull(a.bdr2_fix_yn,'N') as bdr2_fix_yn
				,'Y' as list_bdr2_fix_yn
				,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
				,STR_TO_DATE(a.regdate, '%Y-%m-%d %H:%i:%s') as regdatetime_str
				,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
				,STR_TO_DATE(a.moddate, '%Y-%m-%d %H:%i:%s') as moddatetime_str
				,a.regdate
				,a.reguser
				,a.moddate
				,a.moduser
			from tb_board_reply2 a
			where a.bda_seq = ${bdaSeq}
			and a.bdr_seq = ${bdrSeq}
			and a.bdr2_fix_yn = 'Y'
			union all
			select
				a.bdr2_seq
				,a.bdr_seq
				,a.bda_seq
				,a.bdr2_content
				,ifnull(a.bdr2_fix_yn,'N') as bdr2_fix_yn
				,'N' as list_bdr2_fix_yn
				,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
				,STR_TO_DATE(a.regdate, '%Y-%m-%d %H:%i:%s') as regdatetime_str
				,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
				,STR_TO_DATE(a.moddate, '%Y-%m-%d %H:%i:%s') as moddatetime_str
				,a.regdate
				,a.reguser
				,a.moddate
				,a.moduser
			from tb_board_reply2 a
			where a.bda_seq = ${bdaSeq}
			and a.bdr_seq = ${bdrSeq}
		) a
		order by 
			case when a.list_bdr2_fix_yn = 'Y' then 1 else 2 end asc,
			a.bdr2_seq desc
	";
	$listData = fnDBGetList($sql);
	#---
	$responseData['listData'] = $listData;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else if($actionString=="GET_REPLY2_ROW"){
	$bdr2Seq = nvl(getPostValue("bdr2Seq"),"");
	#---
	if($bdr2Seq==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	$sql = "
		select 
			a.* 
		from tb_board_reply2 a
		where a.bdr2_seq = ${bdr2Seq}
	";
	$rowData = fnDBGetRow($sql);
	#---
	$responseData['rowData'] = $rowData;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else if($actionString=="INSERT_REPLY2_DATA"){
	$bdaSeq = nvl(getPostValue("bdaSeq"),"");
	$bdrSeq = nvl(getPostValue("bdrSeq"),"");
	$bdr2Content = nvl(getPostValue("bdr2Content"),"");
	#---
	if($bdaSeq=="" or $bdr2Content==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	$sql = "
		insert into tb_board_reply2 (
			bda_seq,
			bdr_seq,
			bdr2_content,
			regdate,
			reguser
		) values (
			${bdaSeq},
			${bdrSeq},
			'${bdr2Content}',
			NOW(3),
			'admin'
		)
	";
	$affectedQueryCount = fnDBUpdate($sql);
	#---
	$sql = "SELECT LAST_INSERT_ID()";
	$bdr2Seq = nvl(fnDBGetStringValue($sql));
	#---
	$responseData['affectedQueryCount'] = $affectedQueryCount;
	$responseData['bdr2Seq'] = $bdr2Seq;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else if($actionString=="UPDATE_REPLY2_DATA"){
	$bdr2Seq = trim(nvl(getPostValue("bdr2Seq"),""));
	$bdaSeq = trim(nvl(getPostValue("bdaSeq"),""));
	$bdrSeq = trim(nvl(getPostValue("bdrSeq"),""));
	$bdr2Content = nvl(getPostValue("bdr2Content"),"");
	#---
	if($bdr2Seq=="" or $bdaSeq=="" or $bdrSeq=="" or $bdr2Content==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	fnHistInsertBoardReply2($bdr2Seq);
	#---
	$sql = "
		update tb_board_reply2 set
			bdr2_content = '${bdr2Content}',
			moddate = NOW(3),
			moduser = 'admin'
		where bdr2_seq = '${bdr2Seq}'
		and bda_seq = '${bdaSeq}'
		and bdr_seq = '${bdrSeq}'
	";
	$affectedQueryCount = fnDBUpdate($sql);
	#---
	$responseData['affectedQueryCount'] = $affectedQueryCount;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else if($actionString=="DELETE_REPLY2_DATA"){
	$bdr2Seq = trim(nvl(getPostValue("bdr2Seq"),""));
	$bdaSeq = trim(nvl(getPostValue("bdaSeq"),""));
	$bdrSeq = trim(nvl(getPostValue("bdrSeq"),""));
	#---
	if($bdr2Seq=="" or $bdaSeq=="" or $bdrSeq==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	fnHistInsertBoardReply2($bdr2Seq);
	#---
	$sql = "
		delete from tb_board_reply2
		where bdr2_seq = '${bdr2Seq}'
		and bda_seq = '${bdaSeq}'
		and bdr_seq = '${bdrSeq}'
	";
	$affectedQueryCount = fnDBUpdate($sql);
	#---
	$responseData['affectedQueryCount'] = $affectedQueryCount;
	#---
	$responseLibraryObject->setResponseDataObject('data',$responseData);
	$responseLibraryObject->setSuccessResponseData();
	responseJson();
}else if($actionString=="FIX_REPLY2_DATA"){
	$bdr2Seq = nvl(getPostValue("bdr2Seq"),"");
	$bdr2FixYN = nvl(getPostValue("bdr2FixYN"),"N");
	#---
	if($bdr2Seq==""){
		$responseLibraryObject->setResponseUserErrorData("need_param");
		responseJson();
	}#if
	#---
	$sql = "
		update tb_board_reply2 set
			bdr2_fix_yn = '${bdr2FixYN}'
		where bdr2_seq like '${bdr2Seq}'
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
?>