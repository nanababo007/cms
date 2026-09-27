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
	paramFormObject.action = 'boardDtlWriteM.php';
	paramFormObject.submit();
}
function goList(){
	paramFormObject.action = 'boardDtlM.php';
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
function goToggleFix(bdaSeq='',bdaFixYN='',callbackFunc=null){
	var apiUrl = '';
	var paramsObject = {};
	//---
	if(bdaSeq && confirm('고정 혹은 고정해제 처리를 하시겠습니까?')){
		apiUrl = 'boardDtlApi.php';
		//---
		bdaFixYN = bdaFixYN==='N' ? 'Y' : 'N';
		//---
		paramsObject.actionString = 'FIX_ARTICLE_DATA';
		paramsObject.bdaSeq = bdaSeq;
		paramsObject.bdaFixYN = bdaFixYN;
		//---
		$.post(apiUrl,paramsObject,function(data){
			//console.info('goToggleFix : data : ',data);
			if($.isFunction(callbackFunc)){callbackFunc(data);}//if
		});
	}//if
}
</script>