<?php include $_SERVER['DOCUMENT_ROOT']."/{{cms.prefix}}/lib/_include.php"; ?>
<?php
	$userId = nvl(getPostValue("userId"));
	$userPassword = nvl(getPostValue("userPassword"));
	#---
	$userId = substr($userId,0,50);
	$userPassword = substr($userPassword,0,50);
	#---
	if($siteLoginId=="" or $siteLoginPw==""){alertBack("로그인 정보를 설정해 주세요. (var.php)");}#if
	if($userId=="" or $userPassword==""){alertBack("로그인 정보가 부족합니다. (아아디/비밀번호 입력 필요)");}#if
	#---
	if($userId==$siteLoginId && $userPassword==$siteLoginPw){
		$_SESSION["{{cms.prefix}}loginId"] = $userId;
	}else{
		$_SESSION["{{cms.prefix}}loginId"] = "";
		alertBack("로그인 정보가 잘못되었습니다.");
	}//if
	#---
	header('Location: /{{cms.prefix}}/menu/menuMan.php');
?>