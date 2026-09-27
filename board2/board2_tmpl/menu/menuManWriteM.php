<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
#---
$pageTitleString = "";
$pageMTitleString = "";
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
$pageMTitleString = "메뉴 관리";
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
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/headM.php'); ?>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pageWriteMStyle.php'); ?>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutStartM.php'); ?>

<!-- 상단 네비게이션 -->
<nav class="navbar navbar-light bg-white border-bottom sticky-top">
	<div class="container-fluid">
		<a href="javascript:history.back();" class="btn-close" aria-label="Close"></a>
		<span class="navbar-brand mb-0 h1 mx-auto">메뉴 등록</span>
		<div style="width: 32px;"></div> <!-- 중앙 정렬용 여백 -->
	</div>
</nav>

<div class="write-container p-3">
	<form name="writeForm" method="post" action="menuManProc.php">
		<input type="hidden" name="actionString" value="write" />
		<input type="hidden" name="siteModeString" value="M" />
		<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
		<input type="hidden" name="regMnSeq" value="<?php echo $regMnSeq; ?>" />
		<input type="hidden" name="modMnSeq" value="<?php echo $modMnSeq; ?>" />
		<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
		<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
		<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
		<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
		<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
		
		<!-- 메뉴이름 입력 -->
		<div class="mb-3">
			<label for="mnNm" class="form-label">메뉴이름</label>
			<input type="text" class="form-control" id="mnNm" name="mnNm" value="<?php echo getArrayValue($menuInfo,"mn_nm"); ?>" placeholder="메뉴이름을 입력하세요" />
		</div>

		<!-- 메뉴설명 입력 -->
		<div class="mb-3">
			<label for="mnContent" class="form-label">메뉴설명</label>
			<textarea class="form-control" id="mnContent" name="mnContent" rows="8" placeholder="메뉴설명을 입력해주세요"><?php echo getArrayValue($menuInfo,"mn_content"); ?></textarea>
		</div>

		<!-- 메뉴 URL 입력 -->
		<div class="mb-3">
			<label for="mnUrl" class="form-label">메뉴 URL (&mnSeq=<?php echo $modMnSeq; ?>)</label>
			<input type="text" class="form-control" id="mnUrl" name="mnUrl" value="<?php echo nvl(getArrayValue($menuInfo,"mn_url"),"http://"); ?>" placeholder="메뉴URL을 입력하세요" />
		</div>

		<!-- 메뉴 URL TARGET -->
		<div class="mb-3">
			<label for="mnUrlTargetCombo" class="form-label">메뉴 URL TARGET</label>
			<select class="form-select" id="mnUrlTargetCombo">
				<option value="" selected disabled>TARGET 선택</option>
				<option value="_self">_self</option>
				<option value="_blank">_blank</option>
			</select>
			<input type="text" class="form-control" id="mnUrlTarget" name="mnUrlTarget" value="<?php echo getArrayValue($menuInfo,"mn_url_target"); ?>" placeholder="메뉴타겟을 입력하세요" style="margin-top:10px;" />
		</div>

		<!-- 메뉴 사용 여부 -->
		<div class="mb-3">
			<label class="form-label d-block">메뉴 사용 여부</label>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" id="mnUseY" name="mnUseYn" value="Y" <?php echo nvl(getArrayValue($menuInfo,"mn_use_yn"),"Y")=="Y" ? " checked " : ""; ?> />
				<label class="form-check-label" for="mnUseY">사용</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" id="mnUseN" name="mnUseYn" value="N" <?php echo nvl(getArrayValue($menuInfo,"mn_use_yn"),"Y")!="Y" ? " checked " : ""; ?> />
				<label class="form-check-label" for="mnUseN">미사용</label>
			</div>
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
<input type="hidden" name="regMnSeq" value="<?php echo $regMnSeq; ?>" />
<input type="hidden" name="modMnSeq" value="<?php echo $modMnSeq; ?>" />
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
	paramFormObject.action = 'menuManM.php';
	paramFormObject.submit();
}
</script>

</body>
</html>