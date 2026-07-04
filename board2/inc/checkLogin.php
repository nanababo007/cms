<?php
checkLogin_goLoginPage();
#---
function checkLogin_goLoginPage(){
	global $siteVarScriptPath;
	#---
	$loginPathString = "";
	$loginPathParamString = "";
	$loginUrlString = "";
	#---
	if (str_contains($siteVarScriptPath, "M.php")) {
		$loginPathString = "/board2/loginM.php";
	} else {
		$loginPathString = "/board2/login.php";
	}
	#---
	$loginPathParamString = "";
	$loginPathParamString .= "?return_url=".urlencode($_SERVER['REQUEST_URI']);
	#---
	$loginUrlString = $loginPathString.$loginPathParamString;
	#---
	if(nvl($_SESSION["loginId"])==""){alertGo("로그인 해주세요.",$loginUrlString);}#if
}
?>