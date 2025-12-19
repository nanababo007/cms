<header>
	<ul class="top-menu-class">
		<?php fnTopPrintDisplayMenu(); ?>
	</ul>
</header>
<?php
function fnTopPrintDisplayMenu(){
	global $displayMenuList;
	global $currentMenuSeq;
	global $currentTopMenuSeq;
	#---
	$mnSeq = 0;
	$pMnSeq = 0;
	$mnUrlString = "";
	$activeClassString = "";
	#---
	foreach($displayMenuList as $index => $menuInfo){
		$mnSeq = intval(nvl((string)$menuInfo["mn_seq"],"0"));
		$pMnSeq = intval(nvl((string)$menuInfo["p_mn_seq"],"0"));
		$mnUrlString = trim(nvl($menuInfo["mn_url"]));
		#---
		$mnUrlString = getMenuUrlAppendMnSeq($mnUrlString,(string)$mnSeq);
		#---
		if($pMnSeq==0){
			if($currentTopMenuSeq==$mnSeq){
				$activeClassString = " class='active' ";
			}else{
				$activeClassString = "";
			}#if
			#---
			?><li><a <?php echo $activeClassString; ?> href="<?php echo $mnUrlString; ?>" target="<?php echo nvl($menuInfo["mn_url_target"]); ?>"><?php echo nvl($menuInfo["mn_nm"]); ?></a></li><?php
		}#if
	}#foreach
}
?>