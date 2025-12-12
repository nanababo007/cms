<?php
/*
$dbLibraryObject = new DBLibraryClass($host="",$user="",$pass="",$dbName="");
addGlobalResource($dbLibraryObject);
#---
$listData = $dbLibraryObject->fnDBGetList("SELECT * FROM tb_board");
#---
$dbLibraryObject = null;
#=======================
$dbLibraryObject = getDBLibrary("conn1");
#---
if($dbLibraryObject!=null){
	$listData = $dbLibraryObject->fnDBGetList("SELECT * FROM tb_board_info");
	debugArray("=== listData",$listData);
}#if
#---
$dbLibraryObject = null;
*/
#---
function getDBLibrary($connectionSortString=""){
	$dbLibraryObject = null;
	#---
	if($connectionSortString=="conn1"){
		$dbLibraryObject = new DBLibraryClass("localhost","root","","test");
		addGlobalResource($dbLibraryObject);
	}#if
	#---
	return $dbLibraryObject;
}
class DBLibraryClass {
    public $dbCon;
    public $host;
    public $user;
    public $pass;
    public $dbName;
	#---
    // 생성자 정의
    public function __construct($host="",$user="",$pass="",$dbName="") {
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->dbName = $dbName;
		#---
		$this->fnOpenDB();
		#---
        debugString("DBLibraryClass", "객체생성");
    }
	// 소멸자 정의
    public function __destruct() {
		$this->fnCloseDB();
		#---
        debugString("DBLibraryClass", "객체소멸");
    }
	#---
	public function fnOpenDB(){
		global $envVarMap;

		$host = $this->host;
		$user = $this->user;
		$pass = $this->pass;
		$dbName = $this->dbName;

		$this->dbCon = mysqli_connect($host, $user, $pass);

		//데이터베이스 설치 및 클라이언트 접속 캐릭터셋 설정
		mysqli_query($this->dbCon, "set session character_set_connection=utf8mb4;");
		mysqli_query($this->dbCon, "set session character_set_results=utf8mb4;");
		mysqli_query($this->dbCon, "set session character_set_client=utf8mb4;");
		mysqli_query($this->dbCon, "set names utf8mb4");

		//데이터베이스 접속정보 확인
		if(!$this->dbCon){
			echo "Database Connection Error!!";
		}else{
			#echo "Database Connection Success!!";
		}

		$selectdb = mysqli_select_db($this->dbCon, $dbName);

		//데이터베이스 선택 확인
		if(!$selectdb){
			echo "Database Select DB Error!!";
		}else{
			#echo "Database Select DB Success!!";
		}
	}
	public function fnCloseDB(){
		if($this->dbCon!=null){
			mysqli_close($this->dbCon);
			$this->dbCon = null;
		}
	}
	# $listArray = fnDBGetList("SELECT * FROM tb_board");
	public function fnDBGetList($sql=""){
		$returnArray = array();
		
		if($sql=="") return $returnArray;
		
		$result = mysqli_query($this->dbCon, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				array_push($returnArray,$row);
			}
		}
		
		return $returnArray;
	}
	# $rowData = fnDBGetRow("SELECT * FROM tb_board");
	public function fnDBGetRow($sql=""){
		$returnValue = null;
		
		if($sql=="") return $returnArray;
		
		$result = mysqli_query($this->dbCon, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				$returnValue = $row;
				break;
			}
		}
		
		return $returnValue;
	}
	# $listTotalCount = fnDBGetIntValue("SELECT count(*) FROM tb_board");
	public function fnDBGetIntValue($sql=""){
		$returnValue = 0;
		
		if($sql=="") return $returnValue;

		$result = mysqli_query($this->dbCon, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_row($result)) {
				$returnValue = intval($row[0]);
				break;
			}
		}
		
		return $returnValue;
	}
	# $listTotalCount = fnDBGetValue("SELECT count(*) FROM tb_board");
	public function fnDBGetValue($sql=""){
		$returnValue = 0;
		
		if($sql=="") return $returnValue;

		$result = mysqli_query($this->dbCon, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_row($result)) {
				$returnValue = $row[0];
				break;
			}
		}
		
		return $returnValue;
	}
	# $titleString = fnDBGetStringValue("SELECT title FROM tb_board");
	public function fnDBGetStringValue($sql=""){
		$returnValue = 0;
		
		if($sql=="") return $returnValue;

		$result = mysqli_query($this->dbCon, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_row($result)) {
				$returnValue = strval($row[0]);
				break;
			}
		}
		
		return $returnValue;
	}
	# $affectedRows = fnDBUpdate("UPDATE tb_board_article SET bda_title = 'aaaa' WHERE bda_seq = 0");
	public function fnDBUpdate($sql=""){
		$returnCount = 0;
		
		if($sql=="") return $returnCount;
		
		$result = mysqli_query($this->dbCon, $sql);
		if ($result) {
			$returnCount = mysqli_affected_rows($this->dbCon);
			#echo "성공적으로 " . $affected_rows . "개의 레코드가 수정되었습니다.";
		} else {
			echo "업데이트 쿼리 오류: " . mysqli_error($this->dbCon);
		}
		
		return $returnCount;
	}
}
?>