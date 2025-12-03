<?php
/*
ResponseLibraryClass::setHeaderJson();
ResponseLibraryClass::setDisplayAllError(); 	# 서버에서 오류 확인 시, 임시 설정
*/
class ResponseLibraryClass
{
	public const CONST_RESULT_CODE_SUCCESS = "0";
	public const CONST_RESULT_CODE_UNKNOWN_ERROR = '1';
	public const CONST_RESULT_CODE_USER_ERROR = '2';
	public const CONST_RESULT_MESSAGE_SUCCESS = 'success';
	public const CONST_RESULT_MESSAGE_UNKNOWN_ERROR = 'unknown error';
	#---
	private $responseData = null;
	#---
	public function __construct()
	{
		$this->responseData = array();
		$this->setResponseData(
			ResponseLibraryClass::CONST_RESULT_CODE_UNKNOWN_ERROR,
			ResponseLibraryClass::CONST_RESULT_MESSAGE_UNKNOWN_ERROR
		);
	}
	public function __destruct() {
		#todo: 
	}
	#---
	public function getResponseData(){
		return $this->responseData;
	}
	public function setResponseData($resultCode="",$resultMessage=""){
		$this->responseData['resultCode'] = $resultCode;
		$this->responseData['resultMessage'] = $resultMessage;
	}
	public function setResponseUserErrorData($userErrorMessage=""){
		$this->responseData['resultCode'] = ResponseLibraryClass::CONST_RESULT_CODE_USER_ERROR;
		$this->responseData['resultMessage'] = $userErrorMessage;
	}
	#TODO: change method name to setResponseObjectData
	public function setResponseDataObject($resultObjectName="",$resultObject=null){
		$this->responseData[$resultObjectName] = $resultObject;
	}
	#TODO: change method name to setResponseValueData
	public function setResponseDataValue($resultValueName="",$resultValue=""){
		$this->responseData[$resultValueName] = $resultValue;
	}
	public function setSuccessResponseData(){
		$this->setResponseData(
			ResponseLibraryClass::CONST_RESULT_CODE_SUCCESS,
			ResponseLibraryClass::CONST_RESULT_MESSAGE_SUCCESS
		);
	}
	public function setUnknownErrorResponseData(){
		$this->setResponseData(
			ResponseLibraryClass::CONST_RESULT_CODE_UNKNOWN_ERROR,
			ResponseLibraryClass::CONST_RESULT_MESSAGE_UNKNOWN_ERROR
		);
	}
	#---
	public static function setHeaderJson(){
		header('Content-Type: application/json');
	}
	public static function setDisplayAllError(){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}
}
?>