<?php
$dbCon = null;
function fnOpenDB(){
	global $dbCon;
	global $envVarMap;

	$host = $envVarMap["dbHostname"];
	$user = $envVarMap["dbUsername"];
	$pass = $envVarMap["dbUserpwd"];
	$dbName = $envVarMap["dbName"];

	$dbCon = mysqli_connect($host, $user, $pass);

	//데이터베이스 설치 및 클라이언트 접속 캐릭터셋 설정
	mysqli_query($dbCon, "set session character_set_connection=utf8mb4;");
	mysqli_query($dbCon, "set session character_set_results=utf8mb4;");
	mysqli_query($dbCon, "set session character_set_client=utf8mb4;");
	mysqli_query($dbCon, "set names utf8mb4");

	//데이터베이스 접속정보 확인
	if(!$dbCon){
		echo "Database Connection Error!!";
	}else{
		#echo "Database Connection Success!!";
	}

	$selectdb = mysqli_select_db($dbCon, $dbName);

	//데이터베이스 선택 확인
	if(!$selectdb){
		echo "Database Select DB Error!!";
	}else{
		#echo "Database Select DB Success!!";
	}
}
function fnCloseDB(){
	global $dbCon;
	
	if($dbCon!=null){
		mysqli_close($dbCon);
		$dbCon = null;
	}
}
# $listArray = fnDBGetList("SELECT * FROM tb_board");
function fnDBGetList($sql=""){
	global $dbCon;
	$returnArray = array();
	
	if($sql=="") return $returnArray;
	
	$result = mysqli_query($dbCon, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			array_push($returnArray,$row);
		}
	}
	
	return $returnArray;
}
# $rowData = fnDBGetRow("SELECT * FROM tb_board");
function fnDBGetRow($sql=""){
	global $dbCon;
	$returnValue = null;
	
	if($sql=="") return $returnArray;

	$result = mysqli_query($dbCon, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			$returnValue = $row;
			break;
		}
	}
	
	return $returnValue;
}
# $listTotalCount = fnDBGetIntValue("SELECT count(*) FROM tb_board");
function fnDBGetIntValue($sql=""){
	global $dbCon;
	$returnValue = 0;
	
	if($sql=="") return $returnValue;

	$result = mysqli_query($dbCon, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_row($result)) {
			$returnValue = intval($row[0]);
			break;
		}
	}
	
	return $returnValue;
}
# $listTotalCount = fnDBGetValue("SELECT count(*) FROM tb_board");
function fnDBGetValue($sql=""){
	global $dbCon;
	$returnValue = 0;
	
	if($sql=="") return $returnValue;

	$result = mysqli_query($dbCon, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_row($result)) {
			$returnValue = $row[0];
			break;
		}
	}
	
	return $returnValue;
}
# $titleString = fnDBGetStringValue("SELECT title FROM tb_board");
function fnDBGetStringValue($sql=""){
	global $dbCon;
	$returnValue = 0;
	
	if($sql=="") return $returnValue;

	$result = mysqli_query($dbCon, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_row($result)) {
			$returnValue = strval($row[0]);
			break;
		}
	}
	
	return $returnValue;
}
# $affectedRows = fnDBUpdate("UPDATE tb_board_article SET bda_title = 'aaaa' WHERE bda_seq = 0");
function fnDBUpdate($sql=""){
	global $dbCon;
	$returnCount = 0;
	
	if($sql=="") return $returnCount;
	
	$result = mysqli_query($dbCon, $sql);
	if ($result) {
		$returnCount = mysqli_affected_rows($dbCon);
		#echo "성공적으로 " . $affected_rows . "개의 레코드가 수정되었습니다.";
	} else {
		echo "업데이트 쿼리 오류: " . mysqli_error($dbCon);
	}
	
	return $returnCount;
}
function fnExit(){
	global $globalResourceArray;
	#---
	$globalResourceArrayIndex = 0;
	$globalResourceArrayItemObject = null;
	$globalResourceArrayCount = getArrayCount($globalResourceArray);
	#---
	fnCloseDB();
	for($globalResourceArrayIndex=0;$globalResourceArrayIndex<$globalResourceArrayCount;$globalResourceArrayIndex++){
		$globalResourceArrayItemObject = $globalResourceArray[$globalResourceArrayIndex];
		if($globalResourceArrayItemObject!=null){
			$globalResourceArray[$globalResourceArrayIndex] = null;
		}#if
	}#for
	exit();
}
function nvl($stringValue="",$defaultValue=""){
	if($stringValue==null || $stringValue==""){
		return $defaultValue;
	}else{
		return $stringValue;
	}#if
}
# fnEchoBR("title","cont");
function fnEchoBR($title="",$cont=""){
	echo "<br />";
	echo "<strong>${title}</strong> : ${cont}";
	echo "<br />";
}
function debugString($titleString="",$debugString=""){
	global $envVarMap;
	if($envVarMap["debugMode"]){
		echo "<br/>".DateLibraryClass::getCurrentDatetimeFormatString("Y-m-d H:i:s")." : ".$titleString." : ".$debugString."<br/>";
		echo "<br/>";
	}#if
}
function debugArray($titleString="",&$debugArray=null){
	global $envVarMap;
	if($envVarMap["debugMode"] && $debugArray!=null){
		echo "<br/>".DateLibraryClass::getCurrentDatetimeFormatString("Y-m-d H:i:s")." : ".$titleString." : <br/>";
		print_r($debugArray);
		echo "<br/>";
	}#if
}
function debugDump($titleString="",&$debugVal=null){
	global $envVarMap;
	if($envVarMap["debugMode"] && $debugVal!=null){
		echo "<br/>".DateLibraryClass::getCurrentDatetimeFormatString("Y-m-d H:i:s")." : ".$titleString." : <br/>";
		var_dump($debugVal);
		echo "<br/>";
	}#if
}
function echoBr($stringValue=""){
	echo "<br/>".$stringValue."<br/>";
}
function echoNl($stringValue=""){
	echo "\n".$stringValue."\n";
}
function getArrayValue(&$array=null,$keyString=""){
	$returnValue = "";
	#---
	if($array!=null && $keyString!="" && isset($array[$keyString])){
		$returnValue = $array[$keyString];
	}#if
	#---
	return $returnValue;
}
function getArrayCount(&$array=null){
	if($array!=null && is_array($array)){
		return count($array);
	}else{
		return 0;
	}#if
}
function alertBack($msg="",$isExit=true){
	echo "
		<html lang='ko'>
		<head>
			<meta charset='utf-8'>
			<title>멀티게시판</title>
			<script>
				alert('${msg}');
				history.back();
			</script>
		</head>
		</html>
	";
	#---
	if($isExit){fnExit();}#if
}
function alertGo($msg="",$url="",$isExit=true){
	echo "
		<html lang='ko'>
		<head>
			<meta charset='utf-8'>
			<title>멀티게시판</title>
			<script>
				alert('${msg}');
				location.href = '${url}';
			</script>
		</head>
		</html>
	";
	#---
	if($isExit){fnExit();}#if
}
function pageGo($url="",$isExit=true){
	echo "
		<html lang='ko'>
		<head>
			<meta charset='utf-8'>
			<title>멀티게시판</title>
			<script>
				location.href = '${url}';
			</script>
		</head>
		</html>
	";
	#---
	if($isExit){fnExit();}#if
}
function getRequestValue($keyString=""){
	if($keyString==""){return "";}#if
	$valueString = nvl(getArrayValue($_REQUEST,$keyString));
	$valueString = getInjectString($valueString);
	return $valueString;
}
function getPostValue($keyString=""){
	if($keyString==""){return "";}#if
	$valueString = nvl(getArrayValue($_POST,$keyString));
	$valueString = getInjectString($valueString);
	return $valueString;
}
function getGetValue($keyString=""){
	if($keyString==""){return "";}#if
	$valueString = nvl(getArrayValue($_GET,$keyString));
	$valueString = getInjectString($valueString);
	return $valueString;
}
function getInjectString($valueString=""){
	$valueString = nvl($valueString);
	#$valueString = str_replace("'","''",$valueString);
	$valueString = str_replace("<","&lt;",$valueString);
	$valueString = str_replace(">","&gt;",$valueString);
	$valueString = str_replace("=","〓",$valueString);
	$valueString = str_ireplace("onclick","ⓞⓝⓒⓛⓘⓒⓚ",$valueString);
	$valueString = str_ireplace("ondblclick","ⓞⓝⓓⓑⓛⓒⓛⓘⓒⓚ",$valueString);
	$valueString = str_ireplace("onmouseover","ⓞⓝⓜⓞⓤⓢⓔⓞⓥⓔⓡ",$valueString);
	$valueString = str_ireplace("onmouseout","ⓞⓝⓜⓞⓤⓢⓔⓞⓤⓣ",$valueString);
	$valueString = str_ireplace("onmousedown","ⓞⓝⓜⓞⓤⓢⓔⓓⓞⓦⓝ",$valueString);
	$valueString = str_ireplace("onmouseup","ⓞⓝⓜⓞⓤⓢⓔⓤⓖ",$valueString);
	$valueString = str_ireplace("onmousemove","ⓞⓝⓜⓞⓤⓢⓔⓜⓞⓥⓔ",$valueString);
	$valueString = str_ireplace("onkeydown","ⓞⓝⓚⓔⓨⓓⓞⓦⓝ",$valueString);
	$valueString = str_ireplace("onkeypress","ⓞⓝⓚⓔⓨⓖⓡⓔⓢⓢ",$valueString);
	$valueString = str_ireplace("onkeyup","ⓞⓝⓚⓔⓨⓤⓖ",$valueString);
	$valueString = str_ireplace("onsubmit","ⓞⓝⓢⓤⓑⓜⓘⓣ",$valueString);
	$valueString = str_ireplace("onreset","ⓞⓝⓡⓔⓢⓔⓣ",$valueString);
	$valueString = str_ireplace("onchange","ⓞⓝⓒⓗⓐⓝⓖⓔ",$valueString);
	$valueString = str_ireplace("onfocus","ⓞⓝⓕⓞⓒⓤⓢ",$valueString);
	$valueString = str_ireplace("onblur","ⓞⓝⓑⓛⓤⓡ",$valueString);
	$valueString = str_ireplace("oninput","ⓞⓝⓘⓝⓖⓤⓣ",$valueString);
	$valueString = str_ireplace("onload","ⓞⓝⓛⓞⓐⓓ",$valueString);
	$valueString = str_ireplace("onunload","ⓞⓝⓤⓝⓛⓞⓐⓓ",$valueString);
	$valueString = str_ireplace("onresize","ⓞⓝⓡⓔⓢⓘⓩⓔ",$valueString);
	$valueString = str_ireplace("onscroll","ⓞⓝⓢⓒⓡⓞⓛⓛ",$valueString);
	$valueString = str_ireplace("onerror","ⓞⓝⓔⓡⓡⓞⓡ",$valueString);
	$valueString = str_replace(" ","&nbsp;",$valueString);
	$valueString = str_replace("\"","&quot;",$valueString);
	$valueString = str_replace("'","&#39;",$valueString);
	return $valueString;
}
function getDecodeHtmlString($valueString=""){
	$valueString = nvl($valueString);
	$valueString = str_replace(" ","&nbsp;",$valueString);
	$valueString = str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;",$valueString);
	$valueString = str_replace("\n","<br/>",$valueString);
	return $valueString;
}
function addGlobalResource(&$globalResourceObject=null){
	global $globalResourceArray;
	#---
	if($globalResourceObject!=null){
		array_push($globalResourceArray,$globalResourceObject);
	}#if
}
function fnGetSqlWhereAndString($sqlSearchPartIndex=0){
	return $sqlSearchPartIndex==0 ? " where " : " and ";
}
# $str = "HelloWorldExample";
# list($before,$after) = splitBySubstring($str,"World");
function splitBySubstring($text="",$substring="") {
	$text = nvl($text);
	$substring = nvl($substring);
	#---
	if($text==""){return "";}#if
	if($substring==""){return [$text,""];}#if
	#--- 특정 문자열의 시작 위치 찾기
	$pos = strpos($text, $substring);
	if ($pos === false) {
		# 문자열이 없으면 원본 그대로 반환
		return [$text,""];
	}#if
	#---
	# 앞부분: 기준 문자열 이전
	$before = substr($text, 0, $pos);
	# 뒷부분: 기준 문자열 이후
	$after = substr($text, $pos + strlen($substring));
	return [$before, $after];
}
?>