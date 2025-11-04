<?php
/*
$dateObject = new DateLibraryClass("2025-01-01");
$dateObject = new DateLibraryClass("2025-01-01","2025-01-31");
$dateObject = null;
#--- 날짜 static 메서드
DateLibraryClass::setBaseDatetime(new DateTime());
DateLibraryClass::setBaseDatetime(new DateTime("2025-01-01"));
DateLibraryClass::setBaseDatetimeString("2025-01-01",$formatString="Y-m-d");
DateLibraryClass::setAfterDatetime(new DateTime());
DateLibraryClass::setAfterDatetime(new DateTime("2025-01-01"));
DateLibraryClass::setAfterDatetimeString("2025-01-01",$formatString="Y-m-d")
DateLibraryClass::setAllDatetime($baseDatetimeObject=null,$afterDatetimeObject=null)
DateLibraryClass::setAllDatetimeString($baseDatetimeString="",$afterDatetimeString="")
#--- 
$formattedDateString = DateLibraryClass::getStandardDatetimeString("2025/01/01 17:43:21.357");
$formattedDateString = DateLibraryClass::getCurrentDatetimeFormatString("Y-m-d H:i:s.v");
$formattedDateString = DateLibraryClass::getSomeDatetimeFormatString("2025-01-01 17:43:21.357","Y-m-d H:i:s.v");
$weekNumberOfMonth = DateLibraryClass::getWeekOfMonth("2025-01-01");
$weekDateListOfMonth = DateLibraryClass::getWeekDateListOfMonth("2025-01-01",$engWeekName="mon");
$datediffNumber = DateLibraryClass::getDateDiffForDay("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffNumber = DateLibraryClass::getDateDiffForMonth("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffNumber = DateLibraryClass::getDateDiffForYear("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffNumber = DateLibraryClass::getDateDiffForHour("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffNumber = DateLibraryClass::getDateDiffForMinute("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffNumber = DateLibraryClass::getDateDiffForSecond("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffString = DateLibraryClass::getDateDiffFormattedString("2025-01-01 13:22:35","2025-01-02 13:22:35","%a일 %h시간 %i분");
$datediffObject = DateLibraryClass::getDateDiffForObject("2025-01-01 13:22:35","2025-01-02 13:22:35");
$korWeekName = DateLibraryClass::getDateKorWeekNameForEngWeekName("eng");
$korWeekName = DateLibraryClass::getCurrentKorWeekName();
$engWeekName = DateLibraryClass::getEngWeekName("2025-01-01");
$korWeekName = DateLibraryClass::getKorWeekName("2025-01-01");
#--- 날짜관련 소스코드
$now = new DateTime();
echo $now->format("Y-m-d H:i:s"); // 출력: 현재 날짜와 시간
#---
$date = new DateTime("2025-11-03");
echo $date->format("d/m/Y"); // 출력: 03/11/2025
#---
$timestamp = strtotime("2025-11-03"); // 문자열을 타임스탬프로 변환
$formattedDate = date("Y/m/d", $timestamp); // 원하는 포맷으로 변환
echo $formattedDate; // 출력: 2025/11/03
*/
class DateLibraryClass {
	private $baseDatetime = null; # DateTime Object
	private $afterDatetime = null; # DateTime Object
	#---
	public function __construct($baseDatetimeString="",$afterDatetimeString="") {
		if($baseDatetimeString==""){
			$this->setBaseDatetimeString();
		}else{
			$this->setBaseDatetimeString($baseDatetimeString);
		}#if
		#---
		if($afterDatetimeString==""){
			$this->setAfterDatetimeString();
		}else{
			$this->setAfterDatetimeString($afterDatetimeString);
		}#if
	}
	public function __destruct() {
		$this->baseDatetime = null;
		$this->afterDatetime = null;
    }
	#--- object method
	public function setBaseDatetime($datetimeObject=null){
		if($datetimeObject==null){
			$this->baseDatetime = new DateTime();
		}else{
			$this->baseDatetime = $datetimeObject;
		}#if
	}
	public function setBaseDatetimeString($datetimeString="",$formatString="Y-m-d H:i:s.v"){
		$editDatetimeString = "";
		#---
		if($datetimeString==""){
			$editDatetimeString = self::getCurrentDatetimeFormatString($formatString);
		}else{
			$editDatetimeString = self::getStandardDatetimeString($datetimeString);
		}#if
		#---
		$this->baseDatetime = new DateTime($editDatetimeString);
	}
	public function setAfterDatetime($datetimeObject=null){
		if($datetimeObject==null){
			$this->afterDatetime = new DateTime();
		}else{
			$this->afterDatetime = $datetimeObject;
		}#if
	}
	public function setAfterDatetimeString($datetimeString="",$formatString="Y-m-d H:i:s.v"){
		$editDatetimeString = "";
		#---
		if($datetimeString==""){
			$editDatetimeString = self::getCurrentDatetimeFormatString($formatString);
		}else{
			$editDatetimeString = self::getStandardDatetimeString($datetimeString);
		}#if
		#---
		$this->afterDatetime = new DateTime($editDatetimeString);
	}
	public function setAllDatetime($baseDatetimeObject=null,$afterDatetimeObject=null){
		$this->setBaseDatetime($baseDatetimeObject);
		$this->setAfterDatetime($afterDatetimeObject);
	}
	public function setAllDatetimeString($baseDatetimeString="",$afterDatetimeString=""){
		$this->setBaseDatetime($baseDatetimeObject);
		$this->setAfterDatetime($afterDatetimeObject);
	}
	#---
	public function getBaseDatetime(){
		return this->$baseDatetime;
	}
	public function getBaseDatetimeString($format="Y-m-d"){
	}
	public function getAfterDatetime(){
	}
	public function getAfterDatetimeString($format="Y-m-d"){
	}
	public function getBaseDatetimeWeekOfMonth(){
		$baseDatetimeFormatString = "";
		#---
		$baseDatetimeFormatString = $this->baseDatetime->format("Y-m-d");
		#---
		return self::getWeekOfMonth($baseDatetimeFormatString);
	}
	public function getBaseDatetimeDateDiffForDay(){
		$baseDatetimeFormatString = "";
		$afterDatetimeFormatString = "";
		#---
		$baseDatetimeFormatString = $this->baseDatetime->format("Y-m-d");
		$afterDatetimeFormatString = $this->afterDatetime->format("Y-m-d");
		#---
		return self::getDateDiffForDay($baseDatetimeFormatString,$afterDatetimeFormatString);
	}
/*
$datediffNumber = DateLibraryClass::getDateDiffForMonth("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffNumber = DateLibraryClass::getDateDiffForYear("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffNumber = DateLibraryClass::getDateDiffForHour("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffNumber = DateLibraryClass::getDateDiffForMinute("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffNumber = DateLibraryClass::getDateDiffForSecond("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffString = DateLibraryClass::getDateDiffFormattedString("2025-01-01 13:22:35","2025-01-02 13:22:35","%a일 %h시간 %i분");
$datediffObject = DateLibraryClass::getDateDiffForObject("2025-01-01 13:22:35","2025-01-02 13:22:35");
*/

	#--- static method
	# 모든 날짜 계산 및 처리 가공 함수들을 static 으로 구현하고, 상단 내부 변수 가공/계산 등에,  
	# 내부 static  변수 사용, self::함수명(인자); 형태로 사용. 모든 필요 데이터들은, 
	# 각  static 메서드에서 인자로 받아서 처리. 클래스 내부 변수 사용불가.
	
	# 날짜문자열 값을, 표준 날짜값으로 변환하는 함수. 2025/01/01 => 2025-01-01 표준 문자열 형태로 변환.
	public static function getStandardDatetimeString($datetimeString=""){
		$returnString = "";
		$editDatetimeString = "";
		$timeDivStringPos = false;
		$mllsDivStringPos = false;
		$mllsPrevString = "";
		$mllsAfterString = "";
		#---
		$editDatetimeString = trim($datetimeString);
		$editDatetimeString = str_replace("/","-",$editDatetimeString);
		$editDatetimeString = str_replace(".","-",$editDatetimeString);
		$editDatetimeString = str_replace("~","-",$editDatetimeString);
		$editDatetimeString = str_replace("  "," ",$editDatetimeString);
		#---
		$timeDivStringPos = strpos($editDatetimeString,":");
		$mllsDivStringPos = strrpos($editDatetimeString,"-");
		if($timeDivStringPos !== false && $mllsDivStringPos !== false && $timeDivStringPos < $mllsDivStringPos) {
			$mllsPrevString = substr($editDatetimeString, 0, $mllsDivStringPos);
			$mllsAfterString = substr($editDatetimeString, $mllsDivStringPos+1);
			$editDatetimeString = $mllsPrevString.".".$mllsAfterString;
		}#if
		#---
		$returnString = $editDatetimeString;
		return $returnString;
	}
	# 현재 날짜를, 특정 문자열 포맷으로 반환
	public static function getCurrentDatetimeFormatString($formatString="Y-m-d H:i:s"){
		$returnString = "";
		$now = null;
		$nowDatetimeString = "";
		#---
		if($formatString==""){return "";}#if
		#---
		$now = new DateTime();
		$nowDatetimeString = $now->format($formatString);
		#---
		$returnString = $nowDatetimeString;
		return $returnString;
	}
	# 특정 날짜를, 특정 문자열 포맷으로 반환
	public static function getSomeDatetimeFormatString($datetimeString="",$formatString="Y-m-d H:i:s"){
		$returnString = "";
		$someDatetime = null;
		$someDatetimeString = "";
		$editDatetimeString = "";
		#---
		if($datetimeString==""){return "";}#if
		if($formatString==""){return "";}#if
		#---
		$editDatetimeString = self::getStandardDatetimeString($datetimeString);
		$someDatetime = new DateTime($editDatetimeString);
		$someDatetimeString = $someDatetime->format($formatString);
		#---
		$returnString = $someDatetimeString;
		return $returnString;
	}
	# 특정 날짜의, 해당 월의 특정요일의 일자배열 반환
	public static function getWeekDateListOfMonth($dateString="",$engWeekName="") {
		if(trim($dateString)==""){return "";}#if
		if(trim($engWeekName)==""){return "";}#if
		#---
		$weekDateArray = array();
		$weekOfMonth = 0;
		$dateString = self::getStandardDatetimeString($dateString);
		$date = new DateTime($dateString);
		$day = (int)$date->format('j'); // 일(day)
		$month = (int)$date->format('n'); // 월(month)
		$year = (int)$date->format('Y'); // 연(year)
		$lastDaysOfMonth = (int)$date->format('t');
		#---
		for($dayIndex=1;$dayIndex<=$lastDaysOfMonth;$dayIndex++){
			$monthItemDate = new DateTime("${year}-${month}-${dayIndex}");
			if(strtolower($monthItemDate->format("D"))==$engWeekName){
				array_push($weekDateArray,$monthItemDate->format('Y-m-d'));
				$weekOfMonth++;
			}#if
		}#for lastDaysOfMonth
		#---
		return $weekDateArray;
	}
	# 특정 날짜의, 해당 월의 몇주차인지 숫자반환
	public static function getWeekOfMonth($dateString="") {
		if(trim($dateString)==""){return "";}#if
		$dateString = self::getStandardDatetimeString($dateString);
		$date = new DateTime($dateString);
		$day = (int)$date->format('j'); // 일(day)
		$month = (int)$date->format('n'); // 월(month)
		$year = (int)$date->format('Y'); // 연(year)
		// 해당 월의 첫 번째 날
		$firstDayOfMonth = new DateTime("$year-$month-01");
		$firstDayWeekday = (int)$firstDayOfMonth->format('N'); // 1 (월요일) ~ 7 (일요일)
		// 첫 주의 시작일 기준으로 주차 계산
		$weekOfMonth = intval(($day + $firstDayWeekday - 1) / 7) + 1;
		return $weekOfMonth;
	}
	# 두 특정 날짜의, 일수 차이값
	public static function getDateDiffForDay($date1String="",$date2String="") {
		if(trim($date1String)==""){return 0;}#if
		if(trim($date2String)==""){return 0;}#if
		$date1String = self::getStandardDatetimeString($date1String);
		$date2String = self::getStandardDatetimeString($date2String);
		$date1 = new DateTime($date1String);
		$date2 = new DateTime($date2String);
		$diff = $date1->diff($date2);
		return $diff->days;
	}
	# 두 특정 날짜의, 월수 차이값
	public static function getDateDiffForMonth($date1String="",$date2String="") {
		if(trim($date1String)==""){return 0;}#if
		if(trim($date2String)==""){return 0;}#if
		$date1String = self::getStandardDatetimeString($date1String);
		$date2String = self::getStandardDatetimeString($date2String);
		$date1 = new DateTime($date1String);
		$date2 = new DateTime($date2String);
		$diff = $date1->diff($date2);
		return $diff->m;
	}
	# 두 특정 날짜의, 년수 차이값
	public static function getDateDiffForYear($date1String="",$date2String="") {
		if(trim($date1String)==""){return 0;}#if
		if(trim($date2String)==""){return 0;}#if
		$date1String = self::getStandardDatetimeString($date1String);
		$date2String = self::getStandardDatetimeString($date2String);
		$date1 = new DateTime($date1String);
		$date2 = new DateTime($date2String);
		$diff = $date1->diff($date2);
		return $diff->y;
	}
	# 두 특정 날짜의, 시간 차이값
	public static function getDateDiffForHour($date1String="",$date2String="") {
		if(trim($date1String)==""){return 0;}#if
		if(trim($date2String)==""){return 0;}#if
		$date1String = self::getStandardDatetimeString($date1String);
		$date2String = self::getStandardDatetimeString($date2String);
		$date1 = new DateTime($date1String);
		$date2 = new DateTime($date2String);
		$diff = $date1->diff($date2);
		return $diff->h;
	}
	# 두 특정 날짜의, 분 차이값
	public static function getDateDiffForMinute($date1String="",$date2String="") {
		if(trim($date1String)==""){return 0;}#if
		if(trim($date2String)==""){return 0;}#if
		$date1String = self::getStandardDatetimeString($date1String);
		$date2String = self::getStandardDatetimeString($date2String);
		$date1 = new DateTime($date1String);
		$date2 = new DateTime($date2String);
		$diff = $date1->diff($date2);
		return $diff->i;
	}
	# 두 특정 날짜의, 초 차이값
	public static function getDateDiffForSecond($date1String="",$date2String="") {
		if(trim($date1String)==""){return 0;}#if
		if(trim($date2String)==""){return 0;}#if
		$date1String = self::getStandardDatetimeString($date1String);
		$date2String = self::getStandardDatetimeString($date2String);
		$date1 = new DateTime($date1String);
		$date2 = new DateTime($date2String);
		$diff = $date1->diff($date2);
		return $diff->s;
	}
	# 두 특정 날짜의, 차이값 포맷문자열
	public static function getDateDiffFormattedString($date1String="",$date2String="",$formatString="%a일 %h시간 %i분") {
		if(trim($date1String)==""){return "";}#if
		if(trim($date2String)==""){return "";}#if
		if(trim($formatString)==""){return "";}#if
		$date1String = self::getStandardDatetimeString($date1String);
		$date2String = self::getStandardDatetimeString($date2String);
		$date1 = new DateTime($date1String);
		$date2 = new DateTime($date2String);
		$diff = $date1->diff($date2);
		return $diff->format($formatString);
	}
	# 두 특정 날짜의, 차이정보 객체
	public static function getDateDiffForObject($date1String="",$date2String="") {
		if(trim($date1String)==""){return null;}#if
		if(trim($date2String)==""){return null;}#if
		$date1String = self::getStandardDatetimeString($date1String);
		$date2String = self::getStandardDatetimeString($date2String);
		$date1 = new DateTime($date1String);
		$date2 = new DateTime($date2String);
		$diff = $date1->diff($date2);
		return $diff;
	}
	# 영문 요일명을 받아서, 한글 요일명 반환
	public static function getDateKorWeekNameForEngWeekName($engWeekName="") {
		$engWeekName = strtolower($engWeekName);
		if($engWeekName=="mon"){
			return "월";
		}else if($engWeekName=="tue"){
			return "화";
		}else if($engWeekName=="wed"){
			return "수";
		}else if($engWeekName=="thu"){
			return "목";
		}else if($engWeekName=="fri"){
			return "금";
		}else if($engWeekName=="sat"){
			return "토";
		}else if($engWeekName=="sun"){
			return "일";
		}else{
			return "";
		}#if
	}
	# 금일자 요일 한글명
	public static function getCurrentKorWeekName() {
		$weekdaysArray = ['일', '월', '화', '수', '목', '금', '토'];
		$weekdayString = $weekdaysArray[date('w')];
		return $weekdayString;
	}
	# 금일자 요일 소문자 영문명
	public static function getEngWeekName($dateString="") {
		if($dateString==""){return "";}#if
		$dateString = self::getStandardDatetimeString($dateString);
		$date = new DateTime($dateString);
		$dayName = strtolower($date->format('D'));
		return $dayName;
	}
	# 금일자 요일 소문자 한글명
	public static function getKorWeekName($dateString="") {
		if($dateString==""){return "";}#if
		$engWeekNameString = self::getEngWeekName($dateString);
		$korWeekNameString = self::getDateKorWeekNameForEngWeekName($engWeekNameString);
		return $korWeekNameString;
	}
}
?>