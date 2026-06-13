var calData = {};
var isCoupangDataInit = {};
//--- 단순 날짜/일정 데이터 셋팅
//calData['20260612'] = '쿠팡근무';
//calData['20260613'] = '쿠팡근무';
//calData['20260614'] = '휴일<br />약속';
//--- 복잡한 날짜/일정 데이터 셋팅함수로 처리
$(function(){
	//---
});
//--- date setting functions
//쿠팡근무 월, 주5일(월화수목금), 주4일(월화목금), 주단위로 번갈아가면서, 셋팅.
function fnSetCoupangWorkingDate(){
	let lastDateOfCurrentGridDateObject = 0;
	let isSun = false;
	let isSat = false;
	let isMon = false;
	let isTue = false;
	let isWed = false;
	let isThu = false;
	let isFri = false;
	let dayOfWeek = 0; //0: 일, 1: 월, 2: 화, 3: 수, 4: 목, 5: 금, 6: 토
	let firstDayIndex = 0;
	let currentGridDateYear = 0;
	let currentGridDateMonth = 0;
	let date = 0;
	let dateWeekNumberOfYear = 0;
	let dateWeekNumberOfMonth = 0;
	let currentGridDateMonthWeekNumberOfYear = 0;
	let weekCounterOfMonth = 1;
	//---
	lastDateOfCurrentGridDateObject = getLastDateOfThisDateObject(currentGridDate);
	currentGridDateYear = currentGridDate.getFullYear();
	currentGridDateMonth = currentGridDate.getMonth();
	firstDayIndex = new Date(currentGridDateYear, currentGridDateMonth, 1).getDay();
	currentGridDateMonthWeekNumberOfYear = getMonthWeekNumberOfYearByNumber(currentGridDateYear, currentGridDateMonth);
	//---
	for(date=1; date<=lastDateOfCurrentGridDateObject; date++){
		dayOfWeek = (firstDayIndex + date - 1) % 7;
		dateFormatString = getDateString(currentGridDateYear, currentGridDateMonth, date);
		dateWeekNumberOfYear = getWeekNumberOfYearByNumber(currentGridDateYear, currentGridDateMonth, date);
		dateWeekNumberOfMonth = dateWeekNumberOfYear - currentGridDateMonthWeekNumberOfYear + 1; //현재년월의 몇번째 주 번호
		//--- debug
		if(pageDebugFlag){console.info('[fnSetCoupangWorkingDate] lastDateOfCurrentGridDateObject : ',lastDateOfCurrentGridDateObject);}//if
		if(pageDebugFlag){console.info('[fnSetCoupangWorkingDate] dateFormatString : ',dateFormatString);}//if
		if(pageDebugFlag){console.info('[fnSetCoupangWorkingDate] dateWeekNumberOfMonth : ',dateWeekNumberOfMonth);}//if
		//---
		if (dayOfWeek === 0) {isSun = true;}else{isSun = false;} //if
		if (dayOfWeek === 1) {isMon = true;}else{isMon = false;} //if
		if (dayOfWeek === 2) {isTue = true;}else{isTue = false;} //if
		if (dayOfWeek === 3) {isWed = true;}else{isWed = false;} //if
		if (dayOfWeek === 4) {isThu = true;}else{isThu = false;} //if
		if (dayOfWeek === 5) {isFri = true;}else{isFri = false;} //if
		if (dayOfWeek === 6) {isSat = true;}else{isSat = false;} //if
		//---
		if(isSun && date!==1){weekCounterOfMonth++;}//if
		//---
		//주말 제외 및 (쿠팡 설정데이터 초기화 여부 체크)
		if(!isSun && !isSat && !isCoupangDataInit[dateFormatString]){
			if(weekCounterOfMonth % 2===1){
				//--- 홀수 주이면, 쿠팡 주5일 근무표 (월~금)
				if(isMon || isTue || isWed || isThu || isFri){
					calData[dateFormatString] = fnGetCombineString(calData[dateFormatString], '쿠팡근무', '<br />');
					isCoupangDataInit[dateFormatString] = true;
				}//if
			}else{
				//--- 짝수 주이면, 쿠팡 주4일 근무표, (월화목금)
				if(isMon || isTue || isThu || isFri){
					calData[dateFormatString] = fnGetCombineString(calData[dateFormatString], '쿠팡근무', '<br />');
					isCoupangDataInit[dateFormatString] = true;
				}//if
			}//if
		}//if
	}//for
}