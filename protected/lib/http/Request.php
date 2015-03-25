<?php

/**
 * WMS仓库请求
 *
 * @author stephen
 */
class Request {

    //参数
    protected $_parameters = array();

    function __destruct() {
        unset($this->_parameters);
    }

    public function __get($name) {
        if (isset($this->_parameters[$name]))
            return $this->_parameters[$name];

        return false;
    }

    public function __set($name, $value) {
      
		$this->_parameters[$name] = $value;
        
    }

    public function __isset($name) {
        if (isset($this->_parameters[$name]))
            return true;
        return false;
    }

    public function getParameters() {
        return $this->_parameters;
    }
    
    public function clear(){
        $this->_parameters = array();
    }

}
