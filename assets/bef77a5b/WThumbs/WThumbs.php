<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WThumbs
 *
 * @author Administrator
 */
class WThumbs extends CWidget  {
    
    public $model;
    
    public $title;
    
    const MAXCOUNT = 4;
    
    public function init() {
        parent::init();
    }

    public function registerClientScript(){
        $options=array(
                    'id'=>'#'.$this->id,
	);
        $options=CJavaScript::encode($options);
        $cs=Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');

        $baseScriptUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('widgets')).'/WThumbs';
        $cs->registerScriptFile($baseScriptUrl.'/wThumbs.js',CClientScript::POS_END);
        $cs->registerCssFile($baseScriptUrl.'/wThumbs.css');
        $cs->registerScript(__CLASS__.'#'.$this->id,"jQuery('#$this->id').thumbs($options);");
    }
    
    public function run() {
        
        $this->registerClientScript();
        
        $this->renderHtml();
    }
    
    private function renderHtml() {
       $arryImgs = $this->model->getImages();
       if(!is_array($arryImgs) || count($arryImgs) <= 0) return;
        $id = CHtml::resolveValue($this->model, 'id');
       echo CHtml::openTag('div', array('id'=>$this->id));       
       //主图片
       echo CHtml::openTag('div', array('class'=>'product_pic'));
       echo  CHtml::image(Yii::app()->baseUrl . '/product/' . $id. '/' . $arryImgs[0]['image'], '', array('width' => 336, 'height' => 450));
       echo CHtml::closeTag('div');
       
       echo CHtml::openTag('ul', array('class'=>'tb-thumb tm-clear'));
        $count = count($arryImgs) > self::MAXCOUNT ? self::MAXCOUNT : count($arryImgs);
        for ($i = 0; $i < $count; $i++) {
            if($i === 0){
                echo CHtml::openTag('li', array('class'=>'tb-pic tb-selected'));
            }else{
                echo CHtml::openTag('li', array('class'=>'tb-pic'));
            }
            echo '<a href="#" class="thumbs">' . CHtml::image(Yii::app()->baseUrl . '/product/' . $id. '/' . $arryImgs[$i]['image'], '', array('width' => 55, 'height' => 55)) . '</a>';
            echo CHtml::closeTag('li');
        }
        echo CHtml::closeTag('ul');
        
        echo CHtml::closeTag('div');
    }
}

?>
