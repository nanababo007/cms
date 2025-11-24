<?php
#--- 좌측 메뉴 표시여부 설정.
if(!isset($isLeftMenuDisplay)){$isLeftMenuDisplay = true;}#if
?>
<div class="layout">
	<?php if($isLeftMenuDisplay){ ?>
	<aside>
		<ul class="left-menu-class">
			<li><a class="active" href="#home">Home</a></li>
			<li><a href="#news">News</a></li>
			<li><a href="#news">- News 1</a></li>
			<li><a href="#news">- News 2</a></li>
			<li><a href="#contact">Contact</a></li>
			<li><a href="#about">About</a></li>
		</ul>
	</aside>
	<?php } ?>
	<main class="content-area-class">