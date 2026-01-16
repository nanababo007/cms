	<meta charset="utf-8">
	<title><?php echo isset($pageTitleString) ? $pageTitleString : "멀티게시판"; ?></title>
	<link rel="stylesheet" href="/board2/cmn/cmn.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="/board2/cmn/cmn.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
	<script>
		var linkAllowedHostsGlobalValue = [<?php echo "'".str_replace(",","','",$envVarMap["linkAllowedHostsGlobalValue"])."'"; ?>];
		//---
		function getLinkContentHtmlString(orgContentTextString=''){
			var returnString = '';
			var failReturnString = '';
			var allowedUrlsArray = null;
			var linkContentString = '';
			//---
			if(orgContentTextString===''){return failReturnString;}//if
			//---
			allowedUrlsArray = getAllowedUrlsArrayOfContent(orgContentTextString,linkAllowedHostsGlobalValue);
			//---
			linkContentString = getAllowedUrlLinkHtmlString(orgContentTextString,allowedUrlsArray);
			linkContentString = linkContentString.replaceAll('\r\n','<br />');
			linkContentString = linkContentString.replaceAll('\n','<br />');
			//---
			returnString = linkContentString;
			return returnString;
		}
	</script>