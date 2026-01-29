<?php
/*
 * 백업 테이블 
 - tb_board_bak_info
 - tb_board_bak_article
 - tb_board_bak_reply
 - tb_board_bak_img_info
 - tb_board_bak_img_article
 - tb_board_bak_img_reply
 - tb_board_bak_menu_info
 * 함수 목록
 - fnHistInsertBoardInfo($bdSeq="")
 - fnHistInsertBoardArticle($bdaSeq="")
 - fnHistInsertBoardReply($bdrSeq="")
 - fnHistInsertImgBoardInfo($bdSeq="")
 - fnHistInsertImgBoardArticle($bdaSeq="")
 - fnHistInsertImgBoardReply($bdrSeq="")
 - fnHistInsertBoardMenuInfo($mnSeq="")
*/
function fnHistInsertBoardInfo($bdSeq=""){
	$bdSeq = trim($bdSeq);
	#---
	if($bdSeq==""){return;}#if
	#---
	$sql = "
		insert into tb_board_bak_info (
			bd_seq
			,bd_nm
			,bd_content
			,bd_fix_yn
			,regdate
			,reguser
			,moddate
			,moduser
			,bakdate
		) select
			bd_seq
			,bd_nm
			,bd_content
			,bd_fix_yn
			,regdate
			,reguser
			,moddate
			,moduser
			,NOW(3)
		from tb_board_info
		where bd_seq = '${bdSeq}'
	";
	#---
	fnDBUpdate($sql);
}
function fnHistInsertBoardArticle($bdaSeq=""){
	$bdaSeq = trim($bdaSeq);
	#---
	if($bdaSeq==""){return;}#if
	#---
	$sql = "
		insert into tb_board_bak_article (
			bda_seq
			,bda_title
			,bda_content
			,bd_seq
			,bda_fix_yn
			,regdate
			,reguser
			,moddate
			,moduser
			,bakdate
		) select
			bda_seq
			,bda_title
			,bda_content
			,bd_seq
			,bda_fix_yn
			,regdate
			,reguser
			,moddate
			,moduser
			,NOW(3)
		from tb_board_article
		where bda_seq = '${bdaSeq}'
	";
	#---
	fnDBUpdate($sql);
}
function fnHistInsertBoardReply($bdrSeq=""){
	$bdrSeq = trim($bdrSeq);
	#---
	if($bdrSeq==""){return;}#if
	#---
	$sql = "
		insert into tb_board_bak_reply (
			bdr_seq
			,bda_seq
			,bdr_content
			,regdate
			,reguser
			,moddate
			,moduser
			,bakdate
		) select
			bdr_seq
			,bda_seq
			,bdr_content
			,regdate
			,reguser
			,moddate
			,moduser
			,NOW(3)
		from tb_board_reply
		where bdr_seq = '${bdrSeq}'
	";
	#---
	fnDBUpdate($sql);
}
function fnHistInsertImgBoardInfo($bdSeq=""){
	$bdSeq = trim($bdSeq);
	#---
	if($bdSeq==""){return;}#if
	#---
	$sql = "
		insert into tb_board_bak_img_info (
			bd_seq
			,bd_nm
			,bd_content
			,bd_fix_yn
			,regdate
			,reguser
			,moddate
			,moduser
			,bakdate
		) select
			bd_seq
			,bd_nm
			,bd_content
			,bd_fix_yn
			,regdate
			,reguser
			,moddate
			,moduser
			,NOW(3)
		from tb_board_img_info
		where bd_seq = '${bdSeq}'
	";
	#---
	fnDBUpdate($sql);
}
function fnHistInsertImgBoardArticle($bdaSeq=""){
	$bdaSeq = trim($bdaSeq);
	#---
	if($bdaSeq==""){return;}#if
	#---
	$sql = "
		insert into tb_board_bak_img_article (
			bda_seq
			,bda_title
			,bda_content
			,bd_seq
			,bda_fix_yn
			,regdate
			,reguser
			,moddate
			,moduser
			,bakdate
		) select
			bda_seq
			,bda_title
			,bda_content
			,bd_seq
			,bda_fix_yn
			,regdate
			,reguser
			,moddate
			,moduser
			,NOW(3)
		from tb_board_img_article
		where bda_seq = '${bdaSeq}'
	";
	#---
	fnDBUpdate($sql);
}
function fnHistInsertImgBoardReply($bdrSeq=""){
	$bdrSeq = trim($bdrSeq);
	#---
	if($bdrSeq==""){return;}#if
	#---
	$sql = "
		insert into tb_board_bak_img_reply (
			bdr_seq
			,bda_seq
			,bdr_content
			,regdate
			,reguser
			,moddate
			,moduser
			,bakdate
		) select
			bdr_seq
			,bda_seq
			,bdr_content
			,regdate
			,reguser
			,moddate
			,moduser
			,NOW(3)
		from tb_board_img_reply
		where bdr_seq = '${bdrSeq}'
	";
	#---
	fnDBUpdate($sql);
}
function fnHistInsertBoardMenuInfo($mnSeq=""){
	$mnSeq = trim($mnSeq);
	#---
	if($mnSeq==""){return;}#if
	#---
	$sql = "
		insert into tb_board_bak_menu_info (
			p_mn_seq
			,mn_seq
			,mn_nm
			,mn_content
			,mn_ord
			,mn_url
			,mn_url_target
			,mn_use_yn
			,mn_del_yn
			,regdate
			,reguser
			,moddate
			,moduser
			,bakdate
		) select
			p_mn_seq
			,mn_seq
			,mn_nm
			,mn_content
			,mn_ord
			,mn_url
			,mn_url_target
			,mn_use_yn
			,mn_del_yn
			,regdate
			,reguser
			,moddate
			,moduser
			,NOW(3)
		from tb_board_menu_info
		where mn_seq = '${mnSeq}'
	";
	#---
	fnDBUpdate($sql);
}
?>