<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
#---
$actionString = getRequestValue("actionString");
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
#---
debugString("actionString",$actionString);
debugString("pageNumber",$pageNumber);
debugString("pageSize",$pageSize);
debugString("blockSize",$blockSize);
debugString("schTitle",$schTitle);
debugString("schContent",$schContent);
#---
fnOpenDB();
#---
if($actionString=="write"){
	$bdNm = nvl(getRequestValue("bdNm"));
	$bdContent = nvl(getRequestValue("bdContent"));
	$bdFixYn = trim(nvl(getRequestValue("bdFixYn"),"N"));
	#---
	if($bdNm==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		insert into tb_board_info (
			bd_nm
			,bd_content
			,bd_fix_yn
			,regdate
			,reguser
		)values(
			'${bdNm}'
			,'${bdContent}'
			,'${bdFixYn}'
			,NOW(3)
			,'admin'
		)
	";
	#---
	fnDBUpdate($sql);
	#---
	$sql = "SELECT LAST_INSERT_ID()";
	$bdSeq = nvl(fnDBGetStringValue($sql));
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=1";
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&bdSeq=".$bdSeq;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	alertGo("처리 되었습니다.","board.php".$moveUrlParam);
}else if($actionString=="modify"){
	$bdSeq = nvl(getRequestValue("bdSeq"));
	$bdNm = nvl(getRequestValue("bdNm"));
	$bdContent = nvl(getRequestValue("bdContent"));
	$bdFixYn = trim(nvl(getRequestValue("bdFixYn"),"N"));
	#---
	if($bdSeq==""){alertBack("정보가 부족 합니다.");}#if
	if($bdNm==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		update tb_board_info set
			bd_nm = '${bdNm}'
			,bd_content = '${bdContent}'
			,bd_fix_yn = '${bdFixYn}'
			,moddate = NOW(3)
			,moduser = 'admin'
		where bd_seq = ${bdSeq}
	";
	#---
	fnDBUpdate($sql);
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=".$pageNumber;
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	alertGo("처리 되었습니다.","board.php".$moveUrlParam);
}else if($actionString=="delete"){
	$bdSeq = nvl(getRequestValue("bdSeq"));
	#---
	if($bdSeq==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		delete from tb_board_info
		where bd_seq = ${bdSeq}
	";
	#---
	fnDBUpdate($sql);
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=".$pageNumber;
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	alertGo("처리 되었습니다.","board.php".$moveUrlParam);
}else{
	alertBack("잘못된 접근 입니다.");
}#if
#---
fnCloseDB();
?>