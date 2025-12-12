function getNvlString(stringValue='',stringReplaceValue=''){
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