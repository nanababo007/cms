<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdImg/boardMasLibraryInclude.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdImg/boardDtlLibraryInclude.php');
include("boardDtlViewServer.php");
?>
<!doctype html>
<html lang="ko">
<head>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/headM.php'); ?>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pageViewMStyle.php'); ?>
	<script src="boardDtlReplyTemplateM.js"></script>
	<script src="boardDtlReplyFunctionM.js"></script>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutStartM.php'); ?>

<!-- 상단 네비게이션 -->
<nav class="navbar navbar-light bg-white border-bottom sticky-top">
	<div class="container-fluid justify-content-start">
		<a href="javascript:goList();" class="btn btn-sm me-2 text-dark" style="font-size: 1.2rem;">←</a>
		<span class="navbar-brand mb-0 h1">게시글 관리</span>
	</div>
</nav>

<div class="view-container">
	<!-- 게시글 헤더 -->
	<div class="post-header">
		<h1 class="post-title"><?php echo getArrayValue($boardArticleInfo,"bda_title"); ?></h1>
		<div class="post-meta d-flex justify-content-between">
			<div>
				<strong><?php echo getArrayValue($boardArticleInfo,"reguser"); ?></strong> <span class="mx-1">|</span> <span><?php echo getArrayValue($boardArticleInfo,"regdate_str"); ?></span>
			</div>
			<div>조회 <?php echo getArrayValue($boardArticleInfo,"bda_view_cnt"); ?></div>
		</div>
	</div>
	
	<!-- 본문 내용 -->
	<div class="post-content">
		<div>
			<p>
				페이지끝 이동:
				<a href="javascript:goPageEndPos();">이동</a>
			</p>
			<p>
				댓글 이동:
				<a href="javascript:goReplyPos();">이동</a>
			</p>
			<p>
				변경이력 팝업:
				<a href="javascript:toggleBoardContentHistoryList();">팝업</a>
			</p>
		</div>
		<div class="board-content-history-list-area" style="display:none;">
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
		<div class="post-content-area board-content-area">
			<hr />
			최초 등록일시 : <?php echo getDecodeHtmlString(getArrayValue($boardArticleInfo,"regdate_str")); ?>
			<br />최종 변경일시 : <?php echo getDecodeHtmlString(getArrayValue($boardArticleInfo,"moddate_str")); ?>
			
			<?php if($boardDtlFileListCount > 0){ ?>
			<div align="left" style="margin-top:10px;">
				<?php
					for($boardDtlFileListIndex=0;$boardDtlFileListIndex<$boardDtlFileListCount;$boardDtlFileListIndex++){
						$boardDtlFileListNumber = $boardDtlFileListIndex+1;
						$boardDtlFileInfo = $boardDtlFileList[$boardDtlFileListIndex];
						$boardDtlFileType = FileUtilLibraryClass::getFileTypeString($boardDtlFileInfo["bdaf_save_thumbnail"]);
						if($boardDtlFileType=="img"){
							?>
								<br /><a href="<?php echo $boardDtlFileInfo["bdaf_save_filename"]; ?>" target="_blank"><img src="<?php echo $boardDtlFileInfo["bdaf_save_thumbnail"]; ?>" class="img-fluid rounded mb-1 mt-3" alt="<?php echo $boardDtlFileInfo["bdaf_filename"]; ?> 이미지" /></a>
								<br /><a href="<?php echo $boardDtlFileInfo["bdaf_save_filename"]; ?>" target="_blank">(<?php echo $boardDtlFileInfo["bdaf_filename"]; ?>)</a>
							<?php
						}else{
							?><br /><a href="<?php echo $boardDtlFileInfo["bdaf_save_filename"]; ?>" target="_blank"><?php echo $boardDtlFileInfo["bdaf_filename"]; ?></a><?php
						}#if
					}#for
				?>
			</div>
			<?php }#if ?>
			
			<br /><?php echo getDecodeHtmlString(getArrayValue($boardArticleInfo,"bda_content")); ?>
			<br /><br />
		</div>
		<!--img src="https://placeholder.com" class="img-fluid rounded mb-3" alt="샘플 이미지" /-->
	</div>
	
	<!-- 댓글 영역 (심플) -->
	<a name="replyPos"></a>
	<div class="comment-section">
		<h6 class="fw-bold mb-3">댓글 <span id="replyCntArea">0</span></h6>
		<div class="mb-3 border-bottom pb-3">
			<textarea class="form-control" id="replyContent" rows="4" placeholder="댓글내용을 입력해주세요"></textarea>
			<div class="mt-2">
				<button class="btn btn-light border flex-shrink-0" onclick="writeReply();">댓글등록</button>
				<button class="btn btn-light border flex-shrink-0" onclick="cancelReply();">댓글취소</button>
			</div>
		</div>
		<div class="reply-item-area-class">
			<!--
			<div class="mb-3 border-bottom pb-2">
				<div class="small fw-bold">코딩고수</div>
				<div class="small text-muted mb-1">10분 전</div>
				<div class="small">오, 디자인이 아주 깔끔하네요! 참고하겠습니다.</div>
			</div>
			<div class="mb-3 border-bottom pb-2">
				<div class="small fw-bold">비기너</div>
				<div class="small text-muted mb-1">5분 전</div>
				<div class="small">혹시 글쓰기 페이지 코드도 공유해주실 수 있나요?</div>
			</div>
			-->
		</div>
	</div>
	
	<!-- 하단 여백 (고정 하단바 공간) -->
	<div style="height: 10px;"></div>
</div>

<!-- 하단 고정 액션바 -->
<div class="bottom-nav">
	<a href="javascript:goList();" class="btn btn-outline-secondary flex-grow-1">목록</a>
	<button class="btn btn-light border flex-shrink-0" onclick="goModify();">수정</button>
	<button class="btn btn-danger flex-shrink-0" onclick="goDelete();" style="color:ffaaaa;">삭제</button>
</div>

<script src="/board2/cmn/bootstrap5/js/bootstrap.bundle.min.js"></script>

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
<input type="hidden" name="siteModeString" value="M" />
<?php include("boardDtlViewFormItem.php"); ?>
</form>

<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutEndM.php'); ?>

<?php include("boardDtlViewMScript.php"); ?>

</body>
</html>