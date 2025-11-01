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
	mysqli_query($dbCon, "set session character_set_connection=utf8;");
	mysqli_query($dbCon, "set session character_set_results=utf8;");
	mysqli_query($dbCon, "set session character_set_client=utf8;");
	mysqli_query($dbCon, "set names utf8");

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
	fnCloseDB();
	exit();
}
# fnEchoBR("title","cont");
function fnEchoBR($title="",$cont=""){
	echo "<br />";
	echo "<strong>${title}</strong> : ${cont}";
	echo "<br />";
}
?>