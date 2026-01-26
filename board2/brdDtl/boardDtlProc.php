<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdMas/boardLibraryInclude.php');
#---
$boardInfo = null;
$actionString = getPostValue("actionString");
$pageNumber = intval(nvl(getPostValue("pageNumber"),"1"));
$pageSize = intval(nvl(getPostValue("pageSize"),"10"));
$blockSize = intval(nvl(getPostValue("blockSize"),"10"));
$mnSeq = nvl(getPostValue("mnSeq"));
$bdSeq = nvl(getPostValue("bdSeq"));
$schTitle = nvl(getPostValue("schTitle"),"");
$schContent = nvl(getPostValue("schContent"),"");
$schReply = nvl(getPostValue("schReply"),"");
$schSRegdate = nvl(getRequestValue("schSRegdate"),"");
$schERegdate = nvl(getRequestValue("schERegdate"),"");
$replyCount = 0;
#---
debugString("actionString",$actionString);
debugString("pageNumber",$pageNumber);
debugString("pageSize",$pageSize);
debugString("blockSize",$blockSize);
debugArray("request values",$_REQUEST);
debugString("schTitle",$schTitle);
debugString("schContent",$schContent);
debugString("schReply",$schReply);
debugString("schSRegdate",$schSRegdate);
debugString("schERegdate",$schERegdate);
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
		insert into tb_board_article (
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
	#---
	fnDBUpdate($sql);
	#---
	$sql = "SELECT LAST_INSERT_ID()";
	$bdaSeq = nvl(fnDBGetStringValue($sql));
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
	$moveUrlParam .= "&schReply=".$schReply;
	$moveUrlParam .= "&schSRegdate=".$schSRegdate;
	$moveUrlParam .= "&schERegdate=".$schERegdate;
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
	fnHistInsertBoardArticle($bdaSeq);
	#---
	$sql = "
		update tb_board_article set
			bda_title = '${bdaTitle}'
			,bda_content = '${bdaContent}'
			,bda_fix_yn = '${bdaFixYn}'
			,moddate = NOW(3)
			,moduser = 'admin'
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
	$moveUrlParam .= "&bdaSeq=".$bdaSeq;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	$moveUrlParam .= "&schReply=".$schReply;
	$moveUrlParam .= "&schSRegdate=".$schSRegdate;
	$moveUrlParam .= "&schERegdate=".$schERegdate;
	alertGo("처리 되었습니다.","boardDtlView.php".$moveUrlParam);
}else if($actionString=="delete"){
	$bdaSeq = nvl(getPostValue("bdaSeq"));
	#---
	if($bdaSeq==""){alertBack("정보가 부족 합니다.");}#if
	#---
	fnHistInsertBoardArticle($bdaSeq);
	#---
	$sql = "
		select count(*) as cnt
		from tb_board_reply
		where bda_seq = ${bdaSeq}
	";
	$replyCount = fnDBGetIntValue($sql);
	if($replyCount > 0){alertBack("댓글이 존재 합니다.\\n댓글을 모두 삭제 해주세요.");}#if
	#---
	$sql = "
		delete from tb_board_article
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
	$moveUrlParam .= "&schReply=".$schReply;
	$moveUrlParam .= "&schSRegdate=".$schSRegdate;
	$moveUrlParam .= "&schERegdate=".$schERegdate;
	alertGo("처리 되었습니다.","boardDtl.php".$moveUrlParam);
}else{
	alertBack("잘못된 접근 입니다.");
}#if
#---
fnCloseDB();
?>