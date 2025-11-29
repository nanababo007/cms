<?php
function fnBoardGetInfo($bdSeq=""){
	$returnMap = null;
	$errorReturnMap = null;
	$boardInfo = null;
	#---
	if(nvl($bdSeq)==""){return $errorReturnMap;}#if
	#---
	$sql = "
		SELECT
			a.bd_seq
			,a.bd_nm
			,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
			,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
			,a.regdate
			,a.reguser
			,a.moddate
			,a.moduser
		FROM tb_board_img_info a
		WHERE bd_seq = ${bdSeq}
	";
	debugString("fnBoardGetInfo : sql",getDecodeHtmlString($sql));
	$boardInfo = fnDBGetRow($sql);
	#---
	$returnMap = $boardInfo;
	return $returnMap;
}
?>