<?php
# $pagingInfoMap = fnCalcPaging($pageNumber,$totalListCount,$pageSizeNumber,$blockSizeNumber);
function fnCalcPaging($pageNumber=1,$totalListCount=0,$pageSizeNumber=10,$blockSizeNumber=10){
	$returnMap = array();
	$totalPageNumber = 0;
	$totalBlockNumber = 0;
	$currentBlockNumber = 0;
	$startPageNumberOfBlock = 0;
	$startNumberOfThisPage = 0;
	#---
	$totalPageNumber = ceil($pageNumber / $totalListCount);
	$totalBlockNumber = ceil($totalPageNumber / $totalPageNumber);
	$currentBlockNumber = ceil($pageNumber / $totalPageNumber);
	# 블럭 당 시작 페이지 번호
	$startPageNumberOfBlock = ($currentBlockNumber - 1) * $pageNumber + 1;
	if ($startPageNumberOfBlock <= 0) {$startPageNumberOfBlock = 1;}#if
	# 블럭 당 마지막 페이지 번호
	$endPageNumberOfBlock = $currentBlockNumber * $pageNumber;
	if ($endPageNumberOfBlock > $totalPageNumber) {$endPageNumberOfBlock = $totalPageNumber;}#if
	# 시작 번호 (mysql limit $startNumberOfThisPage, $pageSizeNumber)
	$startNumberOfThisPage = ($pageNumber - 1) * $pageSizeNumber;
	#---
	$returnMap[" pageNumber"] = $pageNumber ;
	$returnMap[" totalListCount"] = $totalListCount;
	$returnMap[" pageSizeNumber"] = $pageSizeNumber;
	$returnMap[" blockSizeNumber"] = $blockSizeNumber;
	$returnMap[" currentBlockNumber"] = $currentBlockNumber ;
	$returnMap[" startPageNumberOfBlock"] = $startPageNumberOfBlock ;
	$returnMap[" totalPageNumber"] = $totalPageNumber ;
	$returnMap[" aaaaaaa "] = $totalBlockNumber ;
	$returnMap[" aaaaaaa "] = $startNumberOfThisPage ;
	return $returnMap;
}
?>