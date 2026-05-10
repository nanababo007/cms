<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdImg/boardMasLibraryInclude.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pagingListInfo.php');
include('boardDtlServer.php');
?>
<!doctype html>
<html lang="ko">
<head>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/headM.php'); ?>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pageListMStyle.php'); ?>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutStartM.php'); ?>

<div class="board-container">
	<!-- 검색창 -->
	<div class="input-group mb-4">
		<input type="text" id="schTitle" value="<?php echo $schTitle; ?>" class="form-control" placeholder="검색어를 입력하세요">
		<input type="hidden" id="schContent" value="<?php echo $schContent; ?>" />
		<input type="hidden" id="schReply" value="<?php echo $schReply; ?>" />
		<input type="hidden" id="schSRegdate" value="<?php echo $schSRegdate; ?>" />
		<input type="hidden" id="schERegdate" value="<?php echo $schERegdate; ?>" />
		<button class="btn btn-outline-secondary" type="button" onclick="javascript:goSearch();">검색</button>
		<button class="btn btn-outline-secondary" type="button" onclick="javascript:resetSearch();" style="color:red;">초기화</button>
	</div>
	
	<!-- 목록 상단 정보 영역 -->
	<?php fnMobilePrintPagingListInfo($pagingInfoMap); ?>
	
	<!-- 게시글 목록 (반응형 리스트) -->
	<div class="list-group">
		<!-- 개별 게시글 유닛 -->
		<?php
		if($boardDtlFixListCount > 0){
			foreach ($boardDtlFixList as $index => $row) {
		?>
		<div class="post-item">
			<a href="javascript:goView('<?php echo $row["bda_seq"]; ?>');" class="post-title">[고정] <?php echo $row["bda_title"]; ?> (<?php echo intval($row["reply_cnt"]); ?>)</a>
			<div class="post-info">
				<span>작성자: <?php echo $row["reguser"]; ?></span> | <span><?php echo $row["regdate_str"]; ?></span> | <span>조회 <?php echo $row["bda_view_cnt"]; ?></span>
			</div>
		</div>
		<?php
			}#foreach
		}#if
		?>
		<?php
		if($boardListTotalCount > 0){
			foreach ($boardList as $index => $row) {
		?>
		<div class="post-item">
			<a href="javascript:goView('<?php echo $row["bda_seq"]; ?>');" class="post-title"><?php echo $pagingInfoMap["startRowNumberForPage"] - $index; ?>. <?php echo $row["bda_title"]; ?> (<?php echo intval($row["reply_cnt"]); ?>)</a>
			<div class="post-info">
				<span>작성자: <?php echo $row["reguser"]; ?></span> | <span><?php echo $row["regdate_str"]; ?></span> | <span>조회 <?php echo $row["bda_view_cnt"]; ?></span>
			</div>
		</div>
		<?php
			}#foreach
		}#if
		?>
		<?php if($boardListTotalCount == 0){ ?>
		<div class="post-item">
			등록된 게시글이 없습니다.
		</div>
		<?php }//if ?>
	</div>
	
	<?php fnMobilePrintPagingHtml($pagingInfoMap); ?>
</div>

<?php if(isset($isDisplayRegBtnOnPagingListInfo) and $isDisplayRegBtnOnPagingListInfo){ ?>
<button type="button" class="btn btn-primary write-btn" onclick="goWrite();">글쓰기 📝</button>
<?php } #if ?>

<script src="/board2/cmn/bootstrap5/js/bootstrap.bundle.min.js"></script>

<form name="paramForm" method="get">
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
<input type="hidden" name="bdaSeq" value="<?php echo $bdaSeq; ?>" />
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
<input type="hidden" name="schReply" value="<?php echo $schReply; ?>" />
<input type="hidden" name="schSRegdate" value="<?php echo $schSRegdate; ?>" />
<input type="hidden" name="schERegdate" value="<?php echo $schERegdate; ?>" />
</form>

<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutEndM.php'); ?>

<?php include('boardDtlMScript.php'); ?>

</body>
</html>