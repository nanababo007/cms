<?php
if(nvl($_SESSION["loginId"])==""){
	$responseLibraryObject->setResponseUserErrorData("need_login");
	responseJson();
}#if
?>