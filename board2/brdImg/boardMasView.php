<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
#---
$pageTitleString = "";
$thisPageMnSeq = 24;
$mnSeq = nvl(getRequestValue("mnSeq"),"");
$bdSeq = nvl(getRequestValue("bdSeq"));
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
#---
$pageTitleString = "이미지 게시판 관리";
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
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/head.php'); ?>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/top.php'); ?>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutStart.php'); ?>

<h2>이미지 게시판 관리 <span class="menu-navi-class"><?php echo getMenuPathString($thisPageMnSeq); ?></span></h2>

<table class="board-write-table-class">
<colgroup>
	<col width="20%" />
	<col width="30%" />
	<col width="20%" />
	<col width="30%" />
</colgroup>
<tr>
	<th>게시판 이름</th>
	<td colspan="3"><?php echo getArrayValue($boardInfo,"bd_nm"); ?></td>
</tr>
<tr>
	<th>게시판 보기</th>
	<td><a href="javascript:goBoardArticleList('<?php echo getArrayValue($boardInfo,"bd_seq"); ?>');">보기</a></td>
	<th>게시판 경로</th>
	<td><a href="javascript:copyBoardArticleListUrl('<?php echo getArrayValue($boardInfo,"bd_seq"); ?>');">경로복사</a></td>
</tr>
<tr>
	<th>게시판 설명</th>
	<td colspan="3">
		<div align="right" style="margin-top:10px;">
			<input type="button" value="수정" onclick="goModify();" />
			<input type="button" value="삭제" onclick="goDelete();" style="color:red;" />
			<input type="button" value="목록" onclick="goList();" />
		</div>
		<?php echo getDecodeHtmlString(getArrayValue($boardInfo,"bd_content")); ?>
	</td>
</tr>
</table>

<div align="right" style="margin-top:10px;">
	<input type="button" value="수정" onclick="goModify();" />
	<input type="button" value="삭제" onclick="goDelete();" style="color:red;" />
	<input type="button" value="목록" onclick="goList();" />
</div>

<?php fnPrintPagingHtml($pagingInfoMap); ?>

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
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
</form>

<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutEnd.php'); ?>

<script>
var paramFormObject = document.paramForm;
var actionParamFormObject = document.actionParamForm;
//---
function goModify(){
	paramFormObject.action = 'boardMasWrite.php';
	paramFormObject.submit();
}
function goList(){
	paramFormObject.action = 'boardMas.php';
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
	url += '/brdImg/boardDtl.php';
	url += '?bdSeq='+bdSeq;
	prompt('게시글 관리 경로 문자열을 복사해 주세요.',url);
}
</script>

</body>
</html>