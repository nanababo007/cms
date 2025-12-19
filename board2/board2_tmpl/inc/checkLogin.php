<?php
if(nvl($_SESSION["{{cms.prefix}}loginId"])==""){alertGo("로그인 해주세요.","/{{cms.prefix}}/login.php");}#if
?>