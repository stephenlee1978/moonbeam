<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of curl_multi
 *
 * @author liyan
 */
class curl_multi{
    private $url_list=array();
    private $curl_setopt=array(
        'CURLOPT_RETURNTRANSFER' => 1,//结果返回给变量
        'CURLOPT_HEADER' => 0,//是否需要返回HTTP头
        'CURLOPT_NOBODY' => 0,//是否需要返回的内容
        'CURLOPT_FOLLOWLOCATION' => 0,//自动跟踪
        'CURLOPT_TIMEOUT' => 6//超时时间(s)
    );
    function __construct($seconds=30){
        set_time_limit($seconds);
    }
    /*
     * 设置网址
     * @list 数组
     */
    public function setUrlList($list=array()){
        $this->url_list=$list;
    }
    /*
     * 设置参数
     * @cutPot array
     */
    public function setOpt($cutPot){
        $this->curl_setopt=$cutPot+$this->curl_setopt;
    }
    /*
     * 执行
     * @return array
     */
    public function execute(){
        $mh=curl_multi_init();
        foreach($this->url_list as $i=>$url){
            $conn[$i]=curl_init($url);
            foreach($this->curl_setopt as $key => $val){
                curl_setopt($conn[$i],preg_replace('/(CURLOPT_\w{1,})/ie','$0',$key),$val);
            }
            curl_multi_add_handle($mh,$conn[$i]);
        }
        $active=false;
        do{
            $mrc=curl_multi_exec($mh,$active);
        }while($mrc == CURLM_CALL_MULTI_PERFORM);

        while($active and $mrc == CURLM_OK){
            if(curl_multi_select($mh) != -1){
                do{
                    $mrc=curl_multi_exec($mh,$active);
                }while($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        $res=array();
        foreach($this->url_list as $i => $url){
            $res[$i]=curl_multi_getcontent($conn[$i]);
            curl_close($conn[$i]);
            curl_multi_remove_handle($mh,$conn[$i]);
        }
        curl_multi_close($mh);
        return $res;
    }
}

?>
