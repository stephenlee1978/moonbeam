<?php
/**
 * Description of WSellercats
 * 2014/12/3
 * @author stephenlee
 */
class WSellercats extends CWidget {

    public $model;
    public $sellercats;

    public function registerClientScript() {
        $options = array(
            'id' => '#' . $this->id,
        );
        $options = CJavaScript::encode($options);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');


        $baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('widgets')) . '/WSellercats';

        $cs->registerScriptFile($baseScriptUrl . '/jquery.wSellercats.js', CClientScript::POS_END);
        $cs->registerScript(__CLASS__ . '#' . $this->id, "jQuery('#$this->id').sellercats($options);");
    }

    public function run() {
        $this->renderHtml();
        
        $this->registerClientScript();
        
    }

    private function renderSellercats() {
        $divs = null;
        $parent_cid = '0';
        foreach ($this->sellercats->seller_cat as $cat) {
            if ($cat->parent_cid == '0') {
                $parent_cid = '0';
                unset($divs);
                $this->createchild($cat->cid, $cat->name, $cat);
            } else {
                if ($parent_cid != $cat->parent_cid) {
                    $divs[] = '-';
                    $parent_cid = $cat->parent_cid;
                }
                $this->createparentchild($parent_cid, $cat->cid, $cat->name, $cat, $divs);
            }
        }
    }

    private function renderSellercatsNew() {
        $divs = array();
        $parent_cid = '0';
        foreach ($this->sellercats->seller_cat as $cat) {
            $obj = new stdClass();
            $obj->cid = $cat->cid;
            $obj->name = $cat->name;
            $obj->checked = $cat->checked;
            if ($cat->parent_cid == '0') {
                $divs['0'][$cat->cid] = $obj;
            } else {
                $divs[$cat->parent_cid][$cat->cid] = $obj;
            }
        }
        $this->createTrees($divs);
    }

    private function createTrees($divs) {
       if(!isset($divs['0'])) return;
       
        foreach ($divs['0'] as $value) {
            $check = "";
                    if (isset($value->checked)) {
                        $check = "checked='checked'";
                    }
                    
            if (isset($divs[$value->cid])) {
                echo "<li><input parentcid='0' {$check} style='float: none;' type=checkbox name=scid[] title={$value->name} value={$value->cid}>{$value->name}</input>";
                echo "<ul>";
                foreach ($divs[$value->cid] as $key => $childvalue) {
                        $childcheck = "";
                        if (isset($childvalue->checked)) {
                            $childcheck = "checked='checked'";
                        }
                        echo "<li><input parentcid={$key} {$childcheck} style='float: none;' type=checkbox name=scid[] title={$childvalue->name} value={$childvalue->cid}>{$childvalue->name}</input><ul>";
                    }

                    echo '</ul></li>';
                }else{
                    echo "<li><input parentcid='0' {$check} style='float: none;' type='text' name=scid[] title={$value->name} value={$value->cid}>{$value->name}</input></li>";
                }
                
            }
    }

    private function createparentchild($parent_cid, $cid, $name, $cat, $divs = array()) {
        $divcontent = implode('', $divs);
        if (isset($cat->checked) && $cat->checked){
            echo "<li>{$divcontent}<input parentcid={$parent_cid} checked='checked' style='float: none;' type=checkbox name=scid[] title={$name} value={$cid}>{$name}</input></li>";
        } else {
            echo "<li>{$divcontent}<input parentcid={$parent_cid} style='float: none;' type=checkbox name=scid[] title={$name} value={$cid}>{$name}</input></li>";
        }
    }

    private function createchild($cid, $name, $cat, $divs = array()) {
        $divcontent = implode('', $divs);
        if (isset($cat->checked) && $cat->checked){
            echo "<li>{$divcontent}<input checked='checked' style='float: none;' type=checkbox name=scid[] title={$name} value={$cid}>{$name}</input></li>";
        }else {
            echo "<li>{$divcontent}<input style='float: none;' type=checkbox name=scid[] title={$name} value={$cid}>{$name}</input></li>";
        }
    }

    private function renderHtml() {
        echo CHtml::openTag('div', array('id' => $this->id));

        echo CHtml::openTag('span');
        echo CHtml::activehiddenField($this->model, 'sellercats');
        echo CHtml::activeTextField($this->model, 'sellerPath');
        echo CHtml::link('选择店铺分类', '', array('class' => 'btn open_cats'));
        echo CHtml::closeTag('span');

        echo CHtml::openTag('div', array('class' => 'cat_divs', 'style' => 'display:none'));
        echo CHtml::openTag('span');
        echo CHtml::link('保存店铺分类', '', array('class' => 'btn save_cats_btn'));
        echo CHtml::link('关闭', '', array('class' => 'btn close_cats_btn'));
        echo CHtml::closeTag('span');

        echo CHtml::openTag('div', array('style' => 'width:100%;max-height:350px;overflow: auto;position: relative;'));
        echo CHtml::openTag('ul', array('class' => 'taobaoSCDiv unstyled well'));
        if($this->sellercats !== false && $this->sellercats->seller_cat !== null){
            $this->renderSellercats();
        }
        echo CHtml::closeTag('ul');
        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');

        echo CHtml::closeTag('div');
    }

}

?>
