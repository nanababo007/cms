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