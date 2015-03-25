<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CollectForm
 *
 * @author Administrator
 */
class CollectForm  extends CFormModel{

    public $city;
    
    public $list='0';
    
    public $url;
    
    public function rules() {
        return array(
            array('url', 'required', 'message' => '{attribute} 不能为空.'),
            array('city, list', 'safe'),
        );
    }

    public function getListData(){
        Yii::import('application.lib.productCollect.CollectClass');
        
        return CollectClass::getListData();
    }
    
    public function attributeLabels() {
        return array(
            'list' => '列表类型 ',
            'city' => '城市 ',
            'url'=>'地址'
        );
    }
    
    public function excute(){
        Yii::import('application.lib.productCollect.CollectClass');
        
        $obj = new CollectClass;
        return $obj->excute($this->url, $this->city, $this->list);
    }
}

?>
