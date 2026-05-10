<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdImg/boardMasLibraryInclude.php');
#---
$thisPageMnSeq = 24;
$bdaFileCount = 12;
$bdaFileIndex = 0;
$bdaFileNumber = 0;
$boardInfo = null;
$boardArticleInfo = null;
$pageTitleString = "";
$pageMTitleString = "";
$mnSeq = nvl(getRequestValue("mnSeq"));
$bdSeq = nvl(getRequestValue("bdSeq"));
$bdaSeq = nvl(getRequestValue("bdaSeq"));
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
$schReply = nvl(getRequestValue("schReply"),"");
$schSRegdate = nvl(getRequestValue("schSRegdate"),"");
$schERegdate = nvl(getRequestValue("schERegdate"),"");
$boardDtlFileList = null;
$boardDtlFileListCount = 0;
$boardDtlFileInfo = null;
$bdafKindName = "";
#---
fnOpenDB();
setDisplayMenuList();
#---
$boardInfo = fnBoardGetInfo($bdSeq);
if($boardInfo==null){alertBack("게시판 정보가 존재하지 않습니다.");}#if
debugArray("boardInfo",$boardInfo);
#---
$pageTitleString = getArrayValue($boardInfo,"bd_nm")." | 멀티게시판";
$pageMTitleString = getArrayValue($boardInfo,"bd_nm")." | 멀티게시판";
#---
if($bdaSeq!=""){
	$sqlBodyPart = "
		FROM tb_board_img_article a
		where bda_seq = ${bdaSeq}
	";
	#---
	$sqlMain = "
		SELECT
			a.bda_seq
			,a.bd_seq
			,a.bda_title
			,a.bda_content
			,a.bda_fix_yn
			,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
			,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
			,a.regdate
			,a.reguser
			,a.moddate
			,a.moduser
		${sqlBodyPart}
	";
	$boardArticleInfo = fnDBGetRow($sqlMain);
	#---
	$sqlFile = "
		select
			a.*
		from (
			SELECT
				a.bdaf_seq
				,a.bda_seq
				,a.bdaf_filename
				,a.bdaf_save_filename
				,a.bdaf_save_thumbnail
				,a.bdaf_kind_name
				,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
				,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
				,a.regdate
				,a.reguser
				,a.moddate
				,a.moduser
			from tb_board_img_article_file a
			where a.bda_seq = ${bdaSeq}
		) a
		ORDER BY a.bda_seq DESC
	";
	$boardDtlFileList = fnDBGetList($sqlFile);
	$boardDtlFileListCount = getArrayCount($boardDtlFileList);
}else{
	$boardArticleInfo = array();
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
		<span class="navbar-brand mb-0 h1 mx-auto">게시글 등록</span>
		<div style="width: 32px;"></div> <!-- 중앙 정렬용 여백 -->
	</div>
</nav>

<div class="write-container p-3">
	<form name="writeForm" method="post" action="boardDtlProc.php" enctype="multipart/form-data">
		<input type="hidden" name="actionString" value="write" />
		<input type="hidden" name="siteModeString" value="M" />
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

		<!-- 게시글 제목 입력 -->
		<div class="mb-3">
			<label for="bdaTitle" class="form-label">게시글 제목</label>
			<input type="text" class="form-control" id="bdaTitle" name="bdaTitle" value="<?php echo getArrayValue($boardArticleInfo,"bda_title"); ?>" placeholder="게시글 제목을 입력하세요" />
		</div>
		
		<!-- 게시글 고정 여부 -->
		<div class="mb-3">
			<label class="form-label d-block">게시글 고정 여부</label>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" id="bdaFixY" name="bdaFixYn" value="Y" <?php echo nvl(getArrayValue($boardArticleInfo,"bda_fix_yn"),"N")=="Y" ? " checked " : ""; ?> />
				<label class="form-check-label" for="bdaFixY">고정</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" id="bdaFixN" name="bdaFixYn" value="N" <?php echo nvl(getArrayValue($boardArticleInfo,"bda_fix_yn"),"N")!="Y" ? " checked " : ""; ?> />
				<label class="form-check-label" for="bdaFixN">비고정</label>
			</div>
		</div>
		
		<!-- 게시글 설명 입력 -->
		<div class="mb-3">
			<label for="bdaContent" class="form-label">게시글 설명</label>
			<textarea class="form-control" id="bdaContent" name="bdaContent" rows="8" placeholder="게시글 설명을 입력해주세요"><?php echo getArrayValue($boardArticleInfo,"bda_content"); ?></textarea>
		</div>
	
		<!-- 게시글 첨부파일 -->
		<div class="mb-3">
			<label for="formFileMultiple1" class="form-label">게시글 첨부파일</label>
			<?php
				for($bdaFileIndex=0;$bdaFileIndex<$bdaFileCount;$bdaFileIndex++){
					$bdaFileNumber = $bdaFileIndex+1;
					$bdafKindName = "file".$bdaFileNumber;
					$boardDtlFileInfo = getBoardDtlFileInfo($bdafKindName);
					#---
					$boardDtlFileInfo = $boardDtlFileInfo==null ? array() : $boardDtlFileInfo;
					?>
			<div>
				<a href="<?php echo getArrayValue($boardDtlFileInfo,"bdaf_save_filename"); ?>" target="_blank"><?php echo getArrayValue($boardDtlFileInfo,"bdaf_filename"); ?></a>
				<?php echo $bdaFileNumber; ?>. 첨부파일 : 
				<?php if(nvl(getArrayValue($boardDtlFileInfo,"bdaf_seq"))!=""){ ?>
				&nbsp;|&nbsp;<a href="javascript:deleteFile(<?php echo getArrayValue($boardDtlFileInfo,"bdaf_seq"); ?>);">파일삭제</a>
				<?php }//if ?>
			</div>
			<input class="form-control" type="file" name="file<?php echo $bdaFileNumber; ?>" id="formFileMultiple<?php echo $bdaFileNumber; ?>" style="margin-bottom:10px;" />
					<?php
				}#for
			?>
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

<form name="postForm" method="post" action="boardDtlProc.php">
<input type="hidden" name="actionString" value="" />
<input type="hidden" name="siteModeString" value="M" />
<input type="hidden" name="bdafSeq" value="" />
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

<script>
var paramFormObject = document.paramForm;
var writeFormObject = document.writeForm;
var postFormObject = document.postForm;
//---
function goSave(pageNumber){
	if(writeFormObject.bdaSeq.value!==''){
		writeFormObject.actionString.value = 'modify';
	}else{
		writeFormObject.actionString.value = 'write';
	}//if
	if(writeFormObject.bdaTitle.value===''){alert('게시글 제목을 입력해주세요.');writeFormObject.bdaTitle.focus();return;}//if
	writeFormObject.submit();
}
function goCancel(){
	paramFormObject.action = 'boardDtlM.php';
	paramFormObject.submit();
}
function deleteFile(bdafSeq=''){
	if(confirm("파일을 삭제 하시겠습니까?")){
		postFormObject.actionString.value = "deleteFile";
		postFormObject.bdafSeq.value = bdafSeq;
		postFormObject.submit();
	}//if
}
</script>

</body>
</html>
<?php 
function getBoardDtlFileInfo($bdafKindName=""){
	global $boardDtlFileList;
	global $boardDtlFileListCount;
	#---
	$returnMap = null;
	#---
	$boardDtlFileInfo = null;
	$boardDtlFileListIndex = 0;
	$boardDtlFileListNumber = 0;
	#---
	if($bdafKindName==""){return null;}#if
	#---
	if($boardDtlFileListCount > 0){
		for($boardDtlFileListIndex=0;$boardDtlFileListIndex<$boardDtlFileListCount;$boardDtlFileListIndex++){
			$boardDtlFileListNumber = $boardDtlFileListIndex+1;
			$boardDtlFileInfo = $boardDtlFileList[$boardDtlFileListIndex];
			if($bdafKindName==trim(nvl($boardDtlFileInfo["bdaf_kind_name"]))){
				$returnMap = $boardDtlFileInfo;
				break;
			}#if
		}#for
	}#if
	#---
	return $returnMap;
}
?>