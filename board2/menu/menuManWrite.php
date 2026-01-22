<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
#---
$pageTitleString = "";
$thisPageMnSeq = 2;
$mnSeq = nvl(getRequestValue("mnSeq"));
$regMnSeq = nvl(getRequestValue("regMnSeq"));
$modMnSeq = nvl(getRequestValue("modMnSeq"));
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),"10"));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
#---
$pageTitleString = "메뉴 관리";
#---
fnOpenDB();
setDisplayMenuList();
#---
if($modMnSeq!=""){
	$sqlBodyPart = "
		FROM tb_board_menu_info a
		where mn_seq = ${modMnSeq}
		and mn_del_yn = 'N'
	";
	#---
	$sqlMain = "
		SELECT
			a.mn_seq
			,a.p_mn_seq
			,a.mn_nm
			,a.mn_content
			,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
			,a.mn_ord
			,a.mn_url
			,a.mn_url_target
			,a.mn_use_yn
			,a.regdate
			,a.reguser
		${sqlBodyPart}
	";
	$menuInfo = fnDBGetRow($sqlMain);
}else{
	$menuInfo = array();
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

<h2>메뉴 관리 <span class="menu-navi-class"><?php echo getMenuPathString($thisPageMnSeq); ?></span></h2>

<form name="writeForm" method="post" action="menuManProc.php">
<input type="hidden" name="actionString" value="write" />
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
<input type="hidden" name="regMnSeq" value="<?php echo $regMnSeq; ?>" />
<input type="hidden" name="modMnSeq" value="<?php echo $modMnSeq; ?>" />
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
	<th align="center">메뉴 이름</th>
	<td colspan="3"><input type="text" name="mnNm" value="<?php echo getArrayValue($menuInfo,"mn_nm"); ?>" style="width:90%;height:20px;" /></td>
</tr>
<tr>
	<th align="center">메뉴 설명</th>
	<td colspan="3">
		<textarea name="mnContent" style="width:90%;height:200px;"><?php echo getArrayValue($menuInfo,"mn_content"); ?></textarea>
	</td>
</tr>
<tr>
	<th align="center">메뉴 URL (&mnSeq=<?php echo $modMnSeq; ?>)</th>
	<td colspan="3">
		<input type="text" name="mnUrl" value="<?php echo nvl(getArrayValue($menuInfo,"mn_url"),"http://"); ?>" style="width:90%;height:20px;" />
	</td>
</tr>
<tr>
	<th align="center">메뉴 URL TARGET</th>
	<td>
		<select id="mnUrlTargetCombo" style="padding:2px 4px;margin-bottom:6px;">
		<option value="">TARGET 선택</option>
		<option value="_self">_self</option>
		<option value="_blank">_blank</option>
		</select>
		<input type="text" name="mnUrlTarget" value="<?php echo getArrayValue($menuInfo,"mn_url_target"); ?>" style="width:90%;height:20px;" />
	</td>
	<th align="center">메뉴 사용 여부</th>
	<td>
		<input type="radio" id="mnUseY" name="mnUseYn" value="Y" <?php echo nvl(getArrayValue($menuInfo,"mn_use_yn"),"Y")=="Y" ? " checked " : ""; ?> /> <label for="mnUseY">사용</label>
		<input type="radio" id="mnUseN" name="mnUseYn" value="N" <?php echo nvl(getArrayValue($menuInfo,"mn_use_yn"),"Y")!="Y" ? " checked " : ""; ?> /> <label for="mnUseN">미사용</label>
	</td>
</tr>
</table>
</form>

<div align="right" style="margin-top:10px;">
	<input type="button" value="저장" onclick="goSave();" />
	<input type="button" value="취소" onclick="goCancel();" />
</div>

<?php fnPrintPagingHtml($pagingInfoMap); ?>

<form name="paramForm" method="get">
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
<input type="hidden" name="regMnSeq" value="<?php echo $regMnSeq; ?>" />
<input type="hidden" name="modMnSeq" value="<?php echo $modMnSeq; ?>" />
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
$(function(){
	$('#mnUrlTargetCombo').click(function(e){
		writeFormObject.mnUrlTarget.value = this.value;
	});
});
//---
function goSave(){
	if(writeFormObject.modMnSeq.value!==''){
		writeFormObject.actionString.value = 'modify';
	}else{
		writeFormObject.actionString.value = 'write';
	}//if
	if(writeFormObject.mnNm.value===''){alert('메뉴 이름을 입력해주세요.');writeFormObject.mnNm.focus();return;}//if
	writeFormObject.submit();
}
function goCancel(){
	paramFormObject.action = 'menuMan.php';
	paramFormObject.submit();
}
</script>

</body>
</html>