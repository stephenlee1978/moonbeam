<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WImages
 *
 * @author Administrator
 */
class WImages extends CWidget {

    //属性
    public $attribute;
    //模块
    public $model;
    //是否单一
    public $count = 1;
    
    public $fileID;
    
    public $actionUrl = '';

    public function registerClientScript() {
        if (!isset($this->actionUrl{0}))
            $this->actionUrl = Yii::app()->createUrl('image/delete');
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');

        $baseUrl = Yii::app()->assetManager->publish(dirname(__FILE__));

        $cs->registerScriptFile($baseUrl . '/wImages.js', CClientScript::POS_HEAD);
        $cs->registerCssFile($baseUrl . '/wImages.css');

        $cs->registerScript(__CLASS__, "actionurl='$this->actionUrl'", CClientScript::POS_END);
    }

    public function run() {

        //输入ID
        $this->id = CHtml::resolveName($this->model, $this->attribute);

        //当前值 
        $values = CHtml::resolveValue($this->model, $this->fileID);


        $this->registerClientScript();

        //echo $this->render('_wImages', array('id'=>$id, 'value'=>$value));
        $this->renderContent($values);
    }

    private function renderContent($values) {

        echo CHtml::openTag('div', array('class' => 'imgbox'));
        echo '<ul>';

        if (is_array($values)) {
            for ($n = 0; $n < $this->count; $n++) {
                if (isset($values[$n])) {
                    $this->renderImageList($values[$n]);
                }
                else
                    $this->renderImageList();
            }
        }else {
            for ($n = 0; $n < $this->count; $n++) {
                if ($n === 0) {
                    $this->renderImageList($values);
                }
                else
                    $this->renderImageList();
            }
        }


        echo '</ul>';
        echo CHtml::closeTag('div');
    }

    private function renderImageList($id = 0) {
        $src = File::getImageById($id);
        echo '<li>';
        echo CHtml::openTag('div', array('class' => 'addImgBtn'));
        if (isset($src{0})) {
            echo CHtml::image($src, '', array('data' => $id));
            echo "<div class='remove' style='cursor:pointer;' onClick='delpic(this)'></div>";
            echo CHtml::openTag('input', array('name' => get_class($this->model) . "[$this->attribute][]", 'type' => 'file', 'class' => 'file', 'style' => 'left:9999px !important;'));
        } else {
            echo CHtml::openTag('input', array('name' => get_class($this->model) . "[$this->attribute][]", 'type' => 'file', 'class' => 'file'));
        }

        echo CHtml::closeTag('input');
        echo CHtml::closeTag('div');
        echo '</li>';
    }

}

?>
