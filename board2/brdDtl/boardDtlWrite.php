<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdMas/boardLibraryInclude.php');
#---
$thisPageMnSeq = getThisPageMnSeq("23","17");
$boardInfo = null;
$boardArticleInfo = null;
$pageTitleString = "";
$mnSeq = nvl(getRequestValue("mnSeq"));
$bdSeq = nvl(getRequestValue("bdSeq"));
$bdaSeq = nvl(getRequestValue("bdaSeq"));
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
$schReply = nvl(getRequestValue("schReply"),"");
#---
fnOpenDB();
setDisplayMenuList();
#---
$boardInfo = fnBoardGetInfo($bdSeq);
if($boardInfo==null){alertBack("게시판 정보가 존재하지 않습니다.");}#if
debugArray("boardInfo",$boardInfo);
#---
$pageTitleString = getArrayValue($boardInfo,"bd_nm")." | 멀티게시판";
#---
if($bdaSeq!=""){
	$sqlBodyPart = "
		FROM tb_board_article a
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
}else{
	$boardArticleInfo = array();
}#if
#---
fnCloseDB();
?>
<!doctype html>
<html lang="ko">
<head>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/head.php'); ?>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/top.php'); ?>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutStart.php'); ?>

<h2>게시글 관리 (<?php echo getArrayValue($boardInfo,"bd_nm"); ?>) <span class="menu-navi-class"><?php echo getMenuPathString($thisPageMnSeq); ?></span></h2>

<form name="writeForm" method="post" action="boardDtlProc.php">
<input type="hidden" name="actionString" value="write" />
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
<input type="hidden" name="bdaSeq" value="<?php echo $bdaSeq; ?>" />
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
<input type="hidden" name="schReply" value="<?php echo $schReply; ?>" />
<table class="board-write-table-class">
<colgroup>
	<col width="20%" />
	<col width="30%" />
	<col width="20%" />
	<col width="30%" />
</colgroup>
<tr>
	<th>게시글 제목</th>
	<td colspan="3"><input type="text" name="bdaTitle" value="<?php echo getArrayValue($boardArticleInfo,"bda_title"); ?>" style="width:90%;height:20px;" /></td>
</tr>
<tr>
	<th align="center">게시글 고정 여부</th>
	<td colspan="3">
		<input type="radio" id="bdaFixY" name="bdaFixYn" value="Y" <?php echo nvl(getArrayValue($boardArticleInfo,"bda_fix_yn"),"N")=="Y" ? " checked " : ""; ?> /> <label for="bdaFixY">고정</label>
		<input type="radio" id="bdaFixN" name="bdaFixYn" value="N" <?php echo nvl(getArrayValue($boardArticleInfo,"bda_fix_yn"),"N")!="Y" ? " checked " : ""; ?> /> <label for="bdaFixN">비고정</label>
	</td>
</tr>
<tr>
	<th>게시글 내용</th>
	<td colspan="3">
		<textarea name="bdaContent" style="width:90%;height:200px;"><?php echo getArrayValue($boardArticleInfo,"bda_content"); ?></textarea>
	</td>
</tr>
<!--<tr>
	<th>게시판 이름</th>
	<td>aaaaaaa</td>
	<th>게시판 이름</th>
	<td>aaaaaaa</td>
</tr>-->
</table>
</form>

<div align="right" style="margin-top:10px;">
	<input type="button" value="저장" onclick="goSave();" />
	<input type="button" value="취소" onclick="goCancel();" />
</div>

<?php fnPrintPagingHtml($pagingInfoMap); ?>

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
</form>

<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutEnd.php'); ?>

<script>
var paramFormObject = document.paramForm;
var writeFormObject = document.writeForm;
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
	paramFormObject.action = 'boardDtl.php';
	paramFormObject.submit();
}
</script>

</body>
</html>