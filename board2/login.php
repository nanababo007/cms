<?php include $_SERVER['DOCUMENT_ROOT']."/board2/lib/_include.php"; ?>
<?php
	$exceptUserFuncJsOption = false;
	$fromParameterValue = "";
	$loginUserId = "";
	#---
	$exceptUserFuncJsOption = true;
	$fromParameterValue = getRequestValue("from");
	if($fromParameterValue=="career"){$loginUserId = "career";}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include $_SERVER['DOCUMENT_ROOT']."/inc/head.php"; ?>
</head>
<body>
<form name="formLogin" method="post" action="loginP.php">
<input type="hidden" name="loginMode" value="" />
<input type="hidden" name="from" value="<?php echo $fromParameterValue; ?>" />
<table>
<tr>
	<td align="center" valign="middle"><input type="text" id="userId" name="userId" value="test" placeholder="User id" class="input-text" onclick="this.select();" /></td>
</tr>
<tr>
	<td align="center" valign="middle"><input type="password" id="userPassword" name="userPassword" value="1234" placeholder="User password" class="input-password" onclick="this.select();" /></td>
</tr>
<tr>
	<td align="center" valign="middle" height="100"><a href="javascript:fnGoLogin();"  style="font-size:20pt;text-decoration:none;">로그인</a></td>
</tr>
<tr style="display:none;">
	<td align="center" valign="middle" height="100"><a href="javascript:fnGoLogin2();"  style="font-size:20pt;text-decoration:none;">기존 로그인</a></td>
</tr>
</table>
</form>
<script>
var formLogin = document.formLogin;
function fnGoLogin(){
	formLogin.loginMode.value='';
	formLogin.submit();
}
function fnGoLogin2(){
	formLogin.loginMode.value='2';
	formLogin.submit();
}
</script>
</body>
</html>