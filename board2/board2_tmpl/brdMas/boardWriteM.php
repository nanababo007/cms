<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
#---
$pageTitleString = "";
$pageMTitleString = "";
$thisPageMnSeq = 16;
$bdSeq = nvl(getRequestValue("bdSeq"));
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
#---
$pageTitleString = "게시판 관리";
$pageMTitleString = "게시판 관리";
#---
fnOpenDB();
setDisplayMenuList();
#---
if($bdSeq!=""){
	$sqlBodyPart = "
		FROM tb_board_info a
		where bd_seq = ${bdSeq}
	";
	#---
	$sqlMain = "
		SELECT
			a.bd_seq
			,a.bd_nm
			,a.bd_content
			,a.bd_fix_yn
			,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
			,a.regdate
			,a.reguser
		${sqlBodyPart}
	";
	$boardInfo = fnDBGetRow($sqlMain);
}else{
	$boardInfo = array();
}#if
#---
fnCloseDB();
?>
<!doctype html>
<html lang="ko">
<head>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/headM.php'); ?>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pageWriteMStyle.php'); ?>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutStartM.php'); ?>

<!-- 상단 네비게이션 -->
<nav class="navbar navbar-light bg-white border-bottom sticky-top">
	<div class="container-fluid">
		<a href="javascript:history.back();" class="btn-close" aria-label="Close"></a>
		<span class="navbar-brand mb-0 h1 mx-auto">게시판 등록</span>
		<div style="width: 32px;"></div> <!-- 중앙 정렬용 여백 -->
	</div>
</nav>

<div class="write-container p-3">
	<form name="writeForm" method="post" action="boardProc.php">
		<input type="hidden" name="actionString" value="write" />
		<input type="hidden" name="siteModeString" value="M" />
		<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
		<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
		<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
		<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
		<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
		<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
		
		<!-- 게시판 이름 입력 -->
		<div class="mb-3">
			<label for="bdNm" class="form-label">게시판 이름</label>
			<input type="text" class="form-control" id="bdNm" name="bdNm" value="<?php echo getArrayValue($boardInfo,"bd_nm"); ?>" placeholder="게시판이름을 입력하세요" />
		</div>
		
		<!-- 게시판 고정 여부 -->
		<div class="mb-3">
			<label class="form-label d-block">게시판 고정 여부</label>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" id="bdFixY" name="bdFixYn" value="Y" <?php echo nvl(getArrayValue($boardInfo,"bd_fix_yn"),"N")=="Y" ? " checked " : ""; ?> />
				<label class="form-check-label" for="bdFixY">고정</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" id="bdFixN" name="bdFixYn" value="N" <?php echo nvl(getArrayValue($boardInfo,"bd_fix_yn"),"N")!="Y" ? " checked " : ""; ?> />
				<label class="form-check-label" for="bdFixN">미고정</label>
			</div>
		</div>
		
		<!-- 게시판 설명 입력 -->
		<div class="mb-3">
			<label for="bdContent" class="form-label">게시판 설명</label>
			<textarea class="form-control" id="bdContent" name="bdContent" rows="8" placeholder="게시판 설명을 입력해주세요"><?php echo getArrayValue($boardInfo,"bd_content"); ?></textarea>
		</div>
		
	</form>
</div>

<!-- 하단 고정 버튼 바 -->
<div class="bottom-actions d-flex gap-2">
	<button type="button" class="btn btn-outline-secondary flex-grow-1" onclick="goCancel();">취소</button>
	<button type="button" class="btn btn-primary flex-grow-1 fw-bold" onclick="goSave();">저장</button>
</div>

<script src="/board2/cmn/bootstrap5/js/bootstrap.bundle.min.js"></script>

<form name="paramForm" method="get">
<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
</form>

<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutEndM.php'); ?>

<script>
var paramFormObject = document.paramForm;
var writeFormObject = document.writeForm;
//---
function goSave(pageNumber){
	if(writeFormObject.bdSeq.value!==''){
		writeFormObject.actionString.value = 'modify';
	}else{
		writeFormObject.actionString.value = 'write';
	}//if
	if(writeFormObject.bdNm.value===''){alert('게시판 이름을 입력해주세요.');writeFormObject.bdNm.focus();return;}//if
	writeFormObject.submit();
}
function goCancel(){
	paramFormObject.action = 'boardM.php';
	paramFormObject.submit();
}
</script>

</body>
</html>