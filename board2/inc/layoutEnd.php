		<br /><br />
	</main><!-- content-area -->
</div><!-- layout -->

<script>
$(function(){
	var boardContentDtlAreaJqueryObject = null;
	//---
	$('.board-content-history-list-area')
		.add('.board-title-area-class')
		.add('.board-content-area')
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
function toggleShowMenu(){
	const TOGGLE_SHOW_MENU_CLASS_NAME_CONST = 'toggle-show-menu-class';
	const TOGGLE_SHOW_MENU_SELECTOR_CONST = '.toggle-show-menu-class > a';
	const LEFT_MENU_TARGET_SELECTOR_CONST = 'ul.left-menu-class > li > a';
	const LEFT_MENU_SHOW_STATUS_CLASS_NAME_CONST = 'left-menu-status-show-class';
	const LEFT_MENU_SHOW_STATUS_TEXT_COLOR_STRING_CONST = '#000000';
	const LEFT_MENU_HIDE_STATUS_TEXT_COLOR_STRING_CONST = '#f1f1f1';
	const LEFT_TOP_MENU_CLASS_NAME_CONST = 'left-top-menu-class';
	//---
	var leftMenuJqueryObject = null;
	var toggleShowMenuJqueryObject = null;
	//---
	leftMenuJqueryObject = $(LEFT_MENU_TARGET_SELECTOR_CONST)
		.not(TOGGLE_SHOW_MENU_SELECTOR_CONST)
		.not('.'+LEFT_TOP_MENU_CLASS_NAME_CONST);
	toggleShowMenuJqueryObject = $('.'+TOGGLE_SHOW_MENU_CLASS_NAME_CONST);
	//---
	if(toggleShowMenuJqueryObject.hasClass(LEFT_MENU_SHOW_STATUS_CLASS_NAME_CONST)){
		leftMenuJqueryObject.css('color',LEFT_MENU_HIDE_STATUS_TEXT_COLOR_STRING_CONST);
		toggleShowMenuJqueryObject.removeClass(LEFT_MENU_SHOW_STATUS_CLASS_NAME_CONST);
	}else{
		leftMenuJqueryObject.css('color',LEFT_MENU_SHOW_STATUS_TEXT_COLOR_STRING_CONST);
		toggleShowMenuJqueryObject.addClass(LEFT_MENU_SHOW_STATUS_CLASS_NAME_CONST);
	}//if
}
</script>