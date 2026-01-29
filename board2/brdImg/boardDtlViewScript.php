<script>
var paramFormObject = document.paramForm;
var actionParamFormObject = document.actionParamForm;
var historyViewParamFormObject = document.historyViewParamForm;
var boardContentHistoryListAreaJqueryObject = $('.board-content-history-list-area');
//---
$(function(){
	initThisPage();
});
//---
function initThisPage(){
	$('.board-content-area').each(function(index,el){
		var boardContentJqueryObject = $(el);
		var boardContentHtmlString = boardContentJqueryObject.html();
		//---
		boardContentHtmlString = getLinkContentHtmlString(boardContentHtmlString);
		//---
		boardContentJqueryObject.html(boardContentHtmlString);
	});
}
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
function goReplyPos(){
	location.href = '#replyPos';
}
function goPageEndPos(){
	location.href = '#pageEndPos';
}
function toggleBoardContentHistoryList(){
	boardContentHistoryListAreaJqueryObject.toggle();
}
function goBoardContentHistoryView(histBdaSeq=''){
	historyViewParamFormObject.histBdaSeq.value = histBdaSeq;
	historyViewParamFormObject.submit();
}
</script>