<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
#---
$bdSeq = nvl(getRequestValue("bdSeq"));
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
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

<h2>게시판 관리</h2>

<form name="writeForm" method="post" action="boardProc.php">
<input type="hidden" name="actionString" value="write" />
<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
<table class="board-write-table-class">
<colgroup>
	<col width="20%" />
	<col width="30%" />
	<col width="20%" />
	<col width="30%" />
</colgroup>
<tr>
	<th>게시판 이름</th>
	<td colspan="3"><input type="text" name="bdNm" value="<?php echo getArrayValue($boardInfo,"bd_nm"); ?>" style="width:90%;height:20px;" /></td>
</tr>
<tr>
	<th>게시판 설명</th>
	<td colspan="3">
		<textarea name="bdContent" style="width:90%;height:200px;"><?php echo getArrayValue($boardInfo,"bd_content"); ?></textarea>
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
	paramFormObject.action = 'board.php';
	paramFormObject.submit();
}
</script>

</body>
</html>