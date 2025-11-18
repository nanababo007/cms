<?php
/*
$pagingInfoMap = fnCalcPaging($pageNumber,$totalListCount,$pageSizeNumber,$blockSizeNumber);
$sqlMain = fnGetPagingQuery($sqlMain,$pagingInfoMap);
fnPrintPagingHtml($pagingInfoMap);
*/
function fnCalcPaging($pageNumber=1,$totalListCount=0,$pageSizeNumber=10,$blockSizeNumber=10){
	$returnMap = array();
	$totalPageNumber = 0;
	$totalBlockNumber = 0;
	$currentBlockNumber = 0;
	$startPageNumberOfBlock = 0;
	$startNumberOfThisPage = 0;
	#---
	$totalPageNumber = ceil($totalListCount / $pageSizeNumber);
	$totalBlockNumber = ceil($totalPageNumber / $blockSizeNumber);
	$currentBlockNumber = ceil($pageNumber / $blockSizeNumber);
	# 블럭 당 시작 페이지 번호
	$startPageNumberOfBlock = ($currentBlockNumber - 1) * $pageSizeNumber + 1;
	$startPageNumberOfBlock = max($startPageNumberOfBlock,1);
	# 블럭 당 마지막 페이지 번호
	$endPageNumberOfBlock = $currentBlockNumber * $pageSizeNumber;
	$endPageNumberOfBlock = min($endPageNumberOfBlock,$totalPageNumber);
	# 이전블록 페이지 번호
	$prevBlockPageNumber = max(max($currentBlockNumber - 1,0) * $pageSizeNumber,1);
	# 다음블록 페이지 번호
	$nextBlockPageNumber = min($currentBlockNumber * $pageSizeNumber + 1,$totalPageNumber);
	# 시작 번호 (mysql limit $startNumberOfThisPage, $pageSizeNumber)
	$startNumberOfThisPage = ($pageNumber - 1) * $pageSizeNumber;
	#---
	$returnMap["pageNumber"] = $pageNumber;
	$returnMap["totalListCount"] = $totalListCount;
	$returnMap["pageSizeNumber"] = $pageSizeNumber;
	$returnMap["blockSizeNumber"] = $blockSizeNumber;
	$returnMap["currentBlockNumber"] = $currentBlockNumber;
	$returnMap["startPageNumberOfBlock"] = $startPageNumberOfBlock;
	$returnMap["endPageNumberOfBlock"] = $endPageNumberOfBlock;
	$returnMap["totalPageNumber"] = $totalPageNumber;
	$returnMap["totalBlockNumber"] = $totalBlockNumber;
	$returnMap["startNumberOfThisPage"] = $startNumberOfThisPage;
	$returnMap["prevBlockPageNumber"] = $prevBlockPageNumber;
	$returnMap["nextBlockPageNumber"] = $nextBlockPageNumber;
	return $returnMap;
}
function fnGetPagingQuery($sqlString="",&$pagingInfoMap=null,$limitStartVarName="{{limitStartNumber}}",$limitEndVarName="{{limitEndNumber}}"){
	if($pagingInfoMap!=null && $limitStartVarName!="" && $limitEndVarName!=""){
		$sqlString = str_replace($limitStartVarName,(string)$pagingInfoMap["startNumberOfThisPage"],$sqlString);
		$sqlString = str_replace($limitEndVarName,(string)$pagingInfoMap["pageSizeNumber"],$sqlString);
	}
	return $sqlString;
}
function fnPrintPagingHtml(&$pagingInfoMap=null){
	$printPageNumber = 0;
	#---
	if($pagingInfoMap!=null){
		?>
<table class="pager" style="margin-top:10px;" align="center">
<tr>

    <?php
    /* paging : 이전 페이지 */
    if($pagingInfoMap["pageNumber"] <= 1){
    ?>
    <td><a href="javascript:goPage(1);">이전</a> | </td>
    <?php } else{ ?>
    <td><a href="javascript:goPage(<?php echo $pagingInfoMap["prevBlockPageNumber"]; ?>);">이전</a> | </td>
    <?php };?>

    <?php
    /* pager : 페이지 번호 출력 */
    for($printPageNumber = $pagingInfoMap["startPageNumberOfBlock"]; $printPageNumber <= $pagingInfoMap["endPageNumberOfBlock"]; $printPageNumber++){
    ?>
    <td><a href="javascript:goPage(<?php echo $printPageNumber; ?>);"><?php echo $printPageNumber; ?></a></td>
    <?php };?>

    <?php
    /* paging : 다음 페이지 */
    if($pagingInfoMap["pageNumber"] >= $pagingInfoMap["totalPageNumber"]){
    ?>
    <td> | <a href="javascript:goPage(<?php echo $pagingInfoMap["totalPageNumber"]; ?>);">다음</a></td>
    <?php } else{ ?>
    <td> | <a href="javascript:goPage(<?php echo $pagingInfoMap["nextBlockPageNumber"]; ?>);">다음</a></td>
    <?php };?>

</tr>
</table>
		<?php
	}
}
?>