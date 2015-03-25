<?php
/**
 * Description of ProductEditForm
 * 2014/12/3
 * @author stephen
 */
class ProductEditForm extends CFormModel {

    public $models = array();
    public $property;
    public $itemProps;
    public $auto = true;
    public $ids;
    public $sellercats; 

    public function rules() {
        return array(
            array('sellercats, models, property,itemProps', 'safe'),
        );
    }

    public function getDeclare() {
        if ($this->auto) {
            return '批量编辑' . count($this->models) . '个商品';
        } else {
            return $this->models[0]->productTitle;
        }
    }

    public function validate($attributes = null, $clearErrors = true) {
        Yii::import('lib.Functions');
        if ($this->auto && Functions::isOverSixTeenWord($this->property->subTitle)){
            $this->property->subTitle = Functions::subTitle($this->property->subTitle);
        }
        
        
        if (!$this->property->validate()) {
            $this->addErrors($this->property->getErrors());
            return false;
        }
        
        if(count($this->ids) <= 0){
            $this->addError('ids', '请选择编辑商品！');
            return false;
        }
        
        if ($this->scenario == 'load')
            return true;

        return true;
    }

    public function attributeLabels() {
        return array(
            'ids'=>'ids',
            'property' => 'property',
            'models' => 'models',
            'itemProps' => 'itemProps',
            'sellercats'=>'sellercats',
        );
    }

    public function setProductTitle() {
        $title = '';

        if ($this->auto)
            $title = '{自动定义}';
        else {
            if(isset($this->property->subTitle{0})){
                $title = $this->property->subTitle;
            }else{
                $title = $this->models[0]->getFullTitle();
            }
        }
        
        $title = str_replace(',', '', $title);
        $this->property->subTitle = $title;
    }

    public function getModelCount() {
        return count($this->ids);
    }

    public static function createEditForm($ids) {
        if (!is_array($ids) || count($ids) == 0)
            return null;

        $form = new ProductEditForm('load');
        $form->ids = $ids;
        foreach ($ids as $id) {
            $model = Product::model()->findByPk(trim((string) $id));
            if ($model !== null) {
                $form->models[] = $model;
            }
        }

        if ($form->getModelCount() == 1) {
            $form->auto = false;
        }
        //$idsdes = implode(',', $ids);
        $form->property = ProductProperty::createInProductIds($ids);
        
        if ($form->property === null)
            $form->property = new ProductProperty();

        $form->setProductTitle();
        
        $form->loadSellercats();

        return $form;
    }

    private function loadSellercats() {
        Yii::import('lib.Taobao');
        $this->sellercats = Taobao::getSellercatsList();
        if($this->sellercats === false){
            return;
        }
        
        //modify stephenlee 2014/12/3
        if(isset($this->property->sellercats{0})){
           $filters = explode (',', $this->property->sellercats);
           foreach ($this->sellercats->seller_cat as $cat){
               $cat->checked = in_array($cat->cid, $filters);
           }
        }/*else{
             Yii::import('lib.Functions');
            $filters[] = $this->models[0]->brandName;
            $filters[] = Functions::getYearMonth();
            
            $cids=array();
            $paths=array();
            foreach ($this->sellercats->seller_cat as $cat){
               $cat->checked = $this->filter($cat->name, $filters);
               if($cat->checked){
                   $cids[] = $cat->cid;
                   $paths[] = $cat->name;
               }
           }
           $this->property->sellercats = implode(',', $cids);
           $this->property->sellerPath = implode(',', $paths);
        }*/
    }
    
    private function filter($name, $filters) {
        foreach ($filters as $value) {
            $r = stripos($name, $value);
            if(stripos($name, $value) !== false){
                return true;
            }
        }
        return false;
    }
    
    public function getRedirectUrl($url) {

        $redirect = $url . '?';
        $field = array();
        foreach ($this->ids as $id) {
            $field[] = 'ids[]=' . $id;
        }
        $redirect .= implode('&', $field);

        return $redirect;
    }

    public function saveAutoProperty($pid, $inputs, $templates, $allprops, $colorprops, $sizeprops, $alias) {
        $product = Product::getProductInfo($pid);
        if ($product === false){
            Yii::log('Product::getProductInfo false');
            return false;
        }

        $this->property->subTitle = Product::getProductFullTitle($product['brandName'], $product['productTitle'], $product['percentOff'], $product['city']);

        //修改品牌
        if (isset($_POST['isbrand'])) {
            $brandvalue = $_POST['isbrand'];
            if (isset($inputs[$brandvalue])) {
                if(isset($templates[20000]) && is_array($templates[20000])){
                    foreach ($templates[20000] as $ckey => $cvalue) {
                        $templates[20000][$ckey] = $pid;
                        break;
                    }
                }
                $inputs[$brandvalue] = $product['brandName'];
                
            }
        }

        //修改货号
        if (isset($_POST['isno'])) {
            $novalue = $_POST['isno'];
            if (isset($inputs[$novalue])) {
                $inputs[$novalue] = $pid;
            }
        }

        //颜色
        $thiscolorprops = array();
        if (count($colorprops) > 0 && isset($_POST['iscolor'])) {
            $colorData = Skuattri::getColors($pid);
            foreach ($colorData as $key => $color) {
                if (isset($colorprops[$_POST['iscolor']][$key])) {
                    $colorvalue = $colorprops[$_POST['iscolor']][$key];
                    if (isset($alias[$colorvalue])) {
                        $thiscolorprops[$_POST['iscolor']][] = $colorvalue;
                        $alias[$colorvalue] = $color['value'];
                    }
                } else {
                    break;
                }
            }
        }

        //尺寸
        $thissizeprops = array();
        if (count($sizeprops) > 0 && isset($_POST['issize'])) {
            $sizeData = Skuattri::getSizes($pid);
            foreach ($sizeData as $key => $size) {
                if (isset($sizeprops[$_POST['issize']][$key])) {

                    $sizevalue = $sizeprops[$_POST['issize']][$key];
                    if (isset($alias[$sizevalue])) {
                        $thissizeprops[$_POST['issize']][] = $sizevalue;
                        $alias[$sizevalue] = $size['value'];
                    }
                } else {
                    break;
                }
            }
        }

        return $this->saveProperty($pid, $inputs, $templates, $allprops, $thiscolorprops, $thissizeprops, $alias);
    }

    public function saveProperty($pid, $inputs, $templates, $allprops, $colorprops, $sizeprops, $alias) {
        $property = ProductProperty::getObject($pid);
        $property->setAttributes($this->property->attributes);

        //自行输入值
        $inputPids = array();
        $inputStr = array();
        foreach ($inputs as $key => $value) {
            if(isset($value{0})){
                if(isset($templates[$key]) && is_array($templates[$key])){
                    foreach ($templates[$key] as $ckey => $cvalue) {
                        $inputStr[] = $value.';'.$ckey.';'.$cvalue;
                        break;
                    }
                }else{
                    $inputStr[] = $value;
                }
                $inputPids[] = $key;
            }
        }
        $property->inputPids = implode(",", $inputPids);
        $property->inputStr = implode(",", $inputStr);
        unset($inputPids);
        unset($inputStr);

        //属性
        $props = array();
        //属性别名
        $propertyAlias = array();

        foreach ($allprops as $key => $values) {
            if (is_array($allprops[$key])) {
                foreach ($allprops[$key] as $value) {
                    if (isset($value{0}) && $value != 0)
                        $props[] = $key . ':' . $value;
                }
            } else {
                if (isset($allprops[$key]{0}) && $allprops[$key] != 0)
                    $props[] = $key . ':' . $allprops[$key];
            }
        }


        //颜色属性
        $colorAlias = array();
        foreach ($colorprops as $key => $colors) {
            foreach ($colors as $colorvalue) {
                $propertyAlias[] = $key . ':' . $colorvalue . ':' . $alias[$colorvalue];
                $colorAlias[] = $key . ':' . $colorvalue . ';' . $alias[$colorvalue];
                $props[] = $key . ':' . $colorvalue;
            }
        }
        $property->colorProperties = implode(",", $colorAlias);
        unset($colorAlias);


        //尺寸
        $sizeAlias = array();
        foreach ($sizeprops as $skey => $sizes) {
            foreach ($sizes as $sizevalue) {
                $propertyAlias[] = $skey . ':' . $sizevalue . ':' . $alias[$sizevalue];
                $sizeAlias[] = $skey . ':' . $sizevalue . ';' . $alias[$sizevalue];
                $props[] = $skey . ':' . $sizevalue;
            }
        }
        $property->sizeProperties = implode(",", $sizeAlias);
        unset($sizeAlias);

        if (isset($propertyAlias{0}))
            $property->propertyAlias = implode(";", $propertyAlias) . ';';
        unset($propertyAlias);

        $property->props = implode(";", $props);
        unset($props);

        $property->productId = $pid;
        $property->userID = Yii::app()->user->id;
        $property->upid = $pid.$property->userID;
        
        Yii::import('lib.Functions');
        $property->subTitle = Functions::subTitle($property->subTitle);
        Yii::log('property->subTitle'.$property->subTitle);
        if (!$property->save()) {
            $this->addErrors($property->getErrors());
            return false;
        }
        return true;
    }

    //保存
    public function save() {
        $inputs = array();
        $props = array();
        $colorprops = array();
        $sizeprops = array();
        $alias = array();
        $templates = array();

        if (isset($_POST['input'])){
            $inputs = $_POST['input'];
        }
        if (isset($_POST['template'])){
            $templates = $_POST['template'];
        }
        if (isset($_POST['prop']))
            $props = $_POST['prop'];
        if (isset($_POST['cprop']))
            $colorprops = $_POST['cprop'];
        if (isset($_POST['sprop']))
            $sizeprops = $_POST['sprop'];
        if (isset($_POST['alias']))
            $alias = $_POST['alias'];

        if ($this->auto) {
            foreach ($this->ids as $id) {
                if($this->saveAutoProperty($id, $inputs, $templates, $props, $colorprops, $sizeprops, $alias) === false)
                        return false;
            }
            return true;
        }

        return $this->saveProperty($this->ids[0], $inputs, $templates, $props, $colorprops, $sizeprops, $alias);
    }

    //加载
    public function load() {
        Yii::import('lib.Taobao');
        $this->property->setScenario($this->scenario);

        $this->itemProps = Taobao::getItemProps($this->property->itemCats, '');

        if (!isset($this->itemProps->item_props)) {
            $this->addError('property', '取淘宝分类出错');
        } else {
            $this->appendProductInfo($this->itemProps->item_props->item_prop);
        }
    }

    private function appendProductInfo($item_prop) {
        if (!is_array($item_prop) || count($item_prop) == 0)
            return;

        if ($this->auto) {
            foreach ($item_prop as $item)
                $this->judgePropAuto($item);
        } else {
            foreach ($item_prop as $item)
                $this->judgeProp($item);
        }
    }

    private function judgeProp($item) {
        if (!isset($this->models[0]))
            return;
        $model = $this->models[0];

        if ($item->pid == 20000) {//品牌
            $item->is_brand = $model->brandName;
            if(isset($item->child_template)  && isset($item->child_template{0})){
                $item->is_child_template = $model->id;
            }
        } elseif ( (strpos($item->name, '尺') !== false || strpos($item->name, '码') !== false) && 
                $item->is_sale_prop && $item->is_enum_prop && $item->multi) 
        {//尺寸对应
            if (isset($item->prop_values)) {
                $item->is_size = true;
                $sizes = $model->getSizeArray();
                $scount = min(count($sizes), count($item->prop_values->prop_value));
                for ($s = 0; $s < $scount; $s++) {
                    if (isset($item->prop_values->prop_value[$s]) && isset($sizes[$s])) {
                        $item->prop_values->prop_value[$s]->aname = $sizes[$s]['value'];
                    } else {
                        break;
                    }
                }
            }
        } elseif ($item->is_color_prop && $item->is_sale_prop && $item->is_enum_prop && $item->multi) 
           {//颜色
            if (isset($item->prop_values)) {
                $item->is_color = true;
                $colors = $model->getSwatchesArray();
                $ccount = min(count($colors), count($item->prop_values->prop_value));
                for ($c = 0; $c < $ccount; $c++) {
                    if (isset($item->prop_values->prop_value[$c]) && isset($colors[$c])) {
                        $item->prop_values->prop_value[$c]->aname = $colors[$c]['value'];
                    } else {
                        break;
                    }
                }
            }
        } elseif (strpos($item->name, '货号') !== false || strpos($item->name, '款号') !== false || strpos($item->name, '型号') !== false) {
            $item->is_no = $model->id;
        }
    }

    private function judgePropAuto($item) {
        if ($item->pid == 20000) {//品牌
            $item->is_brand = '{自动定义}';
            if(isset($item->child_template) && isset($item->child_template{0})){
                $item->is_child_template = '{自动定义}';
            }
        } elseif ((strpos($item->name, '尺') !== false || strpos($item->name, '码') !== false) && 
                $item->is_sale_prop && $item->is_enum_prop && $item->multi) {//尺寸
            if (isset($item->prop_values)) {
                $item->is_size = true;
                foreach ($item->prop_values->prop_value as $prop_value) {
                    $prop_value->aname = $prop_value->name;
                }
            }
        } elseif ($item->is_color_prop && $item->is_sale_prop && $item->is_enum_prop && $item->multi) {//颜色
            if (isset($item->prop_values)) {
                $item->is_color = true;
                foreach ($item->prop_values->prop_value as $prop_value) {
                    $prop_value->aname = $prop_value->name;
                }
            }
        } elseif (strpos($item->name, '货号') !== false || strpos($item->name, '款号') !== false) {
            $item->is_no = '{自动定义}';
        }
    }

}

?>
