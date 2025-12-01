<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdImg/boardMasLibraryInclude.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdImg/boardDtlLibraryInclude.php');
#---
$thisPageMnSeq = 24;
$boardArticleInfo = null;
$boardInfo = null;
$mnSeq = nvl(getRequestValue("mnSeq"),"");
$bdSeq = nvl(getRequestValue("bdSeq"));
$bdaSeq = nvl(getRequestValue("bdaSeq"));
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
#---
fnOpenDB();
setDisplayMenuList();
#---
if($bdSeq==""){alertBack("정보가 부족합니다.");}#if
if($bdaSeq==""){alertBack("정보가 부족합니다.");}#if
#---
$boardInfo = fnBoardGetInfo($bdSeq);
if($boardInfo==null){alertBack("게시판 정보가 존재하지 않습니다.");}#if
debugArray("boardInfo",$boardInfo);
if(!fnBoardArticleCheckInfo($bdaSeq)){alertBack("게시글 정보가 존재하지 않습니다.");}#if
#---
$sql = "
	update tb_board_img_article set
		bda_view_cnt = bda_view_cnt + 1
	where bda_seq = ${bdaSeq}
";
fnDBUpdate($sql);
#---
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
		,a.bda_view_cnt
		,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
		,STR_TO_DATE(a.moddate, '%Y-%m-%d') as moddate_str
		,a.regdate
		,a.reguser
		,a.moddate
		,a.moduser
	${sqlBodyPart}
";
debugString("sqlMain",getDecodeHtmlString($sqlMain));
$boardArticleInfo = fnDBGetRow($sqlMain);
#---
fnCloseDB();
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
	<td colspan="4"><?php echo getDecodeHtmlString(getArrayValue($boardArticleInfo,"bda_content")); ?></td>
</tr>
</table>

<div align="right" style="margin-top:10px;">
	<input type="button" value="수정" onclick="goModify();" />
	<input type="button" value="삭제" onclick="goDelete();" style="color:red;" />
	<input type="button" value="목록" onclick="goList();" />
</div>

<div class="reply-area-class">
	<textarea style="width:99.4%;height:100px;margin-top:10px;" placeholder="댓글내용" id="replyContent"></textarea>
	<button onclick="javascript:writeReply();">댓글등록</button>
	<button onclick="javascript:cancelReply();">댓글취소</button>
	<div class="reply-item-area-class"></div>
</div>

<form name="paramForm" method="get">
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
<input type="hidden" name="bdaSeq" value="<?php echo $bdaSeq; ?>" />
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
<input type="hidden" name="bdaSeq" value="<?php echo $bdaSeq; ?>" />
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
	paramFormObject.action = 'boardDtlWrite.php';
	paramFormObject.submit();
}
function goList(){
	paramFormObject.action = 'boardDtl.php';
	paramFormObject.submit();
}
function goDelete(){
	if(confirm('삭제 하시겠습니까?')){
		actionParamFormObject.actionString.value = 'delete';
		actionParamFormObject.action = 'boardDtlProc.php';
		actionParamFormObject.submit();
	}//if
}
</script>

</body>
</html>