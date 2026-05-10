<?php include $_SERVER['DOCUMENT_ROOT']."/board2/lib/_include.php"; ?>
<?php
	$pageTitleString = "";
	$exceptUserFuncJsOption = false;
	$fromParameterValue = "";
	$loginUserId = "";
	$return_url = "";
	#---
	$pageTitleString = "멀티게시판 로그인";
	$exceptUserFuncJsOption = true;
	$fromParameterValue = getRequestValue("from");
	if($fromParameterValue=="career"){$loginUserId = "career";}
	$return_url = isset($_GET['return_url']) ? $_GET['return_url'] : '/board2/menu/menuManM.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include($_SERVER["DOCUMENT_ROOT"].'/board2/inc/headM.php'); ?>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 1rem;
        }
    </style>
</head>
<body>
<div class="container p-4">
    <div class="card login-card shadow-sm p-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold">로그인</h2>
            <p class="text-muted small">서비스 이용을 위해 로그인 해주세요.</p>
        </div>
        <form name="formLogin" method="post" action="loginP.php">
			<input type="hidden" name="loginMode" value="" />
			<input type="hidden" name="mobileMode" value="M" />
			<input type="hidden" name="from" value="<?php echo $fromParameterValue; ?>" />
			<input type="hidden" name="return_url" value="<?php echo $return_url; ?>" />
            <!-- 사용자 아이디 입력 -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="userId" name="userId" value="" placeholder="name@example.com">
                <label for="userId">사용자 아이디</label>
            </div>
            <!-- 비밀번호 입력 -->
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="userPassword" name="userPassword" value="" placeholder="Password" onclick="this.select();">
                <label for="userPassword">비밀번호</label>
            </div>
            <!-- 로그인 상태 유지 및 비밀번호 찾기 -->
            <!--div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="rememberUserId">
                    <label class="form-check-label small" for="rememberUserId">아이디 저장</label>
                </div>
                <a href="#" class="text-decoration-none small text-primary">비밀번호 찾기</a>
            </div-->
            <!-- 로그인 버튼 -->
            <button class="btn btn-primary w-100 py-3 fw-bold mb-3" type="submit" style="border-radius: 0.5rem;" onclick="javascript:fnGoLogin();">
                로그인
            </div>
			<!--a href="javascript:fnGoLogin2();">기존 로그인</a-->
        </form>
    </div>
</div>
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