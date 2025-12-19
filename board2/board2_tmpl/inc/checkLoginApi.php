<?php
if(nvl($_SESSION["{{cms.prefix}}loginId"])==""){
	$responseLibraryObject->setResponseUserErrorData("need_login");
	responseJson();
}#if
?>