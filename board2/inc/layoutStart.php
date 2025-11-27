<?php
#--- 좌측 메뉴 표시여부 설정.
if(!isset($isLeftMenuDisplay)){$isLeftMenuDisplay = true;}#if
?>
<div class="layout">
	<?php if($isLeftMenuDisplay){ ?>
	<aside>
		<ul class="left-menu-class">
			<?php fnLeftMenuPrintMenu(); ?>
			<!--
			<li><a href="#news">News</a></li>
			<li><a href="#news">- News 1</a></li>
			<li><a href="#news">- News 2</a></li>
			<li><a href="#contact">Contact</a></li>
			<li><a href="#about">About</a></li>
			-->
		</ul>
	</aside>
	<?php } ?>
	<?php
		function fnLeftMenuPrintMenu(){
			global $currentMenuSeq;
			global $currentMenuInfo;
			global $currentTopMenuSeq;
			global $currentTopMenuInfo;
			global $displayMenuList;
			#---
			$mnUrlString = "";
			$mnUrlTargetString = "";
			$mnNmString = "";
			$mnSeq = "";
			$topMnSeq = 0;
			$mnDepthNo = 0;
			$prefixString = "";
			#---
			if($currentTopMenuInfo!=null){
				$mnUrlString = getMenuUrlAppendMnSeq($currentTopMenuInfo["mn_url"],(string)$currentTopMenuInfo["mn_seq"]);
				$mnUrlTargetString = nvl($currentTopMenuInfo["mn_url_target"]);
				$mnNmString = nvl($currentTopMenuInfo["mn_nm"]);
				#---
				?><li><a class="active" href="<?php echo $mnUrlString; ?>" target="<?php echo $mnUrlTargetString; ?>"><?php echo $mnNmString; ?></a></li><?php
			}#if
			if($currentTopMenuSeq!=0){
				foreach($displayMenuList as $index => $menuInfo){
					$mnSeq = intval(nvl($menuInfo["mn_seq"],"0"));
					$topMnSeq = intval(nvl($menuInfo["top_mn_seq"],"0"));
					$mnUrlString = nvl($menuInfo["mn_url"]);
					$mnUrlTargetString = nvl($menuInfo["mn_url_target"]);
					$mnNmString = nvl($menuInfo["mn_nm"]);
					$mnDepthNo = intval(nvl($menuInfo["mn_depth_no"],"0"));
					#---
					if($mnDepthNo==0){continue;}#if
					#---
					$mnUrlString = getMenuUrlAppendMnSeq($mnUrlString,(string)$mnSeq);
					$prefixString = $mnDepthNo > 1 ? "- " : "";
					#---
					if($currentTopMenuSeq==$topMnSeq){
						?><li><a href="<?php echo $mnUrlString; ?>" target="<?php echo $mnUrlTargetString; ?>"><?php echo $prefixString; ?><?php echo nvl($menuInfo["mn_nm"]); ?></a></li><?php
					}#if
				}#foreach
			}#if
		}
	?>
	<main class="content-area-class">