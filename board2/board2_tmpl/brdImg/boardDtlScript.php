<script>
var paramFormObject = document.paramForm;
//---
$(function(){
	initPage();
});
//---
function initPage(){
	setSearchEnter($('#schTitle, #schContent, #schReply'),function(){
		goSearch();
	});
	//날짜입력 컨트롤 셋팅
	setSearchDatepicker($("#schSRegdate"));
	setSearchDatepicker($("#schERegdate"));
}
function goPage(pageNumber){
	paramFormObject.pageNumber.value = pageNumber;
	paramFormObject.action = 'boardDtl.php';
	paramFormObject.submit();
}
function goView(bdaSeq=''){
	paramFormObject.bdaSeq.value = bdaSeq;
	paramFormObject.action = 'boardDtlView.php';
	paramFormObject.submit();
}
function goWrite(bdaSeq=''){
	paramFormObject.bdaSeq.value = bdaSeq;
	paramFormObject.action = 'boardDtlWrite.php';
	paramFormObject.submit();
}
function goSearch(){
	paramFormObject.schTitle.value = $('#schTitle').val();
	paramFormObject.schContent.value = $('#schContent').val();
	paramFormObject.schReply.value = $('#schReply').val();
	paramFormObject.schSRegdate.value = $('#schSRegdate').val();
	paramFormObject.schERegdate.value = $('#schERegdate').val();
	paramFormObject.pageNumber.value = '1';
	paramFormObject.action = 'boardDtl.php';
	paramFormObject.submit();
}
function resetSearch(){
	if(confirm('검색을 초기화 하시겠습니까?')){
		$('#schTitle').val('');
		$('#schContent').val('');
		$('#schReply').val('');
		$('#schSRegdate').val('');
		$('#schERegdate').val('');
		goSearch();
	}//if
}
</script>