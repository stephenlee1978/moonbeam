<?php
/**
 * TOP API: taobao.fenxiao.login.user.get request
 * 
 * @author auto create
 * @since 1.0, 2014-03-04 13:06:59
 */
class FenxiaoLoginUserGetRequest
{
	
	private $apiParas = array();
	
	public function getApiMethodName()
	{
		return "taobao.fenxiao.login.user.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
