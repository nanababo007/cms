<?php
include($_SERVER["DOCUMENT_ROOT"].'/{{cms.prefix}}/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/{{cms.prefix}}/inc/checkLogin.php');
#---
$actionString = getPostValue("actionString");
$pageNumber = intval(nvl(getPostValue("pageNumber"),"1"));
$pageSize = intval(nvl(getPostValue("pageSize"),"10"));
$blockSize = intval(nvl(getPostValue("blockSize"),"10"));
$schTitle = nvl(getPostValue("schTitle"),"");
$schContent = nvl(getPostValue("schContent"),"");
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
	$bdNm = nvl(getPostValue("bdNm"));
	$bdContent = nvl(getPostValue("bdContent"));
	#---
	if($bdNm==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		insert into {{cms.tableNamePrefix}}_info (
			bd_nm
			,bd_content
			,regdate
			,reguser
		)values(
			'${bdNm}'
			,'${bdContent}'
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
	$bdSeq = nvl(getPostValue("bdSeq"));
	$bdNm = nvl(getPostValue("bdNm"));
	$bdContent = nvl(getPostValue("bdContent"));
	#---
	if($bdSeq==""){alertBack("정보가 부족 합니다.");}#if
	if($bdNm==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		update {{cms.tableNamePrefix}}_info set
			bd_nm = '${bdNm}'
			,bd_content = '${bdContent}'
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
	$bdSeq = nvl(getPostValue("bdSeq"));
	#---
	if($bdSeq==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		delete from {{cms.tableNamePrefix}}_info
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