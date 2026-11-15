<br /><br /><br />

<script src="/board2/cmn/bootstrap5/js/bootstrap.bundle.min.js"></script>

<script>
$(function(){
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
});
</script>