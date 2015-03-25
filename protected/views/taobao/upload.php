<?php 
    header("Content-Type:text/html;charset=utf-8");
    Yii::import('lib.Taobao');
    Yii::import('lib.Functions');
    if(is_array($ids)){
        foreach ($ids as $id) {
            $num_iid = Taobao::uploadProduct($id);
            if($num_iid === false){
                Functions::message(CHtml::link('返回商品编辑', array('taobao/edit/id/'.$id)));
                Functions::message("<br/>商品上传失败！");
            }else{
                Functions::message("<br/>商品上传成功！");
            }
            Functions::message("-----------------------------------------------------");
        }
    }
    Functions::message(CHtml::link('返回商品列表', array('product/index')));
?>