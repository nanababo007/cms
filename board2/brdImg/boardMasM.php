<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pagingListInfo.php');
#---
$pageTitleString = "";
$pageMTitleString = "";
$isDisplayRegBtnOnPagingListInfo = false;
$thisPageMnSeq = 24;
$sqlSearchPart = "";
$sqlSearchPartIndex = 0;
$mnSeq = nvl(getRequestValue("mnSeq"),"");
$bdSeq = nvl(getRequestValue("bdSeq"),"");
$pageNumber = intval(nvl(getRequestValue("pageNumber"),"1"));
$pageSize = intval(nvl(getRequestValue("pageSize"),"10"));
$blockSize = intval(nvl(getRequestValue("blockSize"),$envVarMap["pagingBlockSize"]));
$schTitle = nvl(getRequestValue("schTitle"),"");
$schContent = nvl(getRequestValue("schContent"),"");
$boardFixList = null;
$boardFixListCount = 0;
#---
$pageTitleString = "첨부파일 게시판 관리";
$pageMTitleString = "첨부파일 게시판 관리";
$isDisplayRegBtnOnPagingListInfo = true;
#---
fnOpenDB();
setDisplayMenuList();
#---
if($schTitle!=""){
	$sqlSearchPart .= fnGetSqlWhereAndString($sqlSearchPartIndex);
	$sqlSearchPart .= " a.bd_nm like '%${schTitle}%' ";
	$sqlSearchPartIndex++;
}#if
if($schContent!=""){
	$sqlSearchPart .= fnGetSqlWhereAndString($sqlSearchPartIndex);
	$sqlSearchPart .= " a.bd_content like '%${schContent}%' ";
	$sqlSearchPartIndex++;
}#if
#---
$sqlBodyPart = "
	FROM tb_board_img_info a
	${sqlSearchPart}
";
#---
$sqlFix = "
	select
		a.*
	from (
		SELECT
			a.bd_seq
			,a.bd_nm
			,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
			,a.regdate
			,a.reguser
		FROM tb_board_img_info a
		where bd_fix_yn = 'Y'
	) a
	ORDER BY a.bd_seq DESC
";
$boardFixList = fnDBGetList($sqlFix);
$boardFixListCount = getArrayCount($boardFixList);
#---
$sqlCount = "
	SELECT count(*)
	${sqlBodyPart}
";
$boardListTotalCount = fnDBGetIntValue($sqlCount);
#$boardListTotalCount = 328; #test
#---
$pagingInfoMap = fnCalcPaging($pageNumber,$boardListTotalCount,$pageSize,$blockSize);
debugArray("pagingInfoMap",$pagingInfoMap);
#---
$sqlMain = "
	SELECT
		a.bd_seq
		,a.bd_nm
		,STR_TO_DATE(a.regdate, '%Y-%m-%d') as regdate_str
		,a.regdate
		,a.reguser
	${sqlBodyPart}
	ORDER BY a.bd_seq DESC
	LIMIT {{limitStartNumber}}, {{limitEndNumber}}
";
$sqlMain = fnGetPagingQuery($sqlMain,$pagingInfoMap);
debugString("sqlMain",str_replace("\n","<br />",$sqlMain));
$boardList = fnDBGetList($sqlMain);
$boardListCount = getArrayCount($boardList);
#---
fnCloseDB();
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
		if($boardFixListCount > 0){
			foreach ($boardFixList as $index => $row) {
		?>
		<div class="post-item">
			<a href="javascript:goView('<?php echo $row["bd_seq"]; ?>');" class="post-title">[고정] <?php echo $row["bd_nm"]; ?></a>
			<div class="post-info">
				<span>게시판 번호: <?php echo $row["bd_seq"]; ?></span> | 
				<span><a href="javascript:goBoardArticleList('<?php echo $row["bd_seq"]; ?>');">보기</a></span> | 
				<span><a href="javascript:copyBoardArticleListUrl('<?php echo $row["bd_seq"]; ?>');">경로복사</a></span>
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
			<a href="javascript:goView('<?php echo $row["bd_seq"]; ?>');" class="post-title"><?php echo $pagingInfoMap["startRowNumberForPage"] - $index; ?>. <?php echo $row["bd_nm"]; ?></a>
			<div class="post-info">
				<span>게시판 번호: <?php echo $row["bd_seq"]; ?></span> | 
				<span><a href="javascript:goBoardArticleList('<?php echo $row["bd_seq"]; ?>');">보기</a></span> | 
				<span><a href="javascript:copyBoardArticleListUrl('<?php echo $row["bd_seq"]; ?>');">경로복사</a></span>
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
<input type="hidden" name="pageNumber" value="<?php echo $pageNumber; ?>" />
<input type="hidden" name="pageSize" value="<?php echo $pageSize; ?>" />
<input type="hidden" name="blockSize" value="<?php echo $blockSize; ?>" />
<input type="hidden" name="schTitle" value="<?php echo $schTitle; ?>" />
<input type="hidden" name="schContent" value="<?php echo $schContent; ?>" />
</form>

<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutEndM.php'); ?>

<script>
var paramFormObject = document.paramForm;
//---
$(function(){
	initPage();
});
//---
function initPage(){
	setSearchEnter($('#schTitle, #schContent'),function(){
		goSearch();
	});
}
function goPage(pageNumber){
	paramFormObject.pageNumber.value = pageNumber;
	paramFormObject.action = 'boardMasM.php';
	paramFormObject.submit();
}
function goView(bdSeq=''){
	paramFormObject.bdSeq.value = bdSeq;
	paramFormObject.action = 'boardMasViewM.php';
	paramFormObject.submit();
}
function goWrite(bdSeq=''){
	paramFormObject.bdSeq.value = bdSeq;
	paramFormObject.action = 'boardMasWriteM.php';
	paramFormObject.submit();
}
function goBoardArticleList(bdSeq=''){
	var openWin = null;
	var url = '';
	url += 'boardDtlM.php';
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
function goSearch(){
	paramFormObject.schTitle.value = $('#schTitle').val();
	paramFormObject.schContent.value = $('#schContent').val();
	paramFormObject.pageNumber.value = '1';
	paramFormObject.action = 'boardMasM.php';
	paramFormObject.submit();
}
function resetSearch(){
	if(confirm('검색을 초기화 하시겠습니까?')){
		$('#schTitle').val('');
		$('#schContent').val('');
		goSearch();
	}//if
}
function copyBoardSeq(bdSeq=''){
	prompt('게시판 번호를 복사해 주세요.',bdSeq);
}
</script>

</body>
</html>