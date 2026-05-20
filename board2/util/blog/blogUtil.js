const blogContentSectionDivCst = '\n\n───────────────────────\n\n';
const blogContentSectionCntCst = 20;
var blogCmnTitleJObj = $('#blogCmnTitle');
var blogResultTitleJObj = $('#blogResultTitle');
var blogResultContentJObj = $('#blogResultContent');
var blogContentAreaJObj = $('#blogContentArea');
var blogContentWriteBtnJObj = $('#blogContentWriteBtn');
var blogContentViewBtnJObj = $('#blogContentViewBtn');
var blogCate1JObj = $('#blogCate1');
var blogCate2JObj = $('#blogCate2');
var blogCate3JObj = $('#blogCate3');
var blogCate4JObj = $('#blogCate4');
var blogCate5JObj = $('#blogCate5');
var blogTag1JObj = $('#blogTag1');
var blogTag2JObj = $('#blogTag2');
var blogTag3JObj = $('#blogTag3');
var blogTag4JObj = $('#blogTag4');
var blogTag5JObj = $('#blogTag5');
var blogTypeNaverJObj = $('#blogTypeNaver');
var blogTypeGoogleJObj = $('#blogTypeGoogle');
var blogAISearchStrJObj = $('#blogAISearchStr');
var savedTagInfoAreaJObj = $('#savedTagInfoArea');
var savedTagInfoArray = [];
//---
$(function(){
	initPage();
});
//---
function initPage(){
	setBlogContentInputBox();
	//--- 입력박스 클릭 시 선택처리.
	blogResultTitleJObj
		.add(blogResultContentJObj)
		.add(blogAISearchStrJObj)
		.add('.blog-cate-cls')
		.add('.blog-tag-cls')
		.click(function(e){
			$(this).select();
		});
	//--- textarea 전체, 윈도우 스크롤 방지.
	setStopWinScrollOfAllTextarea();
	//--- 저장된 태그 정보 출력.
	fnDisplayTagInfo();
}
function setBlogContentInputBox(){
	var blogContent1JObj = $('#blogContent1');
	var blogContentIdx = 0;
	var blogContentNum = 0;
	var blogContentTmplStr = '';
	//---
	for(blogContentIdx=1;blogContentIdx<blogContentSectionCntCst;blogContentIdx++){
		blogContentNum = blogContentIdx + 1;
		blogContentTmplStr = `<br><br><textarea id="blogContent${blogContentNum}" 
			class="blog-content-cls" placeholder="내용 단락 ${blogContentNum}"  style="height:200px;"></textarea>`;
		//---
		blogContentAreaJObj.append(blogContentTmplStr);
	}//for
	//--- 단락 클릭 시 선택처리.
	/*$('.blog-content-cls').click(function(e){
		$(this).select();
	});*/
}
function setStopWinScrollOfAllTextarea(){
	var blogContentJObj = null;
	var blogContentIdx = 0;
	var blogContentNum = 0;
	//---
	for(blogContentIdx=0;blogContentIdx<blogContentSectionCntCst;blogContentIdx++){
		blogContentNum = blogContentIdx + 1;
		blogContentJObj = $('#blogContent'+blogContentNum);
		//---
		fnSetStopWinScrollOfTextarea(blogContentJObj[0]);
	}//for
	//---
	fnSetStopWinScrollOfTextarea(blogResultContentJObj[0]);
	fnSetStopWinScrollOfTextarea(blogAISearchStrJObj[0]);
}
/**
블로그 본문 글 작성.
*/
function fnWriteBlogContent(){
	var blogContentJObj = null;
	var blogContentIdx = 0;
	var blogContentNum = 0;
	var blogContentSectionStr = '';
	var blogContentArray = [];
	var blogContentResultStr = '';
	var blogContentTagStr = '';
	var blogTitleResultStr = '';
	var isBlogTypeNaverChecked = false;
	var isBlogTypeGoogleChecked = false;
	//---
	isBlogTypeNaverChecked = blogTypeNaverJObj.prop('checked');
	isBlogTypeGoogleChecked = blogTypeGoogleJObj.prop('checked');
	//---
	for(blogContentIdx=0;blogContentIdx<blogContentSectionCntCst;blogContentIdx++){
		blogContentNum = blogContentIdx + 1;
		blogContentJObj = $('#blogContent'+blogContentNum);
		//---
		blogContentSectionStr = blogContentJObj.val();
		if(blogContentSectionStr!==''){
			blogContentSectionStr = fnTrimOuterEmptyLines(blogContentSectionStr);
			blogContentArray.push(blogContentSectionStr);
		}//if
	}//for
	//--- 블로그 본문, 결과 텍스트 설정.
	blogContentResultStr = blogContentArray.join(blogContentSectionDivCst);
	//--- 블로그 본문, 태그 텍스트 설정.
	if(isBlogTypeNaverChecked){
		blogContentTagStr = '태그 :';
		if(blogTag1JObj.val()!==''){blogContentTagStr += ' #'+blogTag1JObj.val()+',';}//if
		if(blogTag2JObj.val()!==''){blogContentTagStr += ' #'+blogTag2JObj.val()+',';}//if
		if(blogTag3JObj.val()!==''){blogContentTagStr += ' #'+blogTag3JObj.val()+',';}//if
		if(blogTag4JObj.val()!==''){blogContentTagStr += ' #'+blogTag4JObj.val()+',';}//if
		if(blogTag5JObj.val()!==''){blogContentTagStr += ' #'+blogTag5JObj.val()+',';}//if
	}else if(isBlogTypeGoogleChecked){
		blogContentTagStr = '태그 :';
		if(blogTag1JObj.val()!==''){blogContentTagStr += ' '+blogTag1JObj.val()+',';}//if
		if(blogTag2JObj.val()!==''){blogContentTagStr += ' '+blogTag2JObj.val()+',';}//if
		if(blogTag3JObj.val()!==''){blogContentTagStr += ' '+blogTag3JObj.val()+',';}//if
		if(blogTag4JObj.val()!==''){blogContentTagStr += ' '+blogTag4JObj.val()+',';}//if
		if(blogTag5JObj.val()!==''){blogContentTagStr += ' '+blogTag5JObj.val()+',';}//if
	}//if
	blogContentResultStr += '\n\n\n\n'+blogContentTagStr+'\n\n';
	blogContentResultStr = blogContentResultStr.replaceAll('〓','=');
	//--- 블로그 제목, 분류 텍스트 설정.
	if(isBlogTypeNaverChecked){
		if(blogCate2JObj.val()!==''){blogTitleResultStr += '['+blogCate2JObj.val()+']';}//if
		if(blogCate3JObj.val()!==''){blogTitleResultStr += '['+blogCate3JObj.val()+']';}//if
		if(blogCate4JObj.val()!==''){blogTitleResultStr += '['+blogCate4JObj.val()+']';}//if
		if(blogCate5JObj.val()!==''){blogTitleResultStr += '['+blogCate5JObj.val()+']';}//if
	}else if(isBlogTypeGoogleChecked){
		if(blogCate1JObj.val()!==''){blogTitleResultStr += '['+blogCate1JObj.val()+']';}//if
		if(blogCate2JObj.val()!==''){blogTitleResultStr += '['+blogCate2JObj.val()+']';}//if
		if(blogCate3JObj.val()!==''){blogTitleResultStr += '['+blogCate3JObj.val()+']';}//if
		if(blogCate4JObj.val()!==''){blogTitleResultStr += '['+blogCate4JObj.val()+']';}//if
		if(blogCate5JObj.val()!==''){blogTitleResultStr += '['+blogCate5JObj.val()+']';}//if
	}//if
	blogTitleResultStr += blogCmnTitleJObj.val();
	//---
	blogResultTitleJObj.val(blogTitleResultStr);
	blogResultContentJObj.val(blogContentResultStr);
	fnViewBlogContent();
}
function fnViewBlogContent(){
	location.href = '#winBlogResultPos';
	blogResultTitleJObj.focus();
	blogResultTitleJObj.select();
}
function fnFavArticle(articleKindStr=''){
	fnClearCateAll();
	fnClearTagAll();
	//---
	if(articleKindStr==='조선역사'){ //조선역사
		blogCate1JObj.val('역사');
		blogCate2JObj.val('조선');
	}else if(articleKindStr==='고려역사'){ //고려역사
		blogCate1JObj.val('역사');
		blogCate2JObj.val('고려');
	}else if(articleKindStr==='명나라역사'){ //명나라역사
		blogCate1JObj.val('역사');
		blogCate2JObj.val('명나라');
	}else if(articleKindStr==='청나라역사'){ //청나라역사
		blogCate1JObj.val('역사');
		blogCate2JObj.val('청나라');
	}//if
	//---
	fnCopyCateToTag();
}
function fnClearCateAll(){
	$('.blog-cate-cls').val('');
}
function fnClearTagAll(){
	$('.blog-tag-cls').val('');
}
function fnCopyCateToTag(){
	blogTag1JObj.val(blogCate1JObj.val());
	blogTag2JObj.val(blogCate2JObj.val());
	blogTag3JObj.val(blogCate3JObj.val());
	blogTag4JObj.val(blogCate4JObj.val());
	blogTag5JObj.val(blogCate5JObj.val());
}
function fnResetPage(){
	if(confirm('페이지를 리셋 하시겠습니까?')){
		location.href = 'blogUtil.html';
	}//if
}
function fnSaveTagInfo(){
	const TAG_STORAGE_NAME_CONST = 'tagInfo';
	var tagSaveName = '';
	var tagSaveInfoJsonString = '';
	var oldTagSaveInfoJsonString = '';
	var tagSaveInfoJsonObject = null;
	var tagSaveInfoJsonObjectArray = null;
	//---
	if(confirm('태그정보를 저장 하시겠습니까?')){
		tagSaveName = prompt('저장할 태그정보 이름을 입력해주세요.','');
		if(!tagSaveName){return;}//if
		//---
		oldTagSaveInfoJsonString = $.trim(localStorage.getItem(TAG_STORAGE_NAME_CONST));
		if(oldTagSaveInfoJsonString!==''){
			tagSaveInfoJsonObjectArray = JSON.parse(oldTagSaveInfoJsonString);
		}else{
			tagSaveInfoJsonObjectArray = [];
		}//if
		//---
		tagSaveInfoJsonObject = {};
		tagSaveInfoJsonObject.saveName = tagSaveName
		tagSaveInfoJsonObject.blogCate1 = blogCate1JObj.val();
		tagSaveInfoJsonObject.blogCate2 = blogCate2JObj.val();
		tagSaveInfoJsonObject.blogCate3 = blogCate3JObj.val();
		tagSaveInfoJsonObject.blogCate4 = blogCate4JObj.val();
		tagSaveInfoJsonObject.blogCate5 = blogCate5JObj.val();
		tagSaveInfoJsonObject.blogTag1 = blogTag1JObj.val();
		tagSaveInfoJsonObject.blogTag2 = blogTag2JObj.val();
		tagSaveInfoJsonObject.blogTag3 = blogTag3JObj.val();
		tagSaveInfoJsonObject.blogTag4 = blogTag4JObj.val();
		tagSaveInfoJsonObject.blogTag5 = blogTag5JObj.val();
		//---
		tagSaveInfoJsonObjectArray.unshift(tagSaveInfoJsonObject);
		localStorage.setItem(TAG_STORAGE_NAME_CONST,JSON.stringify(tagSaveInfoJsonObjectArray));
		//---
		fnDisplayTagInfo();
	}//if
}
function fnDeleteTagAll(){
	const TAG_STORAGE_NAME_CONST = 'tagInfo';
	//---
	if(confirm('태그정보를 모두 삭제 하시겠습니까?')){
		localStorage.setItem(TAG_STORAGE_NAME_CONST,'');
		//---
		fnDisplayTagInfo();
		fnRestoreSavedTag(null);
	}//if
}
function fnDisplayTagInfo(){
	const TAG_STORAGE_NAME_CONST = 'tagInfo';
	var tagInfoHtmlString = '';
	var tagInfoHtmlArray = null;
	var tagItemInfoHtmlString = '';
	var tagItemInfoHtmlTemplateString = '';
	var tagSaveInfoJsonString = '';
	var tagSaveInfoJsonObject = null;
	var tagSaveInfoJsonObjectArray = null;
	//---
	tagSaveInfoJsonString = $.trim(localStorage.getItem(TAG_STORAGE_NAME_CONST));
	if(tagSaveInfoJsonString===''){savedTagInfoAreaJObj.html('');return;}//if
	tagSaveInfoJsonObjectArray = JSON.parse(tagSaveInfoJsonString);
	//---
	tagInfoHtmlArray = [];
	savedTagInfoArray = [];
	$.each(tagSaveInfoJsonObjectArray,function(index,tagSaveInfoJsonItemObject){
		tagSaveInfoJsonObject = tagSaveInfoJsonItemObject;
		savedTagInfoArray[tagSaveInfoJsonObject.saveName] = tagSaveInfoJsonObject;
		//---
		tagItemInfoHtmlTemplateString = `<a href="javascript:fnRestoreSavedTag('saveName');">saveName</a>, `;
		tagItemInfoHtmlTemplateString = tagItemInfoHtmlTemplateString.replaceAll('saveName',tagSaveInfoJsonObject.saveName);
		//---
		tagItemInfoHtmlString = tagItemInfoHtmlTemplateString;
		tagInfoHtmlArray.push(tagItemInfoHtmlString);
	});
	//---
	tagInfoHtmlString = tagInfoHtmlArray.join('\n');
	savedTagInfoAreaJObj.html(tagInfoHtmlString);
}
function fnRestoreSavedTag(saveName){
	var tagSaveInfoJsonObject = null;
	//---
	tagSaveInfoJsonObject = savedTagInfoArray[saveName] || {};
	//---
	blogCate1JObj.val(fnNvl(tagSaveInfoJsonObject.blogCate1));
	blogCate2JObj.val(fnNvl(tagSaveInfoJsonObject.blogCate2));
	blogCate3JObj.val(fnNvl(tagSaveInfoJsonObject.blogCate3));
	blogCate4JObj.val(fnNvl(tagSaveInfoJsonObject.blogCate4));
	blogCate5JObj.val(fnNvl(tagSaveInfoJsonObject.blogCate5));
	blogTag1JObj.val(fnNvl(tagSaveInfoJsonObject.blogTag1));
	blogTag2JObj.val(fnNvl(tagSaveInfoJsonObject.blogTag2));
	blogTag3JObj.val(fnNvl(tagSaveInfoJsonObject.blogTag3));
	blogTag4JObj.val(fnNvl(tagSaveInfoJsonObject.blogTag4));
	blogTag5JObj.val(fnNvl(tagSaveInfoJsonObject.blogTag5));
}