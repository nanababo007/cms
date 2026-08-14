		<br /><br />
	</main><!-- content-area -->
</div><!-- layout -->

<script>
$(function(){
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