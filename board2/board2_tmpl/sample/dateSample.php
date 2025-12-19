<?php
include($_SERVER["DOCUMENT_ROOT"].'/{{cms.prefix}}/lib/_include.php');

$testDateString = "2025.02.05 17:43:21.132";
$test2DateString = "2025.02.07 19:35:16.581";
#---
$testDateObject = new DateLibraryClass($testDateString,$test2DateString);
#---
$formattedDateString = DateLibraryClass::getStandardDatetimeString($testDateString);
$formattedDateString2 = DateLibraryClass::getCurrentDatetimeFormatString("Y-m-d H:i:s.v");
$formattedDateString3 = DateLibraryClass::getSomeDatetimeFormatString($testDateString,"Y-m-d H:i:s.v");
$weekNumberOfMonth = DateLibraryClass::getWeekOfMonth($testDateString);
$datediffDayNumber = DateLibraryClass::getDateDiffForDay("2025-01-01 13:22:35","2025-01-02 13:22:35");
$datediffMonthNumber = DateLibraryClass::getDateDiffForMonth("2025-01-01 13:22:35","2025-02-02 13:22:35");
$datediffYearNumber = DateLibraryClass::getDateDiffForYear("2025-01-01 13:22:35","2027-01-02 13:22:35");
$datediffHourNumber = DateLibraryClass::getDateDiffForHour("2025-01-01 13:22:35","2025-01-02 16:22:35");
$datediffMinuteNumber = DateLibraryClass::getDateDiffForMinute("2025-01-01 13:22:35","2025-01-02 13:35:35");
$datediffSecondNumber = DateLibraryClass::getDateDiffForSecond("2025-01-01 13:22:35","2025-01-02 13:22:45");
$datediffFormattedString = DateLibraryClass::getDateDiffFormattedString("2025-01-01 13:22:35","2025-01-02 13:22:35","%a일 %h시간 %i분");
$datediffObject = DateLibraryClass::getDateDiffForObject("2025-01-01 13:22:35","2025-01-02 13:25:35");
#---
debugString("testDateString",$testDateString);
debugString("formattedDateString",$formattedDateString);
debugString("formattedDateString2",$formattedDateString2);
debugString("formattedDateString3",$formattedDateString3);
debugString("weekNumberOfMonth",$weekNumberOfMonth);
debugString("datediffDayNumber",$datediffDayNumber);
debugString("datediffMonthNumber",$datediffMonthNumber);
debugString("datediffYearNumber",$datediffYearNumber);
debugString("datediffHourNumber",$datediffHourNumber);
debugString("datediffMinuteNumber",$datediffMinuteNumber);
debugString("datediffSecondNumber",$datediffSecondNumber);
debugString("datediffFormattedString",$datediffFormattedString);
debugString("datediffObject(i)",$datediffObject->i);
#---
debugString("testDateObject.getBaseDatetimeWeekOfMonth",$testDateObject->getBaseDatetimeWeekOfMonth());
debugString("testDateObject.getBaseDatetimeDateDiffForDay",$testDateObject->getBaseDatetimeDateDiffForDay());
#---
$testDateObject = null;
?>