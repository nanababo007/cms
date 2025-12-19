<?php
function fnBoardArticleCheckInfo($bdaSeq=""){
	$returnFlag = false;
	$errorReturnFlag = false;
	$articleCount = 0;
	#---
	if(nvl($bdaSeq)==""){return $errorReturnFlag;}#if
	#---
	$sql = "
		SELECT
			count(*) as cnt
		FROM {{cms.tableNamePrefix}}_article a
		WHERE bda_seq = ${bdaSeq}
	";
	debugString("fnBoardArticleCheckInfo : sql",getDecodeHtmlString($sql));
	$articleCount = fnDBGetIntValue($sql);
	#---
	$returnFlag = $articleCount > 0 ? true : false;
	return $returnFlag;
}
?>