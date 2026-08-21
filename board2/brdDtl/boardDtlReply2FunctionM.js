/*
//1차 댓글 항목별 html 셋팅시마다 초기화
$(function(){
	var reply2Object = new Reply2Class(1,1);
	reply2Object.setReply2Objects();
	reply2Object.initReply2();
	reply2Object.initReply2Events();
});
*/
//---
var g_reply2ObjectList = [];
function Reply2Class(bdaSeq=null,bdrSeq=null) {
	var thisObject = this;
	//---
	thisObject.bdaSeq = bdaSeq;
	thisObject.bdrSeq = bdrSeq;
	thisObject.bdrSeqId = Number(thisObject.bdrSeq).toString();
	//---
	thisObject.settingsInfo = {};
	thisObject.reply2Objects = {};
	//---
	var bdrSeqString = '';
	//---
	bdrSeqString = Number(bdrSeq).toString();
	//---
	if(!bdrSeq){
		console.info('Reply2Class constructor function error : ', 'bdrSeq value is null.');
		return;
	}//if
	//---
	g_reply2ObjectList[bdrSeqString] = this;
}
//--- init functions
Reply2Class.prototype.setReply2Objects = function(){
	var thisObject = this;
	var paramFormObject = document.paramForm;
	//---  
	thisObject.reply2ListItemAreaJqueryObject = $('#reply2ListItemOutArea'+thisObject.bdrSeq);
	thisObject.reply2Objects.reply2ContentObject = document.getElementById('reply2Content'+thisObject.bdrSeq);
	thisObject.reply2Objects.reply2ContentJqueryObject = $(thisObject.reply2Objects.reply2ContentObject);
	thisObject.reply2Objects.reply2ListAreaJqueryObject = $('.reply2-list-area-class',thisObject.reply2ListItemAreaJqueryObject).eq(0);
};
Reply2Class.prototype.initReply2 = function(){
	var thisObject = this;
	//---
	thisObject.settingsInfo.apiUrl = 'boardDtlReply2Api.php';
	thisObject.settingsInfo.bdaSeq = thisObject.bdaSeq;
	thisObject.settingsInfo.bdrSeq = thisObject.bdrSeq;
	//---
	thisObject.setThisReply2ListHtml();
};
Reply2Class.prototype.initReply2Events = function(){
};
//--- direct event functions
Reply2Class.prototype.writeReply2 = function(){
	var thisObject = this;
	var bdr2ContentString = '';
	//---
	bdr2ContentString = thisObject.reply2Objects.reply2ContentObject.value;
	//---
	if(bdr2ContentString===''){
		alert('하위댓글 내용을 입력 해주세요.');
		thisObject.reply2Objects.reply2ContentObject.focus();
		return;
	}//if
	//---
	if(confirm('하위댓글을 등록 하시겠습니까?')){
		thisObject.insertReply2Proc(bdr2ContentString,function(data){
			//console.info('writeReply2 : data : ',data);
			thisObject.reply2Objects.reply2ContentObject.value = '';
			thisObject.setThisReply2ListHtml();
		});
	}//if
};
Reply2Class.prototype.cancelReply2 = function(){
	var thisObject = this;
	if(confirm('하위댓글 작성을 취소 하시겠습니까?')){
		thisObject.reply2Objects.reply2ContentObject.value = '';
	}//if
};
Reply2Class.prototype.modifyReply2Form = function(bdr2Seq='',bdr2SeqId=''){
	var thisObject = this;
	var reply2ItemViewJqueryObject = $('#reply2ItemView'+bdr2SeqId);
	var reply2ItemEditJqueryObject = $('#reply2ItemEdit'+bdr2SeqId);
	//---
	reply2ItemViewJqueryObject.hide();
	reply2ItemEditJqueryObject.show();
};
Reply2Class.prototype.modifyReply2 = function(bdr2Seq='',bdr2SeqId=''){
	var thisObject = this;
	var reply2ItemEditTextJqueryObject = $('#reply2ItemEditText'+bdr2SeqId);
	var bdr2ContentString = '';
	//---
	bdr2ContentString = reply2ItemEditTextJqueryObject.val();
	//---
	if(confirm('하위댓글을 수정 하시겠습니까?')){
		thisObject.updateReply2Proc(bdr2Seq,bdr2ContentString,function(data){
			//console.info('modifyReply2 : data : ',data);
			thisObject.setThisReply2ListHtml();
		});
	}//if
};
Reply2Class.prototype.cancelModifyReply2Form = function(bdr2Seq='',bdr2SeqId=''){
	var thisObject = this;
	var reply2ItemViewJqueryObject = $('#reply2ItemView'+bdr2SeqId);
	var reply2ItemEditJqueryObject = $('#reply2ItemEdit'+bdr2SeqId);
	//---
	if(confirm('하위댓글 수정을 취소 하시겠습니까?')){
		reply2ItemEditJqueryObject.hide();
		reply2ItemViewJqueryObject.show();
	}//if
};
Reply2Class.prototype.deleteReply2 = function(bdr2Seq='',bdr2SeqId=''){
	var thisObject = this;
	//---
	if(confirm('하위댓글을 삭제 하시겠습니까?')){
		thisObject.deleteReply2Proc(bdr2Seq,function(data){
			//console.info('deleteReply2 : data : ',data);
			thisObject.setThisReply2ListHtml();
		});
	}//if
};
Reply2Class.prototype.fixReply2 = function(bdr2Seq='',currentBdrFixYN='N',bdr2SeqId=''){
	var thisObject = this;
	var editBdr2FixYN = 'N';
	//---
	editBdr2FixYN = currentBdrFixYN==='Y' ? 'N' : 'Y';
	//---
	if(confirm('하위댓글을 고정/고정해제 하시겠습니까?')){
		thisObject.fixReply2Proc(bdr2Seq,editBdr2FixYN,function(data){
			//console.info('deleteReply2 : data : ',data);
			thisObject.setThisReply2ListHtml();
		});
	}//if
};
//--- etc functions
Reply2Class.prototype.getReply2List = function(bdrSeq=null,callbackFunc=null){
	var thisObject = this;
	var paramsObject = {};
	//---
	paramsObject.actionString = 'GET_REPLY2_LIST';
	paramsObject.bdaSeq = thisObject.settingsInfo.bdaSeq;
	paramsObject.bdrSeq = bdrSeq;
	//---
	if(!bdrSeq){return;}//if
	//---
	$.post(thisObject.settingsInfo.apiUrl,paramsObject,function(data){
		//console.info('getReply2List : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
};
Reply2Class.prototype.setThisReply2ListHtml = function(){
	var thisObject = this;
	//---
	thisObject.getReply2List(thisObject.bdrSeq,function(data){
		var listData = data ? data.data.listData : null;
		thisObject.setReply2ListHtml(listData);
	});
};
Reply2Class.prototype.setReply2ListHtml = function(listData=null){
	var thisObject = this;
	//---
	if(listData){
		var reply2ListHtmlArray = [];
		$.each(listData,function(index,itemDataObject){
			reply2ListHtmlArray.push(thisObject.getReply2ItemHtml(itemDataObject));
		});
		thisObject.reply2Objects.reply2ListAreaJqueryObject.html(reply2ListHtmlArray.join('\n'));
		fnCmnBotReplaceSpcCharForElement(thisObject.reply2Objects.reply2ListAreaJqueryObject);
	}//if
};
Reply2Class.prototype.getReply2ItemHtml = function(rowData=null){
	var thisObject = this;
	var reply2ItemString = '';
	var bdr2ContentString = '';
	//---
	if(rowData){
		bdr2ContentString = getDecodeHtmlString(getNvlString(rowData.bdr2_content));
		bdr2ContentString = getLinkContentHtmlString(bdr2ContentString);
		//---
		reply2ItemString = g_reply2ItemTemplateString;
		reply2ItemString = reply2ItemString.replaceAll('{{bdr2Seq}}',$.trim(rowData.bdr2_seq));
		reply2ItemString = reply2ItemString.replaceAll('{{reply2Datetime}}',$.trim(rowData.regdatetime_str));
		if(rowData.list_bdr2_fix_yn==='Y'){
			reply2ItemString = reply2ItemString.replaceAll('{{reply2Content}}','<span style="color:blue;">[고정]</span> '+bdr2ContentString);
			reply2ItemString = reply2ItemString.replaceAll('{{bdr2SeqId}}',$.trim(rowData.bdr2_seq)+'Fix');
		}else{
			reply2ItemString = reply2ItemString.replaceAll('{{reply2Content}}',bdr2ContentString);
			reply2ItemString = reply2ItemString.replaceAll('{{bdr2SeqId}}',$.trim(rowData.bdr2_seq));
		}//if
		reply2ItemString = reply2ItemString.replaceAll('{{orgReply2Content}}',getNvlString(rowData.bdr2_content));
		reply2ItemString = reply2ItemString.replaceAll('{{bdr2FixYN}}',$.trim(rowData.bdr2_fix_yn));
		reply2ItemString = reply2ItemString.replaceAll('{{bdrSeqId}}',$.trim(thisObject.bdrSeqId));
	}//if
	//---
	return reply2ItemString;
};
Reply2Class.prototype.getReply2Row = function(bdr2Seq='',callbackFunc=null){
	var thisObject = this;
	var paramsObject = {};
	//---
	paramsObject.actionString = 'GET_REPLY2_ROW';
	paramsObject.bdr2Seq = bdr2Seq;
	//---
	$.post(thisObject.settingsInfo.apiUrl,paramsObject,function(data){
		//console.info('getReply2Row : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
};
Reply2Class.prototype.insertReply2Proc = function(bdr2Content='',callbackFunc=null){
	var thisObject = this;
	var paramsObject = {};
	//---
	paramsObject.actionString = 'INSERT_REPLY2_DATA';
	paramsObject.bdaSeq = thisObject.settingsInfo.bdaSeq;
	paramsObject.bdrSeq = thisObject.settingsInfo.bdrSeq;
	paramsObject.bdr2Content = bdr2Content;
	//---
	$.post(thisObject.settingsInfo.apiUrl,paramsObject,function(data){
		//console.info('insertReply2Proc : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
};
Reply2Class.prototype.updateReply2Proc = function(bdr2Seq='',bdr2Content='',callbackFunc=null){
	var thisObject = this;
	var paramsObject = {};
	//---
	paramsObject.actionString = 'UPDATE_REPLY2_DATA';
	paramsObject.bdaSeq = thisObject.settingsInfo.bdaSeq;
	paramsObject.bdrSeq = thisObject.settingsInfo.bdrSeq;
	paramsObject.bdr2Seq = bdr2Seq;
	paramsObject.bdr2Content = bdr2Content;
	//---
	$.post(thisObject.settingsInfo.apiUrl,paramsObject,function(data){
		//console.info('updateReply2Proc : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
};
Reply2Class.prototype.deleteReply2Proc = function(bdr2Seq='',callbackFunc=null){
	var thisObject = this;
	var paramsObject = {};
	//---
	paramsObject.actionString = 'DELETE_REPLY2_DATA';
	paramsObject.bdaSeq = thisObject.settingsInfo.bdaSeq;
	paramsObject.bdrSeq = thisObject.settingsInfo.bdrSeq;
	paramsObject.bdr2Seq = bdr2Seq;
	//---
	$.post(thisObject.settingsInfo.apiUrl,paramsObject,function(data){
		//console.info('deleteReply2Proc : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
};
Reply2Class.prototype.fixReply2Proc = function(bdr2Seq='',bdr2FixYN='N',callbackFunc=null){
	var thisObject = this;
	var paramsObject = {};
	//---
	paramsObject.actionString = 'FIX_REPLY2_DATA';
	paramsObject.bdaSeq = thisObject.settingsInfo.bdaSeq;
	paramsObject.bdrSeq = thisObject.settingsInfo.bdrSeq;
	paramsObject.bdr2Seq = bdr2Seq;
	paramsObject.bdr2FixYN = bdr2FixYN;
	//---
	$.post(thisObject.settingsInfo.apiUrl,paramsObject,function(data){
		//console.info('deleteReply2Proc : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
};