<?php
/*
setDisplayMenuList();
*/
function getThisPageMnSeq($serverMnSeq="",$localMnSeq=""){
	global $modeString;
	#---
	if($modeString=="server"){
		return $serverMnSeq;
	}else{
		return $localMnSeq;
	}#if
}
function setDisplayMenuList(){
	global $displayMenuList;
	global $currentMenuSeq;
	global $currentMenuInfo;
	global $currentTopMenuSeq;
	global $currentTopMenuInfo;
	global $thisPageMnSeq;
	#---
	$displayMenuList = getDisplayMenuList();
	$currentMenuSeq = intval(nvl(getRequestValue("mnSeq"),nvl((string)$thisPageMnSeq,"0")));
	$currentTopMenuSeq = $currentMenuSeq!=0 ? intval(nvl(getMenuTopMnSeq((string)$currentMenuSeq),"0")) : 0;
	$mnSeq = 0;
	#---
	if($currentTopMenuSeq!=0){
		foreach($displayMenuList as $index => $menuInfo){
			$mnSeq = intval(nvl($menuInfo["mn_seq"],"0"));
			if($currentTopMenuSeq==$mnSeq){
				$currentTopMenuInfo = $menuInfo;
				break;
			}#if
		}#foreach
	}#if
	#---
	if($currentMenuSeq!=0){
		foreach($displayMenuList as $index => $menuInfo){
			$mnSeq = intval(nvl($menuInfo["mn_seq"],"0"));
			if($currentMenuSeq==$mnSeq){
				$currentMenuInfo = $menuInfo;
				break;
			}#if
		}#foreach
	}#if
}
function getDisplayMenuList(){
	$returnArray = null;
	$sql = "";
	$menuList = null;
	#---
	$sql = "
		WITH RECURSIVE menu_cte AS (
			SELECT 
				mn_seq, mn_nm, p_mn_seq, 0 AS mn_depth_no, 
				mn_nm AS mn_path, mn_ord
			FROM {{cms.tableNamePrefix}}_menu_info
			WHERE p_mn_seq = 0 /* 루트 메뉴 */
			and mn_del_yn = 'N'
			UNION ALL
			SELECT 
				m.mn_seq, m.mn_nm, m.p_mn_seq, c.mn_depth_no + 1 AS mn_depth_no,
				CONCAT(c.mn_path, ' - ', m.mn_nm) AS mn_path,
				CONCAT(c.mn_ord, '_', m.mn_ord) AS mn_ord
			FROM {{cms.tableNamePrefix}}_menu_info m
			INNER JOIN menu_cte c 
				 ON m.p_mn_seq = c.mn_seq
			where m.mn_del_yn = 'N'
		)
		SELECT
			a.*,
			b.mn_url,
			b.mn_url_target,
			b.mn_use_yn
		FROM menu_cte a
		inner join {{cms.tableNamePrefix}}_menu_info b
			on a.mn_seq = b.mn_seq
		where b.mn_del_yn = 'N'
		and b.mn_use_yn = 'Y'
		ORDER BY mn_ord
	";
	$menuList = fnDBGetList($sql);
	#---
	foreach($menuList as $index => &$menuInfo){
		$menuInfo["top_mn_seq"] = getMenuTopMnSeq($menuInfo["mn_seq"]);
	}#foreach
	#---
	$returnArray = $menuList;
	return $returnArray;
}
function getMenuUpMnSeq($mnSeq=""){
	$returnString = "";
	$upMnSeq = "";
	$sql = "";
	#---
	if($mnSeq==""){return "";}#if
	#---
	$sql = "
		SELECT
			p_mn_seq
		FROM {{cms.tableNamePrefix}}_menu_info 
		where mn_seq = '${mnSeq}'
	";
	$upMnSeq = fnDBGetValue($sql);
	#---
	$returnString = $upMnSeq;
	return $returnString;
}
function getMenuTopMnSeq($mnSeq=""){
	$returnString = "";
	$topMnSeq = "";
	$sql = "";
	#---
	if($mnSeq==""){return "";}#if
	#---
	$sql = "
		WITH RECURSIVE menu_hierarchy AS (
			SELECT mn_seq, p_mn_seq
			FROM {{cms.tableNamePrefix}}_menu_info
			WHERE mn_seq = ${mnSeq} /* 찾고 싶은 메뉴 ID */
			UNION ALL
			SELECT m.mn_seq, m.p_mn_seq
			FROM {{cms.tableNamePrefix}}_menu_info m
			INNER JOIN menu_hierarchy mh 
				ON m.mn_seq = mh.p_mn_seq
		)
		SELECT mn_seq
		FROM menu_hierarchy
		WHERE p_mn_seq = 0
	";
	$topMnSeq = fnDBGetValue($sql);
	#---
	$returnString = $topMnSeq;
	return $returnString;
}
function getMenuUrlAppendMnSeq($mnUrlString="",$mnSeq=""){
	$returnString = "";
	$editMnUrlString = "";
	#---
	$editMnUrlString = $mnUrlString;
	#---
	if($editMnUrlString!=""){
		if(strpos($editMnUrlString,"mnSeq=")===false){
			if(strpos($editMnUrlString,"?")!==false){
				$editMnUrlString = $editMnUrlString."&mnSeq=".$mnSeq;
			}else{
				$editMnUrlString = $editMnUrlString."?mnSeq=".$mnSeq;
			}#if
		}#if
	}#if
	#---
	$returnString = $editMnUrlString;
	return $returnString;
}
function getMenuPathString($mnSeq=""){
	global $displayMenuList;
	#---
	$returnString = "";
	$mnPathString = "";
	#---
	if($mnSeq!=""){
		foreach($displayMenuList as $index => $menuInfo){
			if($mnSeq==$menuInfo["mn_seq"]){
				$mnPathString = nvl($menuInfo["mn_path"]);
				break;
			}#if
		}#foreach
	}#if
	#---
	$returnString = $mnPathString;
	return $returnString;
}
function getSubMenuCount($mnSeq=""){
	$returnCount = "";
	$menuCount = 0;
	$sql = "";
	#---
	if($mnSeq==""){return "";}#if
	#---
	$sql = "
		SELECT
			count(*) as cnt
		FROM {{cms.tableNamePrefix}}_menu_info 
		where p_mn_seq = ${mnSeq}
	";
	$menuCount = fnDBGetIntValue($sql);
	#---
	$returnCount = $menuCount;
	return $returnCount;
}
?>