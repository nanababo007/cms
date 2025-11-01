<?php
include($_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php');

fnOpenDB();

fnEchoBR("title","cont");

$affectedRows = fnDBUpdate("
	UPDATE tb_board_article SET 
		bda_title = 'aaaa' 
	WHERE bda_seq = 0
");
fnEchoBR("affectedRows",$affectedRows);

$listArray = fnDBGetList("SELECT * FROM tb_board_info");
fnEchoBR("list print");
print_r($listArray);

fnCloseDB();
?>