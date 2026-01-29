<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdMas/boardLibraryInclude.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdDtl/boardDtlLibraryInclude.php');
include("boardDtlViewServer.php");
?>
<!doctype html>
<html lang="ko">
<head>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/head.php'); ?>
	<script src="boardDtlReplyTemplate.js"></script>
	<script src="boardDtlReplyFunction.js"></script>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/top.php'); ?>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutStart.php'); ?>

<h2>게시글 관리 (<?php echo getArrayValue($boardInfo,"bd_nm"); ?>) <span class="menu-navi-class"><?php echo getMenuPathString($thisPageMnSeq); ?></span></h2>

<table class="board-write-table-class">
<colgroup>
	<col width="20%" />
	<col width="30%" />
	<col width="20%" />
	<col width="30%" />
</colgroup>
<tr>
	<th>게시글 제목</th>
	<td colspan="3"><?php echo getArrayValue($boardArticleInfo,"bda_title"); ?> (조회수 : <?php echo getArrayValue($boardArticleInfo,"bda_view_cnt"); ?>)</td>
</tr>
<tr>
	<td colspan="4">
		<div align="right" style="margin-top:10px;">
			<input type="button" value="페이지끝" onclick="goPageEndPos();" />
			<input type="button" value="댓글" onclick="goReplyPos();" />
			<input type="button" value="변경이력" onclick="toggleBoardContentHistoryList();" />
			<input type="button" value="수정" onclick="goModify();" />
			<input type="button" value="삭제" onclick="goDelete();" style="color:red;" />
			<input type="button" value="목록" onclick="goList();" />
		</div>
		<div class="board-content-history-list-area">
			<hr />
			<h3 class="board-content-history-list-title">변경 이력 목록 (<?php echo $boardArticleHistoryListCount; ?>)</h3>
			<?php
				printBoardArticleHistoryList();
				function printBoardArticleHistoryList(){
					global $boardArticleHistoryList;
					global $boardArticleHistoryListCount;
					#---
					$boardArticleHistoryListNumber = 0;
					$historyDateString = "";
					$historyBdaSeq = "";
					$historyBdaTitle = "";
					#---
					if($boardArticleHistoryListCount > 0){
						foreach($boardArticleHistoryList as $boardArticleHistoryListIndex => $boardArticleHistoryInfo){
							$boardArticleHistoryListNumber = $boardArticleHistoryListIndex + 1;
							#---
							$historyDateString = nvl(getArrayValue($boardArticleHistoryInfo,"hist_date_str"));
							$historyBdaSeq = nvl(getArrayValue($boardArticleHistoryInfo,"bda_bseq"));
							$historyBdaTitle = nvl(getArrayValue($boardArticleHistoryInfo,"bda_title"));
							#---
							if($boardArticleHistoryListNumber==1){
								?><a href="javascript:goBoardContentHistoryView('<?php echo $historyBdaSeq; ?>');"><?php echo $boardArticleHistoryListNumber; ?>. <?php echo $historyDateString; ?> <?php echo $historyBdaTitle; ?> (수정)</a><?php
							}else{
								?><br /><a href="javascript:goBoardContentHistoryView('<?php echo $historyBdaSeq; ?>');"><?php echo $boardArticleHistoryListNumber; ?>. <?php echo $historyDateString; ?> <?php echo $historyBdaTitle; ?> (수정)</a><?php
							}#if
						}#foreach
					}#if
				}
			?>
		</div>
		<div class="board-content-area">
			<hr />
			최초 등록일시 : <?php echo getDecodeHtmlString(getArrayValue($boardArticleInfo,"regdate_str")); ?>
			<br />최종 변경일시 : <?php echo getDecodeHtmlString(getArrayValue($boardArticleInfo,"moddate_str")); ?>
			<hr />
			<br /><?php echo getDecodeHtmlString(getArrayValue($boardArticleInfo,"bda_content")); ?>
		</div>
	</td>
</tr>
</table>

<div align="right" style="margin-top:10px;">
	<input type="button" value="수정" onclick="goModify();" />
	<input type="button" value="삭제" onclick="goDelete();" style="color:red;" />
	<input type="button" value="목록" onclick="goList();" />
</div>

<a name="replyPos"></a>
<div class="reply-area-class">
	<textarea style="width:99.4%;height:100px;margin-top:10px;" placeholder="댓글내용" id="replyContent"></textarea>
	<button onclick="javascript:writeReply();">댓글등록</button>
	<button onclick="javascript:cancelReply();">댓글취소</button>
	<div class="reply-item-area-class"></div>
</div>

<a name="pageEndPos"></a>

<form name="paramForm" method="get">
<?php include("boardDtlViewFormItem.php"); ?>
</form>

<form name="historyViewParamForm" method="get" target="_blank">
<input type="hidden" name="histBdaSeq" value="" />
<?php include("boardDtlViewFormItem.php"); ?>
</form>

<form name="actionParamForm" method="post">
<input type="hidden" name="actionString" value="" />
<?php include("boardDtlViewFormItem.php"); ?>
</form>

<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutEnd.php'); ?>

<?php include("boardDtlViewScript.php"); ?>

</body>
</html>