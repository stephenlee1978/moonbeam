<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WGotop
 *
 * @author Administrator
 */
class WGotop extends CWidget {

    public $image = '/img/top.png';

    public function registerClientScript() {
        $options = array(
            'id' => '#' . $this->id,
        );
        $options = CJavaScript::encode($options);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');

        $baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('widgets')) . '/WGotop';

        $cs->registerScriptFile($baseScriptUrl . '/jquery.wGotop.js', CClientScript::POS_END);
        $cs->registerCssFile($baseScriptUrl . '/wGotop.css');
        $cs->registerScript(__CLASS__ . '#' . $this->id, "jQuery('#$this->id').gotop($options);");
    }

    public function run() {
            $this->registerClientScript();
            $this->renderHtml();
    }

    private function renderHtml() {

        echo CHtml::openTag('div', array('style' => 'display: none;', 'id' => $this->id));
        echo CHtml::openTag('a', array('class' => 'wgotop', 'herf' => 'javascript:void(0)', 'title' => '返回顶部'));
        echo '返回顶部';
        echo CHtml::closeTag('a');
        echo CHtml::closeTag('div');
    }

}
?>
