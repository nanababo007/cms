<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/checkLogin.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/menu.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/brdMas/boardLibraryInclude.php');
include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/pagingListInfo.php');
include('boardDtlServer.php');
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

<table class="search-table-class">
<colgroup>
	<col width="15%" />
	<col width="35%" />
	<col width="15%" />
	<col width="35%" />
</colgroup>
<tr>
	<td colspan="4"><strong>검색</strong></td>
</tr>
<tr>
	<th>게시글 제목</th>
	<td><input type="text" id="schTitle" value="<?php echo $schTitle; ?>" /></td>
	<th>게시글 내용</th>
	<td><input type="text" id="schContent" value="<?php echo $schContent; ?>" /></td>
</tr>
<tr>
	<th>게시글 댓글</th>
	<td><input type="text" id="schReply" value="<?php echo $schReply; ?>" /></td>
	<th>게시글 등록일</th>
	<td>
		<input type="text" id="schSRegdate" value="<?php echo $schSRegdate; ?>" style="width:35.4%;" class="datepicker-input-class" /> ~ 
		<input type="text" id="schERegdate" value="<?php echo $schERegdate; ?>" style="width:35.4%;" class="datepicker-input-class" />
	</td>
</tr>
<tr class="last-row-class">
	<td colspan="4" align="right">
		<button onclick="javascript:goSearch();">검색</button>
		<button onclick="javascript:resetSearch();" style="color:red;">초기화</button>
	</td>
</tr>
</table>

<?php fnPrintPagingListInfo($pagingInfoMap); ?>

<table class="board-table-class">
<colgroup>
	<col width="10%" />
	<col width="*" />
	<col width="20%" />
	<col width="20%" />
	<col width="20%" />
</colgroup>
<tr>
	<th>번호</th>
	<th>제목</th>
	<th>조회수</th>
	<th>등록자</th>
	<th>등록일</th>
</tr>
<?php
if($boardDtlFixListCount > 0){
	foreach ($boardDtlFixList as $index => $row) {
?>
<tr>
	<td align="center">고정</td>
	<td align="left"><a href="javascript:goView('<?php echo $row["bda_seq"]; ?>');"><?php echo $row["bda_title"]; ?></a></td>
	<td align="center"><?php echo $row["bda_view_cnt"]; ?></td>
	<td align="center"><?php echo $row["regdate_str"]; ?></td>
	<td align="center"><?php echo $row["reguser"]; ?></td>
</tr>
<?php
	}#foreach
}#if
?>
<?php
if($boardListTotalCount > 0){
	foreach ($boardList as $index => $row) {
?>
<tr>
	<td align="center"><?php echo $pagingInfoMap["startRowNumberForPage"] - $index; ?></td>
	<td align="left"><a href="javascript:goView('<?php echo $row["bda_seq"]; ?>');"><?php echo $row["bda_title"]; ?></a></td>
	<td align="center"><?php echo $row["bda_view_cnt"]; ?></td>
	<td align="center"><?php echo $row["regdate_str"]; ?></td>
	<td align="center"><?php echo $row["reguser"]; ?></td>
</tr>
<?php
	}#foreach
}#if
?>
<?php if($boardListTotalCount == 0){ ?>
<tr>
	<td align="center" colspan="5">등록된 게시글이 없습니다.</td>
</tr>
<?php }//if ?>
</table>

<div align="right" style="margin-top:10px;">
	<input type="button" value="등록" onclick="goWrite();" />
</div>

<?php fnPrintPagingHtml($pagingInfoMap); ?>

<form name="paramForm" method="get">
<input type="hidden" name="bdSeq" value="<?php echo $bdSeq; ?>" />
<input type="hidden" name="mnSeq" value="<?php echo $mnSeq; ?>" />
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

<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/layoutEnd.php'); ?>

<?php include('boardDtlScript.php'); ?>

</body>
</html>