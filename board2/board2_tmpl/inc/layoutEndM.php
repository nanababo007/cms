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
});
</script>