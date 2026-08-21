
    <!-- 상단 네비게이션 바 -->
    <nav class="navbar navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <!-- 좌측 상단 메뉴 아이콘 버튼 -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <span class="navbar-brand mb-0 h1 mx-auto"><?php echo $pageMTitleString; ?> </span>
            <div style="width: 40px;"></div> <!-- 중앙 정렬을 위한 빈 공간 -->
        </div>
    </nav>

    <!-- 사이드 메뉴 (Offcanvas) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title" id="mobileMenuLabel">전체 메뉴</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="list-group list-group-flush">
                <!--a href="#" class="list-group-item list-group-item-action py-3 active">📝 자유게시판</a-->
				<?php fnTopPrintDisplayMenu(); ?>
            </div>
        </div>
    </div>
	<?php
	#--- 왼쪽 메뉴 표시여부 설정.
	if(!isset($isLeftMenuDisplay)){$isLeftMenuDisplay = true;}#if
	#--- 상단메뉴 및 하위메뉴 출력.
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
			if(strpos($mnUrlString, "javascript:")===false){$mnUrlString = str_replace(".php","M.php",$mnUrlString);}//if
			$mnUrlString = getMenuUrlAppendMnSeq($mnUrlString,(string)$mnSeq);
			#---
			if($pMnSeq==0){
				if($currentTopMenuSeq==$mnSeq){
					$activeClassString = " active ";
				}else{
					$activeClassString = "";
				}#if
				#---
				?><a data-url_info="<?php echo $mnUrlString; ?>" href="javascript:fnLeftMenu_toggleTopMenu(<?php echo $mnSeq; ?>);" 
					class="list-group-item list-group-item-action py-3 <?php echo $activeClassString; ?>" 
					data-target_info="<?php echo nvl($menuInfo["mn_url_target"]); ?>" id="topMenuItemBox<?php echo $mnSeq; ?>">📝 <?php echo nvl($menuInfo["mn_nm"]); ?></a><?php
				#--- 현재 메뉴의 하위메뉴
				if($currentTopMenuSeq==$mnSeq){
					fnLeftMenuPrintMenu($mnSeq,true);
				}else{
					fnLeftMenuPrintMenu($mnSeq);
				}#if
			}#if
		}#foreach
	}
	function fnLeftMenuPrintMenu($displayTopMenuSeq=0,$currentTopMenuFlag=false){
		global $currentMenuSeq;
		global $currentMenuInfo;
		global $currentTopMenuInfo;
		global $displayMenuList;
		global $isLeftMenuDisplay;
		#---
		$mnUrlString = "";
		$mnUrlTargetString = "";
		$mnNmString = "";
		$mnSeq = "";
		$topMnSeq = 0;
		$mnDepthNo = 0;
		$prefixString = "";
		#---
		#if(!$isLeftMenuDisplay){return;}#if
		#--- 미사용(피씨용).
		if(false &&$currentTopMenuInfo!=null){
			$mnUrlString = getMenuUrlAppendMnSeq($currentTopMenuInfo["mn_url"],(string)$currentTopMenuInfo["mn_seq"]);
			$mnUrlTargetString = nvl($currentTopMenuInfo["mn_url_target"]);
			$mnNmString = nvl($currentTopMenuInfo["mn_nm"]);
			#---
			?><li><a class="active" href="<?php echo $mnUrlString; ?>" target="<?php echo $mnUrlTargetString; ?>"><?php echo $mnNmString; ?></a></li><?php
		}#if
		#--- 하위메뉴출력.
		if($displayTopMenuSeq!=0){
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
				if(strpos($mnUrlString, "javascript:")===false){$mnUrlString = str_replace(".php","M.php",$mnUrlString);}//if
				$prefixString = $mnDepthNo > 1 ? "&nbsp;&nbsp;" : "";
				#---
				if($displayTopMenuSeq==$topMnSeq){
					?><a href="<?php echo $mnUrlString; ?>" target="<?php echo $mnUrlTargetString; ?>" class="list-group-item list-group-item-action py-3 left-menu-item-box-item-list-class-<?php echo $displayTopMenuSeq; ?> left-menu-item-box-item-list-class" 
						style="<?php if(!$currentTopMenuFlag){ ?>display:none;<?php } ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $prefixString; ?><?php echo nvl($menuInfo["mn_nm"]); ?></a><?php
				}#if
			}#foreach
		}#if
	}
	?>
	<script>
	function fnLeftMenu_toggleTopMenu(topMnSeq=0){
		var topMenuItemBoxJqueryObject = null;
		var thisMenuUrlString = '';
		//---
		if(!topMnSeq || topMnSeq===0){return;}//if
		//---
		topMenuItemBoxJqueryObject = $('#topMenuItemBox'+topMnSeq);
		thisMenuUrlString = getNvlString(topMenuItemBoxJqueryObject.data('url_info'));
		//---
		if(thisMenuUrlString.indexOf('logoutM.php')!==-1){
			location.href = thisMenuUrlString;
		}else{
			$('.left-menu-item-box-item-list-class').hide();
			$('.left-menu-item-box-item-list-class-'+topMnSeq).show();
		}//if
	}
	</script>