<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
#---
$actionString = getPostValue("actionString");
$pageNumber = intval(nvl(getPostValue("pageNumber"),"1"));
$pageSize = intval(nvl(getPostValue("pageSize"),"10"));
$blockSize = intval(nvl(getPostValue("blockSize"),"10"));
$schTitle = nvl(getPostValue("schTitle"),"");
$schContent = nvl(getPostValue("schContent"),"");
$mnSeq = nvl(getPostValue("mnSeq"));
$regMnSeq = nvl(getPostValue("regMnSeq"));
$modMnSeq = nvl(getPostValue("modMnSeq"));
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
	$mnNm = nvl(getPostValue("mnNm"));
	$mnContent = nvl(getPostValue("mnContent"));
	$mnUrl = nvl(getPostValue("mnUrl"));
	$mnUrlTarget = nvl(getPostValue("mnUrlTarget"));
	$mnUseYn = nvl(getPostValue("mnUseYn"),"N");
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
	$modMnSeq = nvl(getPostValue("modMnSeq"));
	$mnNm = nvl(getPostValue("mnNm"));
	$mnContent = nvl(getPostValue("mnContent"));
	$mnUrl = nvl(getPostValue("mnUrl"));
	$mnUrlTarget = nvl(getPostValue("mnUrlTarget"));
	$mnUseYn = nvl(getPostValue("mnUseYn"),"N");
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
	$delMnSeq = nvl(getPostValue("delMnSeq"));
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
}else if($actionString=="menuMoveUp"){
	$moveMnSeq = nvl(getPostValue("moveMnSeq"));
	$pMoveMnSeq = "";
	$moveDepthMenuList = null;
	$moveDepthMenuListCount = 0;
	$moveDepthMenuListIndex = 0;
	$moveMnInfo = null;
	$prevMnInfo = null;
	$moveMnNewOrd = "";
	$prevMnNewOrd = "";
	#---
	if($moveMnSeq==""){alertBack("정보가 부족 합니다.");}#if
	#---
	## M = 이동대상 메뉴번호.
	#   (M - $moveMnSeq)
	## A = M의 부모 메뉴번호를 구함.
	#   (A - $pMoveMnSeq)
	$sql = "
		SELECT p_mn_seq 
		FROM tb_board_menu_info
		where mn_seq = '${moveMnSeq}'
	";
	$pMoveMnSeq = fnDBGetValue($sql);
	debugString("pMoveMnSeq sql",$sql);
	debugString("pMoveMnSeq",$pMoveMnSeq);
	## B = A값이 전체 메뉴에서 부모메뉴번호와 일치하는 목록 구함.
	#   (B - $moveDepthMenuList)
	$sql = "
		select
			a.*
		from (
			SELECT
				a.mn_seq
				,a.p_mn_seq
				,a.mn_nm
				,a.mn_ord
			from tb_board_menu_info a
			where a.p_mn_seq = ${pMoveMnSeq}
		) a
		ORDER BY a.mn_ord asc
	";
	$moveDepthMenuList = fnDBGetList($sql);
	$moveDepthMenuListCount = getArrayCount($moveDepthMenuList);
	debugString("moveDepthMenuList sql",$sql);
	debugArray("moveDepthMenuList",$moveDepthMenuList);
	debugString("moveDepthMenuListCount",$moveDepthMenuListCount);
	## C,D = B 목록 루프에서, M 과 일치하는 메뉴번호의 정보(=C)를 구하고, 이전순서의 메뉴번호에 해당하는 정보(=D)를 구함.
	#   (C - $moveMnInfo, D - $prevMnInfo)
	if($moveDepthMenuListCount > 0){
		for($moveDepthMenuListIndex=0;$moveDepthMenuListIndex < $moveDepthMenuListCount;$moveDepthMenuListIndex++){
			$moveMnInfo = $moveDepthMenuList[$moveDepthMenuListIndex];
			#---
			if($moveMnSeq==nvl($moveMnInfo["mn_seq"])){break;}#if
			#---
			$prevMnInfo = $moveMnInfo;
		}#for
	}#if
	debugArray("moveMnInfo",$moveMnInfo);
	debugArray("prevMnInfo",$prevMnInfo);
	## E = C 메뉴정보의 미래 정렬순서 정보변수(=E)에, D 메뉴정보의 정렬순서값 셋팅.
	#   (E - $moveMnNewOrd)
	if($moveMnInfo!=null){
		$moveMnNewOrd = nvl($prevMnInfo["mn_ord"]);
	}#if
	## F = D 메뉴정보의 미래 정렬순서 정보변수(=F)에, C 메뉴정보의 정렬순서값 셋팅.
	#   (F - $prevMnNewOrd)
	if($prevMnInfo!=null){
		$prevMnNewOrd = nvl($moveMnInfo["mn_ord"]);
	}#if
	debugString("moveMnNewOrd",$moveMnNewOrd);
	debugString("prevMnNewOrd",$prevMnNewOrd);
	##
	if($moveMnInfo!=null && $prevMnInfo!=null){
		## C 메뉴정보의 메뉴번호에 해당하는 메뉴의 디비정렬필드에, E 값을 업데이트.
		$sql = "
			update tb_board_menu_info set
				mn_ord = '${moveMnNewOrd}'
			where mn_seq = '".nvl($moveMnInfo["mn_seq"])."'
		";
		debugString("moveMnNewOrd update sql",$sql);
		fnDBUpdate($sql);
		## D 메뉴정보의 메뉴번호에 해당하는 메뉴의 디비정렬필드에, F 값을 업데이트.
		$sql = "
			update tb_board_menu_info set
				mn_ord = '${prevMnNewOrd}'
			where mn_seq = '".nvl($prevMnInfo["mn_seq"])."'
		";
		debugString("prevMnNewOrd update sql",$sql);
		fnDBUpdate($sql);
	}#if
	# ※ 이전 메뉴정보가 없으면, 아무처리없이, 다음 실행으로 넘어감.
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=".$pageNumber;
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	$moveUrlParam .= "#mnSeqPos".$moveMnSeq;
	pageGo("menuMan.php".$moveUrlParam);
}else if($actionString=="menuMoveDown"){
	$moveMnSeq = nvl(getPostValue("moveMnSeq"));
	$pMoveMnSeq = "";
	$moveDepthMenuList = null;
	$moveDepthMenuListCount = 0;
	$moveDepthMenuListIndex = 0;
	$moveMnInfo = null;
	$nextMnInfo = null;
	$moveMnNewOrd = "";
	$nextMnNewOrd = "";
	#---
	if($moveMnSeq==""){alertBack("정보가 부족 합니다.");}#if
	#---
	## M = 이동대상 메뉴번호.
	#   (M - $moveMnSeq)
	## A = M의 부모 메뉴번호를 구함.
	#   (A - $pMoveMnSeq)
	$sql = "
		SELECT p_mn_seq 
		FROM tb_board_menu_info
		where mn_seq = '${moveMnSeq}'
	";
	$pMoveMnSeq = fnDBGetValue($sql);
	debugString("pMoveMnSeq sql",$sql);
	debugString("pMoveMnSeq",$pMoveMnSeq);
	## B = A값이 전체 메뉴에서 부모메뉴번호와 일치하는 목록 구함.
	#   (B - $moveDepthMenuList)
	$sql = "
		select
			a.*
		from (
			SELECT
				a.mn_seq
				,a.p_mn_seq
				,a.mn_nm
				,a.mn_ord
			from tb_board_menu_info a
			where a.p_mn_seq = ${pMoveMnSeq}
		) a
		ORDER BY a.mn_ord asc
	";
	$moveDepthMenuList = fnDBGetList($sql);
	$moveDepthMenuListCount = getArrayCount($moveDepthMenuList);
	debugString("moveDepthMenuList sql",$sql);
	debugArray("moveDepthMenuList",$moveDepthMenuList);
	debugString("moveDepthMenuListCount",$moveDepthMenuListCount);
	## C,D = B 목록 루프에서, M 과 일치하는 메뉴번호의 정보(=C)를 구하고, 다음순서의 메뉴번호에 해당하는 정보(=D)를 구함.
	#   (C - $moveMnInfo, D - $nextMnInfo)
	if($moveDepthMenuListCount > 0){
		for($moveDepthMenuListIndex=0;$moveDepthMenuListIndex < $moveDepthMenuListCount;$moveDepthMenuListIndex++){
			$moveMnInfo = $moveDepthMenuList[$moveDepthMenuListIndex];
			#---
			if($moveMnSeq==nvl($moveMnInfo["mn_seq"])){
				if(($moveDepthMenuListIndex + 1) < $moveDepthMenuListCount){
					$nextMnInfo = $moveDepthMenuList[$moveDepthMenuListIndex + 1];
				}#if
				break;
			}#if
		}#for
	}#if
	debugArray("moveMnInfo",$moveMnInfo);
	debugArray("nextMnInfo",$nextMnInfo);
	## E = C 메뉴정보의 미래 정렬순서 정보변수(=E)에, D 메뉴정보의 정렬순서값 셋팅.
	#   (E - $moveMnNewOrd)
	if($moveMnInfo!=null){
		$moveMnNewOrd = nvl($nextMnInfo["mn_ord"]);
	}#if
	## F = D 메뉴정보의 미래 정렬순서 정보변수(=F)에, C 메뉴정보의 정렬순서값 셋팅.
	#   (F - $nextMnNewOrd)
	if($nextMnInfo!=null){
		$nextMnNewOrd = nvl($moveMnInfo["mn_ord"]);
	}#if
	debugString("moveMnNewOrd",$moveMnNewOrd);
	debugString("nextMnNewOrd",$nextMnNewOrd);
	##
	if($moveMnInfo!=null && $nextMnInfo!=null){
		## C 메뉴정보의 메뉴번호에 해당하는 메뉴의 디비정렬필드에, E 값을 업데이트.
		$sql = "
			update tb_board_menu_info set
				mn_ord = '${moveMnNewOrd}'
			where mn_seq = '".nvl($moveMnInfo["mn_seq"])."'
		";
		debugString("moveMnNewOrd update sql",$sql);
		fnDBUpdate($sql);
		## D 메뉴정보의 메뉴번호에 해당하는 메뉴의 디비정렬필드에, F 값을 업데이트.
		$sql = "
			update tb_board_menu_info set
				mn_ord = '${nextMnNewOrd}'
			where mn_seq = '".nvl($nextMnInfo["mn_seq"])."'
		";
		debugString("nextMnNewOrd update sql",$sql);
		fnDBUpdate($sql);
	}#if
	# ※ 다음 메뉴정보가 없으면, 아무처리없이, 다음 실행으로 넘어감.
	#---
	$moveUrlParam = "";
	$moveUrlParam .= "?pageNumber=".$pageNumber;
	$moveUrlParam .= "&pageSize=".$pageSize;
	$moveUrlParam .= "&blockSize=".$blockSize;
	$moveUrlParam .= "&schTitle=".$schTitle;
	$moveUrlParam .= "&schContent=".$schContent;
	$moveUrlParam .= "&mnSeq=".$mnSeq;
	$moveUrlParam .= "#mnSeqPos".$moveMnSeq;
	pageGo("menuMan.php".$moveUrlParam);
}else{
	alertBack("잘못된 접근 입니다.");
}#if
#---
fnCloseDB();
?>