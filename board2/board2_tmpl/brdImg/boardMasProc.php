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
$mnSeq = nvl(getPostValue("mnSeq"),"");
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
	$bdFixYn = trim(nvl(getPostValue("bdFixYn"),"N"));
	#---
	if($bdNm==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		insert into {{cms.tableNamePrefix}}_img_info (
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
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	alertGo("처리 되었습니다.","boardMas.php".$moveUrlParam);
}else if($actionString=="modify"){
	$bdSeq = nvl(getPostValue("bdSeq"));
	$bdNm = nvl(getPostValue("bdNm"));
	$bdContent = nvl(getPostValue("bdContent"));
	$bdFixYn = trim(nvl(getPostValue("bdFixYn"),"N"));
	#---
	if($bdSeq==""){alertBack("정보가 부족 합니다.");}#if
	if($bdNm==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		update {{cms.tableNamePrefix}}_img_info set
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
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	alertGo("처리 되었습니다.","boardMas.php".$moveUrlParam);
}else if($actionString=="delete"){
	$bdSeq = nvl(getPostValue("bdSeq"));
	#---
	if($bdSeq==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		delete from {{cms.tableNamePrefix}}_img_info
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
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	alertGo("처리 되었습니다.","boardMas.php".$moveUrlParam);
}else{
	alertBack("잘못된 접근 입니다.");
}#if
#---
fnCloseDB();
?>