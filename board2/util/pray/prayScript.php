<script>
//--- 상수정의
//const PAGE_KIND_MEMORY_CONST = 'memory';
//const PAGE_KIND_TEST_CONST = 'test';
//--- 변수정의
//var aaa = '';
//--- 초기화
$(function(){
	initPage();
});
//--- 함수정의
function initPage(){
}
function fnAddBookmark(prSeq='',prNo=''){
	if(!prSeq){return;}//if
	//---
	if(confirm('북마크를 추가하시겠습니까?')){
		insertHistData(prSeq, prNo);
	}//if
}
function insertHistData(prSeq='', prNo='', callbackFunc=null){
	var apiUrl = '';
	var paramsObject = {};
	//---
	apiUrl = 'prayApi.php';
	//---
	paramsObject.actionString = 'INSERT_HIST_DATA';
	paramsObject.prSeq = prSeq;
	paramsObject.prNo = prNo;
	//---
	$.post(apiUrl,paramsObject,function(data){
		//console.info('insertHistData : data : ',data);
		if($.isFunction(callbackFunc)){callbackFunc(data);}//if
	});
}
</script>