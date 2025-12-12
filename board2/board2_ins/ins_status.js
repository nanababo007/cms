//function goStepProc(){} //함수정의 필요, 함수 여러개 교체, procStatusObject.procIndex 사용
//var procStatusObject = new procStatusNewObject(); //변수명 고정, 객체 여러개 교체
//procStatusObject.runStatus(totalCount=0);
//procStatusObject.pauseStatus();
//procStatusObject.stopStatus();
//var displayText = procStatusObject.getDisplayText();
//var statusDisplayText = procStatusObject.getDisplayStatusText();
function procStatusNewObject(){
	this.intervalSecondsConst = 3;
	this.intervalMaxExecCountConst = 100;
	//---
	this.procFilePath = '';
	this.procRate = 0;
	this.procIndex = 0;
	this.procNumber = 0;
	this.totalCount = 0;
	this.intervalHandle = null;
	this.intervalMillSeconds = 0;
	//---
	this.doInitThisObject = function(){
		this.intervalMillSeconds = this.intervalSecondsConst * 1000;
		this.procNumber = 1;
	}
	this.clearIntervalOfStatus = function(){
		if(this.intervalHandle!==null){
			window.clearInterval(this.intervalHandle);
		}//if
		this.intervalHandle = null;
	}
	this.getExecCount = function(statusObj=null){
		if(statusObj!==null){
			return Math.min(statusObj.totalCount,statusObj.intervalMaxExecCountConst);
		}else{
			return 0;
		}//if
	}
	this.isExecIng = function(statusObj=null){
		if(statusObj!==null){
			if(statusObj.procIndex < this.getExecCount(statusObj)){
				return true;
			}else{
				return false;
			}//if
		}else{
			return false;
		}//if
	}
	this.createIntervalOfStatus = function(){
		this.clearIntervalOfStatus();
		//---
		this.intervalHandle = setInterval(function(){
			if(!procStatusObject.isExecIng(procStatusObject)){
				procStatusObject.stopStatus();
			}else{
				procStatusObject.procNumber = procStatusObject.procIndex + 1;
				//---
				goStepProc();
				//---
				procStatusObject.procIndex++;
				procStatusObject.procRate = Math.min(Math.round(procStatusObject.procNumber / procStatusObject.totalCount * 100),100);
			}//if
			//---
		},this.intervalMillSeconds);
	}
	this.pauseStatus = function(){
		this.clearIntervalOfStatus();
	}
	this.stopStatus = function(){
		this.procFilePath = '';
		this.procRate = 0;
		this.procIndex = 0;
		this.procNumber = 1;
		this.totalCount = 0;
		//---
		this.clearIntervalOfStatus();
	}
	this.runStatus = function(totalCount=0){
		this.totalCount = Number(getNvlString(totalCount,'0'));
		this.createIntervalOfStatus();
	}
	this.getDisplayText = function(){
		var returnString = '';
		var displayText = '';
		//---
		displayText = '({{procNumber}}/{{totalCount}}건, {{procRate}}%)';
		displayText = displayText.replaceAll('{{procNumber}}',getNvlString(this.procNumber));
		displayText = displayText.replaceAll('{{totalCount}}',getNvlString(this.totalCount));
		displayText = displayText.replaceAll('{{procRate}}',getNvlString(this.procRate));
		//---
		returnString = displayText;
		return returnString;
	}
	this.getDisplayStatusText = function(){
		var returnString = '';
		var statusDisplayText = '';
		//---
		statusDisplayText = '진행상태 : {{procFilePath}} 파일을 처리 했습니다.';
		statusDisplayText = statusDisplayText.replaceAll('{{procFilePath}}',getNvlString(this.procFilePath));
		//---
		returnString = statusDisplayText;
		return returnString;
	}
	//---
	this.doInitThisObject();
}