    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitleString) ? $pageTitleString : "멀티게시판"; ?></title>
    <link rel="stylesheet" href="/board2/cmn/bootstrap5/css/bootstrap.min.css">
    <link rel="stylesheet" href="/board2/cmn/mobile/cmn.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="/board2/cmn/cmn.js"></script>
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
			//--- 다른 곳에서 이미 줄바꿈 처리하고 있음. 중복 처리됨. 처리 필요 없음.
			//linkContentString = linkContentString.replaceAll('\r\n','\n');
			//linkContentString = linkContentString.replaceAll('\n','<br />');
			//---
			returnString = linkContentString;
			return returnString;
		}
	</script>