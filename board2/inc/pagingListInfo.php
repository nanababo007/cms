<?php
/*
$isDisplayRegBtnOnPagingListInfo : true/false, 페이지별 등록버튼 표시 여부
*/
function fnPrintPagingListInfo(&$pagingInfoMap){
	global $isDisplayRegBtnOnPagingListInfo;
	#---
	?>
<table width="100%">
<colgroup>
	<col width="50%" />
	<col width="50%" />
</colgroup>
<tr>
	<td align="left">총 <?php echo number_format($pagingInfoMap["totalListCount"]); ?>건</td>
	<td align="right">
		<?php echo number_format($pagingInfoMap["pageNumber"]); ?> page / 
		<?php echo number_format($pagingInfoMap["totalPageNumber"]); ?> page
		<?php if(isset($isDisplayRegBtnOnPagingListInfo) and $isDisplayRegBtnOnPagingListInfo){ ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="등록" onclick="goWrite();" /><?php }#if ?>
	</td>
</tr>
</table>
	<?php
}
?>