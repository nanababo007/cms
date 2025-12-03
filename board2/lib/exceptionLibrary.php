<?php
class ExceptionLibraryClass
{
	private $procedureResultFlagString = 'error';
	private $procedureResultErrorCode = 'unknown_error';
	private $procedureResultMessage = '알수없는 오류';
	#---
	public function __construct($procedureResultFlagString=null, $procedureResultErrorCode=null, $procedureResultMessage=null)
	{
		if($procedureResultFlagString!=null or $procedureResultErrorCode!=null or $procedureResultMessage!=null){
			$this->procedureResultFlagString = $procedureResultFlagString;
			$this->procedureResultErrorCode = $procedureResultErrorCode;
			$this->procedureResultMessage = $procedureResultMessage;
		}#if
	}
	public function __destruct() {
		$this->procedureResultFlagString = null;
		$this->procedureResultErrorCode = null;
		$this->procedureResultMessage = null;
	}
	#---
	public function setErrorInformation($procedureResultFlagString="", $procedureResultErrorCode="", $procedureResultMessage=""){
		$this->procedureResultFlagString = $procedureResultFlagString;
		$this->procedureResultErrorCode = $procedureResultErrorCode;
		$this->procedureResultMessage = $procedureResultMessage;
	}
	public function setResultFlagString($procedureResultFlagString=""){
		$this->procedureResultFlagString = $procedureResultFlagString;
	}
	public function setErrorCode($procedureResultErrorCode=""){
		$this->procedureResultErrorCode = $procedureResultErrorCode;
	}
	public function setResultMessage($procedureResultMessage=""){
		$this->procedureResultMessage = $procedureResultMessage;
	}
	#---
	public function getResultFlagString(){
		return $this->procedureResultFlagString;
	}
	public function getErrorCode(){
		return $this->procedureResultErrorCode;
	}
	public function getResultMessage(){
		return $this->procedureResultMessage;
	}
}
?>