<?php
function fnMenuGetPrefixString($mnDepthNo=0){
	$returnString = "";
	$errorReturnString = "";
	$prefixString = "";
	#---
	if($mnDepthNo==0){return $errorReturnString;}#if
	#---
	if($mnDepthNo==1){return "└ ";}#if
	$prefixString = "└".str_repeat("─", $mnDepthNo - 1);
	#---
	$returnString = $prefixString." ";
	return $returnString;
}
?>