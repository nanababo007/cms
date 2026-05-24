<?php
$sql = "";
$index = 0;
$number = 0;
$row = null;
$prayList = null;
$prayListCount = 0;
$bookmarkList = null;
$bookmarkListCount = 0;
#---
fnOpenDB();
#---
$sql = "
	SELECT
		a.pr_seq, 
		a.pr_title, 
		a.pr_content 
	from tb_board_pray_info a
	order by a.pr_seq asc
";
$prayList = fnDBGetList($sql);
$prayListCount = getArrayCount($prayList);
#---
$sql = "
	SELECT
		a.prh_seq, 
		a.pr_seq, 
		a.pr_no, 
		a.prh_date,
		STR_TO_DATE(a.prh_date, '%Y-%m-%d %H:%i:%s') as prh_date_str
	from tb_board_pray_hist a
	order by a.prh_seq desc
	limit 5
";
$bookmarkList = fnDBGetList($sql);
$bookmarkListCount = getArrayCount($bookmarkList);
#--- debug
#print_r($prayList);
#print_r($bookmarkList);
#---
fnCloseDB();
?>