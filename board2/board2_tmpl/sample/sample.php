<?php
include($_SERVER["DOCUMENT_ROOT"].'/{{cms.prefix}}/lib/_include.php');

fnOpenDB();

fnEchoBR("title","cont");

$affectedRows = fnDBUpdate("
	UPDATE {{cms.tableNamePrefix}}_article SET 
		bda_title = 'aaaa' 
	WHERE bda_seq = 0
");
fnEchoBR("affectedRows",$affectedRows);

$listArray = fnDBGetList("SELECT * FROM {{cms.tableNamePrefix}}_info");
fnEchoBR("list print");
print_r($listArray);

fnCloseDB();
?>