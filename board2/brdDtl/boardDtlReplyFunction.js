var replyObjects = {};
//---
$(function(){
	setReplyObjects();
	initReply();
	initReplyEvents();
});
//--- init functions
function setReplyObjects(){
	var paramFormObject = document.paramForm;
	//---
	replyObjects.settingsObject = {};
	replyObjects.settingsObject.apiUrl = 'boardDtlReplyApi.php';
	replyObjects.settingsObject.bdSeq = paramFormObject.bdSeq.value;
	replyObjects.settingsObject.bdaSeq = paramFormObject.bdaSeq.value;
	//---
	replyObjects.replyContentObject = document.getElementById('replyContent');
	replyObjects.replyContentJqueryObject = $(replyObjects.replyContentObject);
	replyObjects.replyItemAreaJqueryObject = $('.reply-item-area-class').eq(0);
}
function initReply(){
	setThisReplyListHtml();
}
function initReplyEvents(){
}
//--- direct event functions
function writeReply(){
	var bdrContentString = '';
	//---
	bdrContentString = replyObjects.replyContentObject.value;
	//---
	if(bdrContentString===''){
		alert('댓글 내용을 입력 해주세요.');
		replyObjects.replyContentObject.focus();
		return;
	}//if
	//---
	if(confirm('댓글을 등록 하시겠습니까?')){
		insertReplyProc(bdrContentString,function(data){
			//console.info('writeReply : data : ',data);
			replyObjects.replyContentObject.value = '';
			setThisReplyListHtml();
		});
	}//if
}
function cancelReply(){
	if(confirm('댓글 작성을 취소 하시겠습니까?')){
		replyObjects.replyContentObject.value = '';
	}//if
}
function modifyReplyForm(bdrSeq=''){
	var replyItemViewJqueryObject = $('#replyItemView'+bdrSeq);
	var replyItemEditJqueryObject = $('#replyItemEdit'+bdrSeq);
	//---
	replyItemViewJqueryObject.hide();
	replyItemEditJqueryObject.show();
}
function modifyReply(bdrSeq=''){
	var replyItemEditTextJqueryObject = $('#replyItemEditText'+bdrSeq);
	var bdrContentString = '';
	//---
	bdrContentString = replyItemEditTextJqueryObject.val();
	//---
	if(confirm('댓글을 수정 하시겠습니까?')){
		updateReplyProc(bdrSeq,bdrContentString,function(data){
			//console.info('modifyReply : data : ',data);
			setThisReplyListHtml();
		});
	}//if
}
function cancelModifyReplyForm(bdrSeq=''){
	var replyItemViewJqueryObject = $('#replyItemView'+bdrSeq);
	var replyItemEditJqueryObject = $('#replyItemEdit'+bdrSeq);
	//---
	if(confirm('댓글 수정을 취소 하시겠습니까?')){
		replyItemEditJqueryObject.hide();
		replyItemViewJqueryObject.show();
	}//if
}
function deleteReply(bdrSeq=''){
	if(confirm('댓글을 삭제 하시겠습니까?')){
		deleteReplyProc(bdrSeq,function(data){
			//console.info('deleteReply : data : ',data);
			setThisReplyListHtml();
		});
	}//if
}
function fixReply(bdrSeq='',currentBdrFixYN='N'){
	var editBdrFixYN = 'N';
	//---
	editBdrFixYN = currentBdrFixYN==='Y' ? 'N' : 'Y';
	//---
	if(confirm('댓글을 고정/고정해제 하시겠습니까?')){
		fixReplyProc(bdrSeq,editBdrFixYN,function(data){
			//console.info('deleteReply : data : ',data);
			setThisReplyListHtml();
		});
	}//if
}
//--- etc functions
function getReplyList(callbackFunc=null){
	var paramsObject = {};
	//---
	paramsObject.actionString = 'GET_REPLY_LIST';
	paramsObject.bdaSeq = replyObjects.settingsObject.bdaSeq;
	//---
	$.post(replyObjects.settingsObject.apiUrl,paramsObject,function(data){
		//console.info('getReplyList : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
}
function setThisReplyListHtml(){
	getReplyList(function(data){
		var listData = data ? data.data.listData : null;
		setReplyListHtml(listData);
	});
}
function setReplyListHtml(listData=null){
	if(listData){
		var replyListHtmlArray = [];
		$.each(listData,function(index,itemDataObject){
			replyListHtmlArray.push(getReplyItemHtml(itemDataObject));
		});
		replyObjects.replyItemAreaJqueryObject.html(replyListHtmlArray.join('\n'));
	}//if
}
function getReplyItemHtml(rowData=null){
	var replyItemString = '';
	var bdrContentString = '';
	//---
	if(rowData){
		bdrContentString = getDecodeHtmlString(getNvlString(rowData.bdr_content));
		//---
		replyItemString = replyItemTemplateString;
		replyItemString = replyItemString.replaceAll('{{bdrSeq}}',$.trim(rowData.bdr_seq));
		replyItemString = replyItemString.replaceAll('{{replyDatetime}}',$.trim(rowData.regdatetime_str));
		if(rowData.list_bdr_fix_yn==='Y'){
			replyItemString = replyItemString.replaceAll('{{replyContent}}','<span style="color:blue;">[고정]</span> '+bdrContentString);
		}else{
			replyItemString = replyItemString.replaceAll('{{replyContent}}',bdrContentString);
		}//if
		replyItemString = replyItemString.replaceAll('{{orgReplyContent}}',getNvlString(rowData.bdr_content));
		replyItemString = replyItemString.replaceAll('{{bdrFixYN}}',$.trim(rowData.bdr_fix_yn));
	}//if
	return replyItemString;
}
function getReplyRow(bdrSeq='',callbackFunc=null){
	var paramsObject = {};
	//---
	paramsObject.actionString = 'GET_REPLY_ROW';
	paramsObject.bdrSeq = bdrSeq;
	//---
	$.post(replyObjects.settingsObject.apiUrl,paramsObject,function(data){
		//console.info('getReplyRow : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
}
function insertReplyProc(bdrContent='',callbackFunc=null){
	var paramsObject = {};
	//---
	paramsObject.actionString = 'INSERT_REPLY_DATA';
	paramsObject.bdaSeq = replyObjects.settingsObject.bdaSeq;
	paramsObject.bdrContent = bdrContent;
	//---
	$.post(replyObjects.settingsObject.apiUrl,paramsObject,function(data){
		//console.info('insertReplyProc : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
}
function updateReplyProc(bdrSeq='',bdrContent='',callbackFunc=null){
	var paramsObject = {};
	//---
	paramsObject.actionString = 'UPDATE_REPLY_DATA';
	paramsObject.bdrSeq = bdrSeq;
	paramsObject.bdrContent = bdrContent;
	//---
	$.post(replyObjects.settingsObject.apiUrl,paramsObject,function(data){
		//console.info('updateReplyProc : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
}
function deleteReplyProc(bdrSeq='',callbackFunc=null){
	var paramsObject = {};
	//---
	paramsObject.actionString = 'DELETE_REPLY_DATA';
	paramsObject.bdrSeq = bdrSeq;
	//---
	$.post(replyObjects.settingsObject.apiUrl,paramsObject,function(data){
		//console.info('deleteReplyProc : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
}
function fixReplyProc(bdrSeq='',bdrFixYN='N',callbackFunc=null){
	var paramsObject = {};
	//---
	paramsObject.actionString = 'FIX_REPLY_DATA';
	paramsObject.bdrSeq = bdrSeq;
	paramsObject.bdrFixYN = bdrFixYN;
	//---
	$.post(replyObjects.settingsObject.apiUrl,paramsObject,function(data){
		//console.info('deleteReplyProc : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
}