function getDecodeHtmlString(stringValue=''){
	var returnString = '';
	var editStringValue = ''
	//---
	if(stringValue && 'string'===typeof stringValue){
		editStringValue = stringValue;
		editStringValue = editStringValue.replaceAll('\r\n','\n');
		editStringValue = editStringValue.replaceAll('\n','<br />');
		editStringValue = editStringValue.replaceAll('\t','&nbsp;&nbsp;&nbsp;&nbsp;');
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
			var domainName = '';
			var domainInfoObject = null;
			//---
			editUrlString = $.trim(url).substring(0,200);
			domainInfoObject = getDomainAndHost(editUrlString);
			if(domainInfoObject===null){return true;}//if
			domainName = $.trim(domainInfoObject.hostname);
			if(debugFlag){console.info('editUrlString : ',editUrlString);}//if
			if(debugFlag){console.info('domainName : ',domainName);}//if
			//---
			$(allowedHostsArray).each(function(index,allowedHostString){
				if(debugFlag){console.info('allowedHostString({{index}}) : ',allowedHostString);}//if
				if(allowedHostString!==undefined && allowedHostString!==null){
					if(debugFlag){console.info('containsIgnoreCaseRegex(domainName,allowedHostString) : ',containsIgnoreCaseRegex(domainName,allowedHostString));}//if
					if(debugFlag){console.info('domainName : ',domainName);}//if
					if(debugFlag){console.info('allowedHostString : ',allowedHostString);}//if
					if(containsIgnoreCaseRegex(domainName,allowedHostString)){
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
	var partArrayObjectOfAllowedUrlsArray = null;
	var substringsArrayOfAllowedUrlsArray = null;
	var nonSubstringsArrayOfAllowedUrlsArray = null;
	//---
	if(debugFlag){console.info('===== getAllowedUrlLinkHtmlString start');}//if
	if(contentString===undefined || contentString===null){return failReturnString;}//if
	if(allowedUrlsArray===undefined || allowedUrlsArray===null || !allowedUrlsArray.length){return contentString;}//if
	//--- allowedUrlsArray, 배열 중복 제거 처리
	allowedUrlsArray = removeDupOfArray(allowedUrlsArray);
	//--- allowedUrlsArray, 배열 항목, 긴 문자열 길이 기준으로, 긴문자열 상위 위치, 배열 정렬 처리
	allowedUrlsArray = sortAscByStringLengthOfArray(allowedUrlsArray);
	//--- allowedUrlsArray, 배열에서 포함문자열 항목 배열과, 비포함문자열 항목 배열로 분리
	partArrayObjectOfAllowedUrlsArray = splitBySubstringOfArray(allowedUrlsArray);
	substringsArrayOfAllowedUrlsArray = partArrayObjectOfAllowedUrlsArray.substrings;
	nonSubstringsArrayOfAllowedUrlsArray = partArrayObjectOfAllowedUrlsArray.nonSubstrings;
	//--- 
	editContentString = contentString;
	if(debugFlag){console.info('contentString : ',contentString);}//if
	if(debugFlag){console.info('editContentString : ',editContentString);}//if
	//--- allowedUrlsArray, 비포함문자열 항목 배열 처리
	$(nonSubstringsArrayOfAllowedUrlsArray).each(function(index,allowedUrlString){
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
	//--- allowedUrlsArray, 포함문자열 항목 배열 처리
	$(substringsArrayOfAllowedUrlsArray).each(function(index,allowedUrlString){
		if(allowedUrlString!==undefined && allowedUrlString!==null){
			editAllowedUrlString = allowedUrlString.substring(0,ALLOW_URL_MAX_LENGTH_CONST);
			//---
			allowedUrlLinkHtmlString = '<a href="editAllowedUrlString" target="_blank">editAllowedUrlString</a>';
			allowedUrlLinkHtmlString = allowedUrlLinkHtmlString.replaceAll('editAllowedUrlString',editAllowedUrlString);
			allowedUrlLinkHtmlString = allowedUrlLinkHtmlString.replaceAll('〓','=');
			//---
			editContentString = editContentString.replaceAll(editAllowedUrlString+' ',allowedUrlLinkHtmlString+' ');
			editContentString = editContentString.replaceAll(editAllowedUrlString+'\t',allowedUrlLinkHtmlString+'\t');
			editContentString = editContentString.replaceAll(editAllowedUrlString+'\n',allowedUrlLinkHtmlString+'\n');
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
//const url = "https://modern3080.mycafe24.com/board2/brdDtl/boardDtlView.php?bdSeq=10";
//console.log(getDomainAndHost(url)); 
//결과: { hostname: "modern3080.mycafe24.com", domain: "modern3080.mycafe24.com" }
function getDomainAndHost(url='') {
	if($.trim(url)===''){return null;}//if
	if(url.toLowerCase().indexOf('http')===-1){return null;}//if
	//---
	try {
		const parsedUrl = new URL(url);
		return {
			hostname: parsedUrl.hostname, // 호스트명 (예: modern3080.mycafe24.com)
			domain: parsedUrl.host        // 도메인 + 포트 (예: modern3080.mycafe24.com:80)
		};
	} catch (e) {
		console.error("Invalid URL:", e);
		return null;
	}
}
//const arr = [1, 2, 2, 3, 4, 4, 5];
//removeDupOfArray(arr);
function removeDupOfArray(arr=null){
	if(!arr){return arr;}//if
	if(!arr.length){return arr;}//if
	//---
	const uniqueArr = arr.filter(
		(item, index) => arr.indexOf(item) === index
	);
	return uniqueArr;
}
/** 
 * 문자열 배열을 길이 기준으로 내림차순 정렬하는 함수 
 * @param {string[]} arr - 문자열 배열 
 * @returns {string[]} - 길이가 긴 문자열이 앞에 오는 정렬된 배열 
 * 사용 예시 
 * const data = ["apple", "banana", "kiwi", "watermelon", "grape"]; 
 * const sorted = sortDescByStringLengthOfArray(data);
 */
function sortDescByStringLengthOfArray(arr=null) {
	if(!arr){return arr;}//if
	if(!arr.length){return arr;}//if
	//---
	return arr.sort((a, b) => b.length - a.length); 
}
/** 
 * 문자열 배열을 길이 기준으로 오름차순 정렬하는 함수 
 * @param {string[]} arr - 문자열 배열 
 * @returns {string[]} - 길이가 긴 문자열이 뒤에 오는 정렬된 배열 
 * 사용 예시 
 * const data = ["apple", "banana", "kiwi", "watermelon", "grape"]; 
 * const sorted = sortAscByStringLengthOfArray(data);
 */
function sortAscByStringLengthOfArray(arr=null) {
	if(!arr){return arr;}//if
	if(!arr.length){return arr;}//if
	//---
	return arr.sort((a, b) => a.length - b.length); 
}
/**
 * 설명
 - 배열에서, 항목중에서, 다른 항목들의 부분문자열인 항목을 따로 배열로 분리하고, 
 - 그외에 아닌 항목들을 또 다른 배열로 분리해서, 반환하는 자바스크립트 함수
 * 사용 예시
 - const data = ["apple", "app", "banana", "nan", "orange", "ora"];
 - const result = splitBySubstringOfArray(data);
 - console.log("부분문자열 배열:", result.substrings); 
 - console.log("비부분문자열 배열:", result.nonSubstrings); 
 */
function splitBySubstringOfArray(arr) {
	const substrings = [];
	const nonSubstrings = [];
	//---
	if(!arr){return {};}//if
	if(!arr.length){return {};}//if
	//---
	arr.forEach((item, index) => {
		//다른 항목들 중 하나라도 item을 포함하면 부분문자열로 분류
		const isSubstring = arr.some((other, i) => i !== index && other.includes(item));
		//---
		if (isSubstring) {
			substrings.push(item);
		} else {
			nonSubstrings.push(item);
		}//if
	});
	//---
	return { substrings, nonSubstrings };
}