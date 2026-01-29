<?php
$thisPageMnSeq = getThisPageMnSeq("23","17");
$pageTitleString = "";
$boardArticleTableName = "";
$boardArticleInfo = null;
$boardInfo = null;
$mnSeq = trim(nvl(getRequestValue("mnSeq")));
$bdSeq = trim(nvl(getRequestValue("bdSeq")));
$bdaSeq = trim(nvl(getRequestValue("bdaSeq")));
$histBdaSeq = trim(nvl(getRequestValue("histBdaSeq")));
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
$schReply = nvl(getRequestValue("schReply"),"");
$schSRegdate = nvl(getRequestValue("schSRegdate"),"");
$schERegdate = nvl(getRequestValue("schERegdate"),"");
$histDateSelectSqlString = "";
$boardArticleHistoryList = null;
$boardArticleHistoryListCount = 0;
$sqlBoardArticleHistoryList = "";
#---
if($histBdaSeq!=""){
	$boardArticleTableName = "tb_board_bak_article";
	$histDateSelectSqlString = " ,STR_TO_DATE(a.bakdate, '%Y-%m-%d') as hist_date_str ";
}else{
	$boardArticleTableName = "tb_board_article";
	$histDateSelectSqlString = " ,'' as hist_date_str ";
}#if
#---
fnOpenDB();
setDisplayMenuList();
#---
if($bdSeq==""){alertBack("정보가 부족합니다.");}#if
if($bdaSeq==""){alertBack("정보가 부족합니다.");}#if
#---
$boardInfo = fnBoardGetInfo($bdSeq);
if($boardInfo==null){alertBack("게시판 정보가 존재하지 않습니다.");}#if
debugArray("boardInfo",$boardInfo);
if(!fnBoardArticleCheckInfo($bdaSeq)){alertBack("게시글 정보가 존재하지 않습니다.");}#if
#---
$pageTitleString = getArrayValue($boardInfo,"bd_nm")." | 멀티게시판";
#---
if($histBdaSeq==""){
	$sql = "
		update ${boardArticleTableName} set
			bda_view_cnt = bda_view_cnt + 1
		where bda_seq = ${bdaSeq}
	";
	fnDBUpdate($sql);
}#if
#---
if($histBdaSeq!=""){
	$sqlBodyPart = "
		FROM ${boardArticleTableName} a
		where a.bda_bseq = ${histBdaSeq}
	";
}else{
	$sqlBodyPart = "
		FROM ${boardArticleTableName} a
		where a.bda_seq = ${bdaSeq}
	";
}#if
#---
$sqlMain = "
	SELECT
		a.bda_seq
		,a.bd_seq
		,a.bda_title
		,a.bda_content
		,a.bda_view_cnt
		,STR_TO_DATE(a.regdate, '%Y-%m-%d %H:%i:%s') as regdate_str
		,STR_TO_DATE(a.moddate, '%Y-%m-%d %H:%i:%s') as moddate_str
		${histDateSelectSqlString}
		,a.regdate
		,a.reguser
		,a.moddate
		,a.moduser
	${sqlBodyPart}
";
debugString("sqlMain",getDecodeHtmlString($sqlMain));
$boardArticleInfo = fnDBGetRow($sqlMain);
#--- history list
if($bdaSeq!=""){
	$sqlBoardArticleHistoryList = "
		select
			a.*
		from (
			SELECT
				a.bda_bseq
				,a.bda_seq
				,a.bd_seq
				,a.bda_title
				,STR_TO_DATE(a.regdate, '%Y-%m-%d %H:%i:%s') as regdate_str
				,STR_TO_DATE(a.moddate, '%Y-%m-%d %H:%i:%s') as moddate_str
				,STR_TO_DATE(a.bakdate, '%Y-%m-%d %H:%i:%s') as hist_date_str
				,a.regdate
				,a.reguser
				,a.moddate
				,a.moduser
			from tb_board_bak_article a
			where a.bda_seq = ${bdaSeq}
		) a
		order by hist_date_str desc
	";
	$boardArticleHistoryList = fnDBGetList($sqlBoardArticleHistoryList);
	$boardArticleHistoryListCount = getArrayCount($boardArticleHistoryList);
}#if
#---
fnCloseDB();
?>