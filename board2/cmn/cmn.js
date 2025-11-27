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