<?php
function fnPrintPagingListInfo(&$pagingInfoMap){
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
	</td>
</tr>
</table>
	<?php
}
?>