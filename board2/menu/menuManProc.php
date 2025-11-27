<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
#---
$actionString = getRequestValue("actionString");
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
$mnSeq = nvl(getRequestValue("mnSeq"));
$regMnSeq = nvl(getRequestValue("regMnSeq"));
$modMnSeq = nvl(getRequestValue("modMnSeq"));
$pMnSeq = "";
$mnOrd = "";
#---
debugString("actionString",$actionString);
debugString("pageNumber",$pageNumber);
debugString("pageSize",$pageSize);
debugString("blockSize",$blockSize);
debugString("schTitle",$schTitle);
debugString("schContent",$schContent);
debugString("partition","====================");
debugString("regMnSeq",$regMnSeq);
debugString("modMnSeq",$modMnSeq);
#---
fnOpenDB();
#---
if($actionString=="write"){
	$mnNm = nvl(getRequestValue("mnNm"));
	$mnContent = nvl(getRequestValue("mnContent"));
	$mnUrl = nvl(getRequestValue("mnUrl"));
	$mnUrlTarget = nvl(getRequestValue("mnUrlTarget"));
	$mnUseYn = nvl(getRequestValue("mnUseYn"),"N");
	#---
	if($regMnSeq==""){
		$pMnSeq = "0";
		#---
		$sql = "
			SELECT max(mn_ord) 
			FROM tb_board_menu_info
			where p_mn_seq = ${pMnSeq}
		";
		$mnOrd = (string)(fnDBGetIntValue($sql) + 1);
		$mnOrd = str_pad($mnOrd, 10, "0", STR_PAD_LEFT);
	} else {
		$pMnSeq = $regMnSeq;
		#---
		$sql = "
			SELECT max(mn_ord) 
			FROM tb_board_menu_info
			where p_mn_seq = ${pMnSeq}
		";
		$mnOrd = (string)(fnDBGetIntValue($sql) + 1);
		if($pMnSeq=="0"){
			$mnOrd = str_pad($mnOrd, 10, "0", STR_PAD_LEFT);
		}else{
			$mnOrd = str_pad($mnOrd, 6, "0", STR_PAD_LEFT);
		}#if
	}#if
	#---
	debugString("pMnSeq",$pMnSeq);
	debugString("mnOrd",$mnOrd);
	debugString("regMnSeq",$regMnSeq);
	debugString("mnNm",$mnNm);
	debugString("mnContent",$mnContent);
	debugString("mnUrl",$mnUrl);
	debugString("mnUrlTarget",$mnUrlTarget);
	debugString("mnUseYn",$mnUseYn);
	#---
	if($mnNm==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		insert into tb_board_menu_info (
			p_mn_seq
			,mn_nm
			,mn_content
			,mn_ord
			,mn_url
			,mn_url_target
			,mn_use_yn
			,mn_del_yn
			,regdate
			,reguser
		)values(
			'${pMnSeq}' /* p_mn_seq */
			,'${mnNm}' /* mn_nm */
			,'${mnContent}' /* mn_content */
			,'${mnOrd}' /* mn_ord */
			,'${mnUrl}' /* mn_url */
			,'${mnUrlTarget}' /* mn_url_target */
			,'${mnUseYn}' /* mn_use_yn */
			,'N' /* mn_del_yn */
			,NOW(3) /* regdate */
			,'admin' /* reguser */
		)
	";
	fnDBUpdate($sql);
	#---
	$sql = "SELECT LAST_INSERT_ID()";
	$newMnSeq = nvl(fnDBGetStringValue($sql));
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=1";
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&newMnSeq=".$newMnSeq;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	alertGo("처리 되었습니다.","menuMan.php".$moveUrlParam);
}else if($actionString=="modify"){
	$modMnSeq = nvl(getRequestValue("modMnSeq"));
	$mnNm = nvl(getRequestValue("mnNm"));
	$mnContent = nvl(getRequestValue("mnContent"));
	$mnUrl = nvl(getRequestValue("mnUrl"));
	$mnUrlTarget = nvl(getRequestValue("mnUrlTarget"));
	$mnUseYn = nvl(getRequestValue("mnUseYn"),"N");
	#---
	if($modMnSeq==""){alertBack("정보가 부족 합니다.");}#if
	if($mnNm==""){alertBack("정보가 부족 합니다.");}#if
	#---
	$sql = "
		update tb_board_menu_info set
			mn_nm = '${mnNm}'
			,mn_content = '${mnContent}'
			,mn_url = '${mnUrl}'
			,mn_url_target = '${mnUrlTarget}'
			,mn_use_yn = '${mnUseYn}'
			,moddate = NOW(3)
			,moduser = 'admin'
		where mn_seq = ${modMnSeq}
	";
	fnDBUpdate($sql);
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=".$pageNumber;
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	$moveUrlParam .= "&editedMnSeq=".$modMnSeq;
	alertGo("처리 되었습니다.","menuMan.php".$moveUrlParam);
}else if($actionString=="delete"){
	$delMnSeq = nvl(getRequestValue("delMnSeq"));
	#---
	if($delMnSeq==""){alertBack("정보가 부족 합니다.");}#if
	if(getSubMenuCount($delMnSeq) > 0){alertBack("하위 메뉴가 존재 합니다.\\n하위 메뉴를 삭제해야, 상위메뉴가 삭제 가능합니다.");}#if
	#---
	$sql = "
		delete from tb_board_menu_info
		where mn_seq = ${delMnSeq}
	";
	fnDBUpdate($sql);
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=".$pageNumber;
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	alertGo("처리 되었습니다.","menuMan.php".$moveUrlParam);
}else{
	alertBack("잘못된 접근 입니다.");
}#if
#---
fnCloseDB();
?>