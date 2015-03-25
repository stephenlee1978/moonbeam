<?php

/**
 * Description of WProps
 *
 * @author stephenlee
 */
class WProps extends CWidget {

    public $itemProps;

    public function run() {

        if (is_object($this->itemProps) && isset($this->itemProps->item_props)) {
            $this->renderHtml();
        }
    }

    private function renderHtml() {
        $items = $this->itemProps->item_props->item_prop;

        echo '<table class="table table-striped">';
        foreach ($items as $item) {

            echo CHtml::openTag('tr', array('class' => 'props'));
            $this->renderTitleElement($item);
            $this->renderContentElement($item);
            echo CHtml::closeTag('tr');
        }
        echo '</table>';
    }

    private function renderTitleElement($item) {
        echo CHtml::openTag('th');
        echo $item->name;
        if ($item->must)
            echo "<font color=red> *</font>";
        echo CHtml::closeTag('th');
    }

    private function renderContentElement($item) {
        echo CHtml::openTag('td');
        if ($item->is_key_prop) {//关键属性
            $this->renderKeyValueElement($item);
        } elseif ($item->is_sale_prop) {//销售属性
            $this->renderSaleValueElement($item);
        } else {//非关键属性
            $this->renderNoneValueElement($item);
        }
        echo CHtml::closeTag('td');
    }

    private function renderKeyValueElement($item) {
        if ($item->is_enum_prop && $item->parent_pid == 0) {

            if (isset($item->child_template) && isset($item->child_template{0})) {
                echo CHtml::openTag('select', array(
                    'class' => 'required',
                    'name' => "prop[{$item->pid}]",
                    'pid' => $item->pid,
                    'child_template' => $item->child_template,
                ));
            } else {
                echo CHtml::openTag('select', array(
                    'class' => 'required',
                    'name' => "prop[{$item->pid}]",
                    'pid' => $item->pid,
                ));
            }

            echo '<option value="">请选择</option>';
            if (isset($item->prop_values)) {
                foreach ($item->prop_values->prop_value as $prop_value) {

                    if (isset($prop_value->is_parent) && $prop_value->is_parent) {

                        echo CHtml::openTag('OPTION', array(
                            'value' => $prop_value->vid,
                            'is_parent' => $prop_value->is_parent
                        ));
                    } else {
                        echo CHtml::openTag('OPTION', array(
                            'value' => $prop_value->vid,
                        ));
                    }
                    echo $prop_value->name;
                    echo CHtml::closeTag('OPTION');
                }
            }

            if ($item->is_input_prop) {
                if (isset($item->is_brand)) {//是否品牌
                    echo CHtml::openTag('OPTION', array('class' => 'required', 'value' => 0, 'selected' => 'selected'));
                } else {
                    echo CHtml::openTag('OPTION', array('class' => 'required', 'value' => 0,));
                }
                echo '自定义';
                echo CHtml::closeTag('OPTION');
            }

            echo CHtml::closeTag('select');

            if (isset($item->is_brand)) {//是否品牌
                echo CHtml::hiddenField('isbrand', $item->pid);
                echo CHtml::textField("input[{$item->pid}]", $item->is_brand, array('class' => 'required'));
                if (isset($item->child_template) && isset($item->child_template{0})) {
                    if (isset($item->is_child_template)) {
                        echo CHtml::textField("template[{$item->pid}][$item->child_template]", $item->is_child_template, array('class' => 'required'));
                    }
                }
            }
        } else {
            if (isset($item->is_brand)) {//是否品牌
                echo CHtml::hiddenField('isbrand', $item->pid);
                echo CHtml::textField("input[{$item->pid}]", $item->is_brand, array('class' => 'required'));
                if (isset($item->child_template) && isset($item->child_template{0})) {
                    if (isset($item->is_no)) {
                        echo CHtml::textField("template[{$item->pid}][$item->child_template]", $item->is_child_template, array('class' => 'required'));
                    }
                }
            } elseif ($item->is_no) {
                echo CHtml::hiddenField('isno', $item->pid);
                echo CHtml::textField("input[{$item->pid}]", $item->is_no, array('class' => 'required'));
            } else {
                echo CHtml::textField("input[{$item->pid}]", '', array('class' => 'required'));
            }
        }
    }

    //销售属性
    private function renderSaleValueElement($item) {

        if ($item->is_enum_prop && $item->multi) {

            if (isset($item->prop_values)) {
                if (isset($item->is_color))
                    echo CHtml::hiddenField('iscolor', $item->pid);
                elseif (isset($item->is_size)) {
                    echo CHtml::hiddenField('issize', $item->pid);
                }

                $custom = -1;
                foreach ($item->prop_values->prop_value as $prop_value) {

                    if (isset($item->is_color)) {
                        $ischeck = isset($prop_value->aname) ? true : false;
                        echo CHtml::checkBox("cprop[{$item->pid}][]", $ischeck, array('value' => $prop_value->vid));
                    } elseif (isset($item->is_size)) {
                        $ischeck = isset($prop_value->aname) ? true : false;
                        echo CHtml::checkBox("sprop[{$item->pid}][]", $ischeck, array('value' => $prop_value->vid));
                    } else {
                        echo CHtml::checkBox("prop[{$item->pid}][]", false, array('value' => $prop_value->vid));
                    }

                    
                    if (isset($prop_value->aname)) {
                        Yii::log('prop_value:'.$prop_value->aname.'$prop_value->aname:'.$item->is_allow_alias);
                        if($item->is_allow_alias){
                            echo CHtml::textField("alias[{$prop_value->vid}]", $prop_value->aname, array('style' => 'width:80px'));
                        }else{
                            echo CHtml::textField("alias[$custom]", $prop_value->aname, array('style' => 'width:80px'));
                            $custom -= 1;
                        }
                    } else {
                        echo CHtml::textField("alias[{$prop_value->vid}]", $prop_value->name, array('style' => 'width:80px'));
                    }
                }
            }
        }
    }

    private function renderNoneValueElement($item) {
        $class = '';
        if ($item->must)
            $class = 'required';

        if ($item->is_enum_prop && $item->multi) {
            if (isset($item->prop_values)) {
                if (isset($item->is_color))
                    echo CHtml::hiddenField('iscolor', $item->pid);
                elseif (isset($item->is_size)) {
                    echo CHtml::hiddenField('issize', $item->pid);
                }
                
                $custom = -1;
                foreach ($item->prop_values->prop_value as $prop_value) {
                    if (isset($prop_value->aname)) {
                        echo CHtml::checkBox("prop[{$item->pid}][]", true, array('value' => $prop_value->vid));
                        Yii::log('prop_value:'.$prop_value->aname.'$prop_value->aname:'.$item->is_allow_alias);
                        if($item->is_allow_alias){
                            echo CHtml::textField("alias[{$prop_value->vid}]", $prop_value->aname, array('style' => 'width:80px'));
                        }else{
                            echo CHtml::textField("alias[$custom]", $prop_value->aname, array('style' => 'width:80px'));
                            $custom -= 1;
                        }
                    } else {
                        echo CHtml::checkBox("prop[{$item->pid}][]", false, array('value' => $prop_value->vid));
                        echo CHtml::textField("alias[{$prop_value->vid}]", $prop_value->name, array('style' => 'width:80px'));
                    }
                }
            }
        } elseif ($item->is_enum_prop && $item->parent_pid == 0) {
            if (isset($item->child_template) && isset($item->child_template{0})) {
                echo CHtml::openTag('select', array(
                    'class' => $class,
                    'name' => "prop[{$item->pid}]",
                    'pid' => $item->pid,
                    'child_template' => $item->child_template,
                ));
            } else {
                echo CHtml::openTag('select', array(
                    'class' => $class,
                    'name' => "prop[{$item->pid}]",
                    'pid' => $item->pid,
                ));
            }

            echo '<option value="">请选择</option>';
            if (isset($item->prop_values)) {
                foreach ($item->prop_values->prop_value as $prop_value) {
                    if (isset($prop_value->is_parent) && $prop_value->is_parent) {

                        echo CHtml::openTag('OPTION', array(
                            'value' => $prop_value->vid,
                            'is_parent' => $prop_value->is_parent
                        ));
                    } else {
                        echo CHtml::openTag('OPTION', array(
                            'value' => $prop_value->vid,
                        ));
                    }
                    echo $prop_value->name;
                    echo CHtml::closeTag('OPTION');
                }
            }

            if ($item->is_input_prop) {
                if (isset($item->is_brand)) {//是否品牌
                    echo CHtml::openTag('OPTION', array('value' => 0, 'selected' => 'selected'));
                } else {
                    echo CHtml::openTag('OPTION', array('value' => 0,));
                }
                echo '自定义';
                echo CHtml::closeTag('OPTION');
            }
            echo CHtml::closeTag('select');
            if (isset($item->is_brand)) {//是否品牌
                echo CHtml::hiddenField('isbrand', $item->pid);
                echo CHtml::textField("input[{$item->pid}]", $item->is_brand, array('class' => 'required', 'name' => "input[{$item->pid}]"));
                if (isset($item->child_template) && isset($item->child_template{0})) {
                    if (isset($item->is_child_template)) {
                        echo CHtml::textField("template[{$item->pid}][$item->child_template]", $item->is_child_template, array('class' => 'required'));
                    }
                }
            }
        } else {
            if (isset($item->is_brand)) {//是否品牌
                echo CHtml::hiddenField('isbrand', $item->pid);
                echo CHtml::textField("input[{$item->pid}]", $item->is_brand, array('name' => "isbrand"));
                if (isset($item->child_template) && isset($item->child_template{0})) {
                    if (isset($item->is_child_template)) {
                        echo CHtml::textField("template[{$item->pid}][$item->child_template]", $item->is_child_template, array('class' => 'required'));
                    }
                }
            } elseif (isset($item->is_no)) {
                echo CHtml::hiddenField('isno', $item->pid);
                echo CHtml::textField("input[{$item->pid}]", $item->is_no, array('name' => "isno"));
            } else {
                echo CHtml::textField("input[{$item->pid}]", '', array('name' => "input[{$item->pid}]"));
            }
        }
    }

}

?>
