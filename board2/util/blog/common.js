//localStorage.setItem('kk','11')
//localStorage.getItem('kk')
//JSON.parse
//JSON.stringify

/**
상단과 하단의 Trim 빈라인을 제거한 값을 반환.
*/
function fnTrimOuterEmptyLines(text='') {
  // 줄 단위로 분리
  const lines = text.replaceAll('\r\n','\n').split('\n');

  // 맨 위 빈 줄 제거
  while (lines.length > 0 && lines[0].trim() === '') {
    lines.shift();
  }

  // 맨 아래 빈 줄 제거
  while (lines.length > 0 && lines[lines.length - 1].trim() === '') {
    lines.pop();
  }

  // 다시 합쳐서 반환
  return lines.join('\n');
}
/**
전체 Trim 빈라인을 제거한 값을 반환.
*/
function fnRemoveAllEmptyLines(text='') {
  return text
	.replaceAll('\r\n','\n')
    .split('\n')                // 줄 단위로 분리
    .filter(line => line.trim() !== '') // trim 후 빈 줄 제거
    .join('\n');                // 다시 합쳐서 반환
}
/**
textarea 스크롤 생길때, 윈도우에 스크롤 이벤트 전달 안되도록.
*/
function fnSetStopWinScrollOfTextarea(textarea=null) {
	if(textarea){
		textarea.addEventListener("wheel", function(e) {
		  const { scrollTop, scrollHeight, clientHeight } = textarea;
		  const delta = e.deltaY;

		  // 스크롤을 위로 올릴 때
		  if (delta < 0 && scrollTop <= 0) {
			e.preventDefault(); // 윈도우로 스크롤 전달 방지
		  }

		  // 스크롤을 아래로 내릴 때
		  if (delta > 0 && scrollTop + clientHeight >= scrollHeight) {
			e.preventDefault(); // 윈도우로 스크롤 전달 방지
		  }
		}, { passive: false });
	}//if
}
/**
입력박스 마지막 글자 위치에 커서이동.
*/
function fnMoveCursorToEnd(inputElement=null) {
  if(inputElement){
	  // 입력 요소에 포커스를 준다
	  inputElement.focus();
	  // 커서를 마지막 위치로 이동
	  const length = inputElement.value.length;
	  inputElement.selectionStart = length;
	  inputElement.selectionEnd = length;
  }//if
}
function fnNvl(checkValue,defaultValue){
	var returnValue = '';
	defaultValue = defaultValue===null || defaultValue===undefined ? '' : defaultValue;
	if (checkValue===null || checkValue===undefined){
		returnValue = defaultValue;
	} else {
		returnValue = checkValue;
	}//if
	return returnValue;
}