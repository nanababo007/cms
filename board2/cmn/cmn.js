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