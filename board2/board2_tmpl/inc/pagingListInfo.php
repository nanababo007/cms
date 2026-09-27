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
function fnMobilePrintPagingListInfo(&$pagingInfoMap){
	global $isDisplayRegBtnOnPagingListInfo;
	#---
	?>
<div class="d-flex justify-content-between align-items-center mb-3">
	<!-- 좌측: 총 건수 -->
	<div class="text-secondary small">
		총 <span class="fw-bold text-dark"><?php echo number_format($pagingInfoMap["totalListCount"]); ?></span>건
	</div>
	<!-- 우측: 페이지 정보 및 등록 버튼 -->
	<div class="d-flex align-items-center gap-3">
		<!-- 페이지 정보 -->
		<div class="small text-muted">
			<span class="text-primary fw-medium"><?php echo number_format($pagingInfoMap["pageNumber"]); ?></span> / 
			<?php echo number_format($pagingInfoMap["totalPageNumber"]); ?> 페이지
		</div>
		<!-- 등록 버튼 -->
		<?php if(isset($isDisplayRegBtnOnPagingListInfo) and $isDisplayRegBtnOnPagingListInfo){ ?><button type="button" class="btn btn-primary btn-sm" onclick="goWrite();">등록</button><?php }#if ?>
	</div>
</div>
	<?php
}

?>