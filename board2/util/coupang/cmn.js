/*
=========================
* 날짜 함수의 월 관련, 착각하기 쉬운 내용.
- new Date(year, month, 0) : month 값은 그냥 현재월 - 1 값
- (new Date()).getMonth() : 현재월보다 -1 값을 반환
*/
function isMobile(){
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;
	if(location.href.indexOf('M.php')!==-1){return true;}//if
    // 주요 모바일 기기 키워드 체크
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(userAgent);
}
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
	if(!isMobile() && targetJqueryObject){
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
function fnCmnBotReplaceSpcCharForElement(elJqueryObject=null){
	var htmlString = '';
	var editHtmlString = '';
	//---
	if(elJqueryObject){
		//---
		htmlString = elJqueryObject.html();
		//---
		editHtmlString = htmlString;
		editHtmlString = editHtmlString.replaceAll('〓','=');
		//---
		elJqueryObject.html(editHtmlString);
	}//if
}
//return : '20260612'
//month : 현재월보다 -1 값
function getDateString(year=0,month=0,day=0){
	var returnValue = '';
	var formatDateStringArray = [];
	var yearFormatDateString = '';
	var monthFormatDateString = '';
	var dayFormatDateString = '';
	//---
	year=Number(year);
	month=Number(month) + 1;
	day=Number(day);
	//---
	yearFormatDateString = (year).toString().padStart(2, '0');
	monthFormatDateString = (month).toString().padStart(2, '0');
	dayFormatDateString = (day).toString().padStart(2, '0');
	//---
	formatDateStringArray.push(yearFormatDateString);
	formatDateStringArray.push(monthFormatDateString);
	formatDateStringArray.push(dayFormatDateString);
	//---
	returnValue = formatDateStringArray.join('');
	return returnValue;
}
//return : 현재 일자의 '20260612' 형식 일자문자열 반환
function getTodayDateString(){
	var today = new Date();
	//---
	return getDateString(today.getFullYear(),today.getMonth(),today.getDate());
}
//return : 특정 날짜객체의 '20260612' 형식 일자문자열 반환
function getDateStringOfDateObject(dateObject=null){
	if(!dateObject){return '';}//if
	//---
	return getDateString(dateObject.getFullYear(),dateObject.getMonth(),dateObject.getDate());
}
//특정 년월의 마지막 일의 요일 문자열
//return value : 수요일
//month : 현재월 값
function getLastDayOfWeekStr(year=0, month=0) {
	year = Number(year);
	month = Number(month);
	//---
    const lastDate = new Date(year, month, 0);
    // 'ko-KR'을 지정하고 weekday 옵션을 'long'으로 주면 완전한 요일 텍스트를 반환합니다.
    return lastDate.toLocaleDateString('ko-KR', { weekday: 'long' });
}
//특정 년월의 마지막 일의 요일 번호
//return value :: 0: 일, 1: 월, 2: 화, 3: 수, 4: 목, 5: 금, 6: 토
//month : 현재월 값
function getLastDayOfWeekNum(year=0, month=0) {
	year = Number(year);
	month = Number(month);
	//---
    // month를 그대로 넣으면 자동으로 '해당 월의 마지막 날' 객체가 생성됩니다.
    const lastDate = new Date(year, month, 0); 
    // 0: 일, 1: 월, 2: 화, 3: 수, 4: 목, 5: 금, 6: 토
    return lastDate.getDay(); 
}
//return value : 일년중에서 해당년월의 주번호 반환
//month : 현재 월에서 -1 값
function getMonthWeekNumberOfYearByNumber(year=0, month=0) {
	let dateStr = '';
	let yearStr = '';
	let monthStr = '';
	//---
	year = Number(year);
	month = Number(month) + 1;
	//---
	yearStr = (year).toString();
	monthStr = (month).toString().padStart(2, '0');
	//---
	dateStr = yearStr+monthStr+'01';
	return getWeekNumberOfYear(dateStr);
}
//yearMonStr : '202601'
//month : 현재월 값
//return value : 일년중에서 해당년월의 주번호 반환
function getMonthWeekNumberOfYear(yearMonStr='') {
	if(!yearMonStr){return 0;}//if
	//---
	let dateStr = '';
	//---
	dateStr = yearMonStr.substring(0,6)+'01';
	return getWeekNumberOfYear(dateStr);
}
//return value : 일년중에서 해당일자의 주번호 반환
//month : 현재월에서 -1 값
function getWeekNumberOfYearByNumber(year=0, month=0, day=0) {
	let dateStr = '';
	let yearStr = '';
	let monthStr = '';
	let dayStr = '';
	//---
	year = Number(year);
	month = Number(month) + 1;
	day = Number(day);
	//---
	yearStr = (year).toString();
	monthStr = (month).toString().padStart(2, '0');
	dayStr = (day).toString().padStart(2, '0');
	//---
	dateStr = yearStr+monthStr+dayStr;
	return getWeekNumberOfYear(dateStr);
}
//dateStr : '20260101'
//month : 현재월 값
//return value : 일년중에서 해당일자의 주번호 반환
function getWeekNumberOfYear(dateString='') {
    if(!dateString){return 0;}//if

  // 문자열을 자 잘라서 숫자형으로 변환
  const year = parseInt(dateString.substring(0, 4), 10);
  const month = parseInt(dateString.substring(4, 6), 10) - 1;
  const day = parseInt(dateString.substring(6, 8), 10);

  // 1. 입력받은 날짜로 Date 객체 생성 (시간은 표준화)
  const target = new Date(year, month, day);
  
  // 2. 해당 날짜의 요일을 구함 (일요일: 0, 월요일: 1, ..., 토요일: 6)
  // ISO 기준(월요일 시작)으로 정렬하기 위해 일요일을 7로 변경
  const dayNum = target.getDay() || 7;
  
  // 3. 해당 주의 목요일 날짜로 이동
  target.setDate(target.getDate() + 4 - dayNum);
  
  // 4. 해당 연도의 1월 1일 설정
  const yearStart = new Date(target.getFullYear(), 0, 1);
  
  // 5. 1월 1일과 목요일 간의 일수 차이 계산 후 주차 도출
  const weekNo = Math.ceil((((target - yearStart) / 86400000) + 1) / 7);
  
  /*
  return {
    year: target.getFullYear(),
    week: weekNo
  };
  */
  return weekNo;
}
// return value : 특정 년월의 마지막 일자 날짜일 반환
function getLastDateOfMonth(year=0, month=0) {
	year = Number(year);
	month = Number(month);
	//---
	const lastDate = new Date(year, month, 0).getDate();
    //---
    return lastDate; 
}
// return value : 현재 년월의 마지막 일자 날짜일 반환
function getLastDateOfThisMonth() {
	var today = new Date();
	//---
	year = today.getFullYear();
	month = today.getMonth();
	//---
	const lastDate = new Date(year, month + 1, 0).getDate();
    //---
    return lastDate; 
}
// return value : 특정 날짜객체의 마지막 일자 날짜일 반환
function getLastDateOfThisDateObject(dateObject=null) {
	if(!dateObject){return -1;}//if
	//---
	var today = new Date();
	//---
	year = dateObject.getFullYear();
	month = dateObject.getMonth();
	//---
	const lastDate = new Date(year, month + 1, 0).getDate();
    //---
    return lastDate; 
}
// return value : 특정 년월의 마지막 일자 날짜객체 반환
function getLastDateObjectOfMonth(year=0, month=0) {
	year = Number(year);
	month = Number(month);
	//---
	const lastDate = new Date(year, month, 0);
    //---
    return lastDate; 
}
// return value : 특정 날짜객체의 마지막 일자 날짜객체 반환
function getLastDateObjectOfDateObject(dateObject=null) {
	if(!dateObject){return null;}//if
	//---
	year = dateObject.getFullYear();
	month = dateObject.getMonth();
	//---
	const lastDate = new Date(year, month + 1, 0);
    //---
    return lastDate; 
}
// return value : 현재 년월의 마지막 일자 날짜객체 반환
function getLastDateObjectOfThisMonth() {
	var today = new Date();
	//---
	year = today.getFullYear();
	month = today.getMonth();
	//---
	const lastDate = new Date(year, month + 1, 0);
    //---
    return lastDate; 
}
// return value : 
//   orgStr 가 비었으면 appendStr 값반환,
//   orgStr 가 안비었으면 orgStr + sepStr + appendStr 값반환
function fnGetCombineString(orgStr='',appendStr='',sepStr=''){
	let returnValue = '';
	let combineString = '';
	//---
	orgStr = getNvlString(orgStr);
	appendStr = getNvlString(appendStr);
	sepStr = getNvlString(sepStr);
	//---
	if(orgStr===''){
		combineString = appendStr;
	}else{
		combineString = orgStr + sepStr + appendStr;
	}//if
	//---
	returnValue = combineString;
	return returnValue;
}
// 해당년월의 해당일자가 포함되는 주의 주번호 반환
// month : 1월부터 시작, 월번호
// console.log(fnGetWeekNoOfMonth(2026, 6, 14));
function fnGetWeekNoOfMonth(year=0, month=0, upToDay=0) {
    let weekCount = 1;
    
    // 1일부터 지정한 날짜(upToDay)까지 반복
    for (let day = 1; day <= upToDay; day++) {
        // 자바스크립트는 월(Month)이 0부터 시작하므로 month - 1 처리
        const date = new Date(year, month - 1, day);
        
        // getDay()가 0이면 일요일
        if (date.getDay() === 0) {
            weekCount++;
        }
    }
    
    return weekCount;
}
// 해당년월의 해당일자가 포함되는 주의 주번호 반환
// month : 1월부터 시작, 월번호
// console.log(fnGetWeekNoOfMonthForDateString('20260614'));
function fnGetWeekNoOfMonthForDateString(dateString='') {
    if(!dateString){return 0;}//if

	// 문자열을 자 잘라서 숫자형으로 변환
	const year = parseInt(dateString.substring(0, 4), 10);
	const month = parseInt(dateString.substring(4, 6), 10);
	const day = parseInt(dateString.substring(6, 8), 10);
	
	return fnGetWeekNoOfMonth(year, month, day);
}