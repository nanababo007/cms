//--- 상수정의
const pageDebugFlag = false;
//const PAGE_KIND_MEMORY_CONST = 'memory';
//const PAGE_KIND_TEST_CONST = 'test';
//--- 변수정의
let currentGridDate = new Date();
let calendarJqueryObject = $('.calendar');
let calendarDaysJqueryObject = calendarJqueryObject.find('tbody');
let calendarTitleJqueryObject = $('#calendarTitle');
//--- 초기화
$(function(){
	fnInitPage();
});
//--- 함수정의
function fnInitPage(){
	setTimeout(function(){
		renderCalendar();
	},500);
}
function renderCalendar() {
	let today = null;
	let todayDateString = '';
	let todayWeekNumberOfYear = 0;
	let year = 0;
	let month = 0;
	let calendarDaysHtml = [];
	let calendarDaysHtmlString = '';
	let lastDayOfWeekNum = 0; //현재 달력년월의 마지막일의 요일번호 (0: 일, 1: 월, 2: 화, 3: 수, 4: 목, 5: 금, 6: 토)
	//--- debug
	if(pageDebugFlag){console.info('year : ',year);}//if
	if(pageDebugFlag){console.info('month : ',month);}//if
	//--- 복잡한 사용자 정의 날짜/일정 데이터 셋팅 함수호출.
	fnSetCoupangWorkingDate();
	//---
	today = new Date();
	todayDateString = getTodayDateString(today);
	todayWeekNumberOfYear = fnGetWeekNoOfMonthForDateString(todayDateString);
	year = currentGridDate.getFullYear();
	month = currentGridDate.getMonth();
	lastDayOfWeekNum = getLastDayOfWeekNum(year, month + 1);
	//---
	calendarTitleJqueryObject.html(`${year}년 ${(month + 1).toString().padStart(2, '0')}월`);
	//---
	// 요일 헤더 생성
	/*
	const weekDays = ['일', '월', '화', '수', '목', '금', '토'];
	weekDays.forEach((w, index) => {
		let className = 'day-header';
		if(index === 0) className += ' sun';
		if(index === 6) className += ' sat';
		calendarDays.innerHTML += `<div class="${className}">${w}</div>`;
	});
	*/
	//---
	// 이번달 첫날 요일 및 총 일수 구하기
	const firstDayIndex = new Date(year, month, 1).getDay();
	const lastDate = new Date(year, month + 1, 0).getDate();
	const lastWeekRestDaysCnt = 6 - lastDayOfWeekNum;
	//--- debug
	if(pageDebugFlag){console.info('lastDayOfWeekNum : ',lastDayOfWeekNum);}//if
	if(pageDebugFlag){console.info('lastWeekRestDaysCnt : ',lastWeekRestDaysCnt);}//if
	// 첫 주 공백 채우기
	for (let i = 0; i < firstDayIndex; i++) {
		if(i===0){calendarDaysHtml.push(`<tr>`);}//if
		calendarDaysHtml.push(`<td valign="top" class="day"><div></div></td>`);
	}//for
	// 날짜 채우기
	for (let date = 1; date <= lastDate; date++) {
		const dayOfWeek = (firstDayIndex + date - 1) % 7;
		let className = '';
		let dateFormatString = ''; //20260612
		let calendarDayHtml = '';
		let isSun = false;
		let isSat = false;
		let calDisplayString = '';
		let thisDateWeekNumberOfYear = '';
		//---
		dateFormatString = getDateString(year,month,date);
		//---
		calDisplayString = getNvlString(calData[dateFormatString]);
		//---
		thisDateWeekNumberOfYear = fnGetWeekNoOfMonthForDateString(dateFormatString);
		//---
		if (dayOfWeek === 0) {
			isSun = true;
		}else{
			isSun = false;
		} //if
		if (dayOfWeek === 6) {
			isSat = true;
		}else{
			isSat = false;
		} //if
		//---
		if (isSun) {className += ' sun';} //if
		if (isSat) {className += ' sat';} //if
		//---
		// 오늘 날짜 하이라이트
		if (year === today.getFullYear() && month === today.getMonth() && date === today.getDate()) {
			className += ' today';
		// 이번주 날짜 하이라이트 (일요일)
		} else if((todayWeekNumberOfYear-1)===thisDateWeekNumberOfYear && isSun) {
			className += ' thisweek';
		// 이번주 날짜 하이라이트 (월~토요일)
		} else if(todayWeekNumberOfYear===thisDateWeekNumberOfYear && !isSun) {
			className += ' thisweek';
			//--- debug
			if(pageDebugFlag){console.info(`[${dateFormatString}]`);}//if
			if(pageDebugFlag){console.info('- todayWeekNumberOfYear : ',todayWeekNumberOfYear);}//if
			if(pageDebugFlag){console.info('- thisDateWeekNumberOfYear : ',thisDateWeekNumberOfYear);}//if
		}//if
		//---
		if (isSun) {calendarDayHtml += `<tr>`;} //if
		calendarDayHtml += `<td valign="top" class="day ${className}">${date}<div>${calDisplayString}</div></td>`;
		if (isSat) {calendarDayHtml += `</tr>`;} //if
		calendarDaysHtml.push(calendarDayHtml);
	}//for
	// 마지막 주 공백 채우기
	for (let i = 0; i < lastWeekRestDaysCnt; i++) {
		calendarDaysHtml.push(`<td valign="top" class="day"><div></div></td>`);
		if(i===(lastWeekRestDaysCnt - 1)){calendarDaysHtml.push(`</tr>`);}//if
	}//for
	//---
	if(pageDebugFlag){
		calendarDaysHtmlString = calendarDaysHtml.join('\n');
	}else{
		calendarDaysHtmlString = calendarDaysHtml.join('');
	}//if
	//--- debug
	if(pageDebugFlag){console.info('calendarDaysHtml : ',calendarDaysHtmlString);}//if
	//--- display html
	calendarDaysJqueryObject.html(calendarDaysHtmlString);
}
function changeMonth(direction=0) {
	direction = Number(direction);
	//---
	if(direction===0){
		currentGridDate = new Date();
	}else{
		currentGridDate.setMonth(currentGridDate.getMonth() + direction);
	}//if
	//---
	renderCalendar();
}