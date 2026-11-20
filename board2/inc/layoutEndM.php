<br /><br /><br />

<script src="/board2/cmn/bootstrap5/js/bootstrap.bundle.min.js"></script>

<script>
$(function(){
	var boardContentDtlAreaJqueryObject = null;
	//---
	$('.post-content')
		.add('.post-header .post-title')
	.each(function(index,el){
		var elJqueryObject = null;
		//---
		elJqueryObject = $(el);
		//---
		fnCmnBotReplaceSpcCharForElement(elJqueryObject);
	});
	//--- 댓글접기 후, 개별로 클릭 시 펴기동작
	if($('.reply-item-area-class').length){
		$('.reply-item-area-class').on('dblclick', '.reply-item-content-class', function() {
			var replyItemContentJqueryObject = $(this);
			//---
			fnCmnToggleItemReply(replyItemContentJqueryObject);
		});
	}//if
	//--- 게시글 내용 접고/펴기 토글 동작
	setTimeout(function(){
		boardContentDtlAreaJqueryObject = $('.board-content-dtl-area-class');
		if(boardContentDtlAreaJqueryObject.length){
			boardContentDtlAreaJqueryObject.dblclick(function(e) {
				fnCmnToggleBoardContent();
			});
		}//if
	},100);
});
</script>