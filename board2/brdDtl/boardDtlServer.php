<?php
$isDisplayRegBtnOnPagingListInfo = false;
$thisPageMnSeq = getThisPageMnSeq("23","17");
$pageTitleString = "";
$boardInfo = null;
$boardListTotalCount = 0;
$pagingInfoMap = null;
$boardList = null;
$boardListCount = null;
$sqlSearchPart = "";
$sqlSearchPartIndex = 0;
$mnSeq = nvl(getRequestValue("mnSeq"),"");
$bdSeq = nvl(getRequestValue("bdSeq"),"");
$bdaSeq = nvl(getRequestValue("bdaSeq"),"");
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
$schReply = nvl(getRequestValue("schReply"),"");
$schSRegdate = nvl(getRequestValue("schSRegdate"),"");
$schERegdate = nvl(getRequestValue("schERegdate"),"");
$boardDtlFixList = null;
$boardDtlFixListCount = 0;
#---
debugString("bdSeq",$bdSeq);
#---
fnOpenDB();
setDisplayMenuList();
#---
$isDisplayRegBtnOnPagingListInfo = true;
$boardInfo = fnBoardGetInfo($bdSeq);
if($boardInfo==null){alertBack("게시판 정보가 존재하지 않습니다.");}#if
debugArray("boardInfo",$boardInfo);
#---
$pageTitleString = getArrayValue($boardInfo,"bd_nm")." | 멀티게시판";
#---
$sqlSearchPart .= "where a.bd_seq = ${bdSeq} ";
$sqlSearchPartIndex = 1;
if($schTitle!=""){
	$sqlSearchPart .= fnGetSqlWhereAndString($sqlSearchPartIndex);
	$sqlSearchPart .= " a.bda_title like '%${schTitle}%' ";
	$sqlSearchPartIndex++;
}#if
if($schContent!=""){
	$sqlSearchPart .= fnGetSqlWhereAndString($sqlSearchPartIndex);
	$sqlSearchPart .= " a.bda_content like '%${schContent}%' ";
	$sqlSearchPartIndex++;
}#if
if($schReply!=""){
	$sqlSearchPart .= fnGetSqlWhereAndString($sqlSearchPartIndex);
	$sqlSearchPart .= " exists (
		select bdr_seq 
		from tb_board_reply 
		where bda_seq = a.bda_seq
		and bdr_content like '%${schReply}%')
	";
	$sqlSearchPartIndex++;
}#if
if($schSRegdate!="" and $schERegdate!=""){
	$sqlSearchPart .= fnGetSqlWhereAndString($sqlSearchPartIndex);
	$sqlSearchPart .= " a.regdate between STR_TO_DATE('${schSRegdate} 00:00:00','%Y-%m-%d %H:%i:%s') and STR_TO_DATE('${schERegdate} 23:59:59','%Y-%m-%d %H:%i:%s') ";
	$sqlSearchPartIndex++;
}#if
#---
$sqlBodyPart = "
	FROM tb_board_article a
";
#---
$sqlFix = "
	select
		a.*
	from (
		SELECT
			a.bda_seq
			,a.bd_seq
			,a.bda_title
			,a.bda_view_cnt
			,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
			,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
			,a.regdate
			,a.reguser
			,a.moddate
			,a.moduser
		${sqlBodyPart}
		where a.bd_seq = ${bdSeq}
		and bda_fix_yn = 'Y'
	) a
	ORDER BY a.bda_seq DESC
";
$boardDtlFixList = fnDBGetList($sqlFix);
$boardDtlFixListCount = getArrayCount($boardDtlFixList);
#---
$sqlCount = "
	SELECT count(*)
	${sqlBodyPart}
	${sqlSearchPart}
";
$boardListTotalCount = fnDBGetIntValue($sqlCount);
#$boardListTotalCount = 328; #test
#---
$pagingInfoMap = fnCalcPaging($pageNumber,$boardListTotalCount,$pageSize,$blockSize);
debugArray("pagingInfoMap",$pagingInfoMap);
#---
$sqlMain = "
	select
		a.*
	from (
		SELECT
			a.bda_seq
			,a.bd_seq
			,a.bda_title
			,a.bda_view_cnt
			,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
			,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
			,a.regdate
			,a.reguser
			,a.moddate
			,a.moduser
		${sqlBodyPart}
		${sqlSearchPart}
	) a
	order by a.bda_seq desc
	LIMIT {{limitStartNumber}}, {{limitEndNumber}}
";
$sqlMain = fnGetPagingQuery($sqlMain,$pagingInfoMap);
debugString("sqlMain",str_replace("\n","<br />",$sqlMain));
$boardList = fnDBGetList($sqlMain);
$boardListCount = getArrayCount($boardList);
#---
fnCloseDB();
?>