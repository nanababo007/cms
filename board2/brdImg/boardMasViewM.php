<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
#---
$pageTitleString = "";
$pageMTitleString = "";
$thisPageMnSeq = 24;
$mnSeq = nvl(getRequestValue("mnSeq"),"");
$bdSeq = nvl(getRequestValue("bdSeq"));
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
#---
$pageTitleString = "첨부파일 게시판 관리";
$pageMTitleString = "첨부파일 게시판 관리";
#---
if($bdSeq==""){alertBack("정보가 부족합니다.");}#if
#---
fnOpenDB();
setDisplayMenuList();
#---
if($bdSeq!=""){
	$sqlBodyPart = "
		FROM tb_board_img_info a
		where bd_seq = ${bdSeq}
	";
	#---
	$sqlMain = "
		SELECT
			a.bd_seq
			,a.bd_nm
			,a.bd_content
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
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pageViewMStyle.php'); ?>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutStartM.php'); ?>

<!-- 상단 네비게이션 -->
<nav class="navbar navbar-light bg-white border-bottom sticky-top">
	<div class="container-fluid justify-content-start">
		<a href="javascript:history.back();" class="btn btn-sm me-2 text-dark" style="font-size: 1.2rem;">←</a>
		<span class="navbar-brand mb-0 h1">게시판 관리</span>
	</div>
</nav>

<div class="view-container">
	<!-- 게시글 헤더 -->
	<div class="post-header">
		<h1 class="post-title"><?php echo getArrayValue($boardInfo,"bd_nm"); ?></h1>
		<div class="post-meta d-flex justify-content-between">
			<div>
				<strong><?php echo getArrayValue($boardInfo,"reguser"); ?></strong> <span class="mx-1">|</span> <span><?php echo getArrayValue($boardInfo,"regdate_str"); ?></span>
			</div>
			<!--div>조회 1,254</div-->
		</div>
	</div>
	
	<!-- 본문 내용 -->
	<div class="post-content">
		<p>
			게시판 보기:
			<a href="javascript:goBoardArticleList('<?php echo getArrayValue($boardInfo,"bd_seq"); ?>');">보기</a>
		</p>
		<p>
			게시판 경로:
			<a href="javascript:copyBoardArticleListUrl('<?php echo getArrayValue($boardInfo,"bd_seq"); ?>');">경로복사</a>
		</p>
		<p class="post-content-area"><?php echo getDecodeHtmlString(getArrayValue($boardInfo,"bd_content")); ?></p>
		<!--img src="https://placeholder.com" class="img-fluid rounded mb-3" alt="샘플 이미지" /-->
	</div>
	
	<!-- 하단 여백 (고정 하단바 공간) -->
	<div style="height: 60px;"></div>
</div>

<!-- 하단 고정 액션바 -->
<div class="bottom-nav">
	<a href="javascript:goList();" class="btn btn-outline-secondary flex-grow-1">목록</a>
	<button class="btn btn-light border flex-shrink-0" onclick="goModify();">수정</button>
	<button class="btn btn-danger flex-shrink-0" onclick="goDelete();" style="color:ffaaaa;">삭제</button>
</div>

<script src="/board2/cmn/bootstrap5/js/bootstrap.bundle.min.js"></script>

<form name="paramForm" method="get">
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
</form>

<form name="actionParamForm" method="post">
<input type="hidden" name="actionString" value="" />
<input type="hidden" name="siteModeString" value="M" />
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
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
var actionParamFormObject = document.actionParamForm;
//---
function goModify(){
	paramFormObject.action = 'boardMasWriteM.php';
	paramFormObject.submit();
}
function goList(){
	paramFormObject.action = 'boardMasM.php';
	paramFormObject.submit();
}
function goDelete(){
	if(confirm('삭제 하시겠습니까?')){
		actionParamFormObject.actionString.value = 'delete';
		actionParamFormObject.action = 'boardMasProc.php';
		actionParamFormObject.submit();
	}//if
}
function goBoardArticleList(bdSeq=''){
	var openWin = null;
	var url = '';
	url += 'boardDtl.php';
	url += '?bdSeq='+bdSeq;
	openWin = window.open(url,'_blank');
	openWin.focus();
}
function copyBoardArticleListUrl(bdSeq=''){
	var url = '';
	url += '<?php echo $envVarMap["appWebPath"]; ?>';
	url += '/brdImg/boardDtlM.php';
	url += '?bdSeq='+bdSeq;
	prompt('게시글 관리 경로 문자열을 복사해 주세요.',url);
}
</script>

</body>
</html>