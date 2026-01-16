function getDecodeHtmlString(stringValue=''){
	var returnString = '';
	var editStringValue = ''
	//---
	if(stringValue && 'string'===typeof stringValue){
		editStringValue = stringValue;
		editStringValue = editStringValue.replaceAll('\r\n','\n');
		editStringValue = editStringValue.replaceAll('\n','<br />');
	}//if
	//---
	returnString = editStringValue;
	return returnString;
}
function getNvlString(stringValue='',defaultString=''){
	var returnString = '';
	var editStringValue = ''
	//---
	editStringValue = stringValue;
	if(editStringValue===null || editStringValue===undefined || editStringValue==="" || 'string'!==typeof stringValue){
		editStringValue = defaultString;
	}//if
	//---
	returnString = editStringValue;
	return returnString;
}
function getNvlString2(stringValue='',stringReplaceValue=''){
	var returnString = '';
	//---
	stringReplaceValue = String(stringReplaceValue);
	//---
	if(stringValue===null || stringValue===undefined){
		returnString = stringReplaceValue;
	}else if('string'===typeof stringValue && stringValue===''){
		returnString = stringReplaceValue;
	}else if('string'===typeof stringValue){
		returnString = stringValue;
	}else if('number'===typeof stringValue){
		returnString = String(stringValue);
	}else if('object'!==typeof stringValue){
		returnString = '';
	}else{
		returnString = '';
	}
	//---
	return returnString;
}
function setSearchEnter(targetJqueryObject=null,fnSearchCb=null){
	if(targetJqueryObject){
		targetJqueryObject.keydown(function(e){
			if(e.key==='Enter'){
				if($.isFunction(fnSearchCb)){
					fnSearchCb();
				}//if
			}//if
		});
	}//if
}
function setSearchDatepicker(targetJqueryObject=null){
	if(targetJqueryObject){
		targetJqueryObject.datepicker({
			dateFormat: "yy-mm-dd",   // 날짜 형식 (예: 2025-12-26)
			showAnim: "fadeIn",       // 애니메이션 효과
			changeMonth: true,        // 월 선택 가능
			changeYear: true,         // 연도 선택 가능
			//yearRange: "2000:2030",   // 연도 범위
			//minDate: "-1M",           // 오늘 기준 한 달 전까지 선택 가능
			//maxDate: "+1Y",           // 오늘 기준 1년 후까지 선택 가능
			showButtonPanel: true     // 오늘/닫기 버튼 표시
		});
	}//if
}
function getAllowedUrlsArrayOfContent(contentString='',allowedHostsArray=null,debugFlag=false){
	var returnArray = null;
	var failReturnArray = [];
	var urlRegex = null;
	var extractedUrls = null;
	var filteredUrls = null;
	//---
	if(debugFlag){console.info('===== getAllowedUrlsArrayOfContent start');}//if
	if(contentString===undefined || contentString===null){return failReturnArray;}//if
	if(allowedHostsArray===undefined || allowedHostsArray===null || !allowedHostsArray.length){return failReturnArray;}//if
	//---
	urlRegex = /(https?:\/\/[^ ^\t^\r^\n^<^>^\(^\)^\{^\}^\[^\]]+)/g;
	extractedUrls = contentString.match(urlRegex) || [];
	if(debugFlag){console.info('extractedUrls : ',extractedUrls);}//if
	if(debugFlag){console.info('allowedHostsArray : ',allowedHostsArray);}//if
	//---
	filteredUrls = extractedUrls.filter(url => {
		try {
			//var host = new URL(url).hostname;
			var editUrlString = '';
			var isContains = false;
			//---
			editUrlString = $.trim(url).substring(0,200);
			if(debugFlag){console.info('editUrlString : ',editUrlString);}//if
			//---
			$(allowedHostsArray).each(function(index,allowedHostString){
				if(debugFlag){console.info('allowedHostString({{index}}) : ',allowedHostString);}//if
				if(allowedHostString!==undefined && allowedHostString!==null){
					if(debugFlag){console.info('containsIgnoreCaseRegex(editUrlString,allowedHostString) : ',containsIgnoreCaseRegex(editUrlString,allowedHostString));}//if
					if(containsIgnoreCaseRegex(editUrlString,allowedHostString)){
						isContains = true;
						return false;
					}//if
				}//if
			});
			//---
			return isContains;
		} catch (e) {
			return false; // URL 파싱 실패 시 제외
		}
	});
	if(debugFlag){console.info('filteredUrls : ',filteredUrls);}//if
	//---
	returnArray = filteredUrls || [];
	if(debugFlag){console.info('returnArray : ',returnArray);}//if
	if(debugFlag){console.info('===== getAllowedUrlsArrayOfContent end');}//if
	return returnArray;
}
function getAllowedUrlLinkHtmlString(contentString='',allowedUrlsArray=null,debugFlag=false){
	const ALLOW_URL_MAX_LENGTH_CONST = 2000;
	//---
	var returnString = '';
	var failReturnString = '';
	var editContentString = '';
	var allowedUrlLinkHtmlString = '';
	var editAllowedUrlString = '';
	//---
	if(debugFlag){console.info('===== getAllowedUrlLinkHtmlString start');}//if
	if(contentString===undefined || contentString===null){return failReturnString;}//if
	if(allowedUrlsArray===undefined || allowedUrlsArray===null || !allowedUrlsArray.length){return contentString;}//if
	//---
	editContentString = contentString;
	if(debugFlag){console.info('contentString : ',contentString);}//if
	if(debugFlag){console.info('editContentString : ',editContentString);}//if
	$(allowedUrlsArray).each(function(index,allowedUrlString){
		if(allowedUrlString!==undefined && allowedUrlString!==null){
			editAllowedUrlString = allowedUrlString.substring(0,ALLOW_URL_MAX_LENGTH_CONST);
			//---
			allowedUrlLinkHtmlString = '<a href="editAllowedUrlString" target="_blank">editAllowedUrlString</a>';
			allowedUrlLinkHtmlString = allowedUrlLinkHtmlString.replaceAll('editAllowedUrlString',editAllowedUrlString);
			allowedUrlLinkHtmlString = allowedUrlLinkHtmlString.replaceAll('〓','=');
			//---
			editContentString = editContentString.replaceAll(editAllowedUrlString,allowedUrlLinkHtmlString);
		}//if
	});
	//---
	returnString = editContentString;
	if(debugFlag){console.info('returnString : ',returnString);}//if
	if(debugFlag){console.info('===== getAllowedUrlLinkHtmlString end');}//if
	return returnString;
} 
function containsIgnoreCaseRegex(str,search){
	const regex = new RegExp(search,"i");
	return regex.test(str);
}
function getCurrentLineOfTextarea(textareaJqueryObject=null) {
	if(!textareaJqueryObject){return '';}//if
	//---
	const text = textareaJqueryObject.val();
	const cursorPos = textareaJqueryObject[0].selectionStart;
	// 커서 이전에서 가장 가까운 줄바꿈 위치 찾기
	const start = text.lastIndexOf("\n", cursorPos - 1) + 1;
	// 커서 이후에서 가장 가까운 줄바꿈 위치 찾기
	let end = text.indexOf("\n", cursorPos);
	if (end === -1) end = text.length; // 마지막 줄 처리
	// 현재 행 추출
	return text.substring(start,end);
}