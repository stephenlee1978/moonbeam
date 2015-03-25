<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 上传商品搜索功能模块
 *
 * @author stephen
 */
class UploadedSearch  extends CFormModel{
    //采集站点
    public $site;
    //商品货号
    public $pid;
    //商品标题
    public $ptitle;
    //上传
    public $suploadtime;
    //上传
    public $euploadtime;
    //品牌
    public $brand;

    public function rules() {
        return array(
            array('site, pid, ptitle, suploadtime, suploadtime, brand', 'safe'),
        );
    }

    public function attributeLabels() {
        return array(
            'site' => '站点',
            'pid' => '货号 ',
            'ptitle' => '商品标题 ',
            'suploadtime' => '开始时间 ',
            'suploadtime' => '结束时间 ',
            'brand' => '品牌 ',
        );
    }
    protected function getStatuImg($data, $row) {
        if(strcmp($data['approveStatus'], 'onsale')==0){
            echo CHtml::image(Yii::app()->baseUrl . '/img/onsale.png' ,'', array('width' => 16, 'height' => 16));
        }else{
            echo CHtml::image(Yii::app()->baseUrl . '/img/instock.png' ,'', array('width' => 16, 'height' => 16));
        }
    }
    
    protected function getProductFristImg($data, $row){
        $content = '';
        $image = ProductImages::getFirstImage($data['id']);
        if($image!==false){
            echo CHtml::openTag('a', array('href'=>CHtml::normalizeUrl(array('product/view/id/' . $data['id'])), 'target'=>'_blank') );
            echo CHtml::image(Yii::app()->params['product_path'] . $data['id'] . '/' .$image, '', array('width' => 80, 'height' => 80));
            echo '</a>';
        }else{
            echo CHtml::image('http://placehold.it/80x80', '', array('width' => 80, 'height' => 80));
        }
    }

    //显示商品第一个图片
    public function showProductFirstImage($pid){
        $product = Product::model()->findByPk($pid);
        if($product === null) return '';
         return $product->getFirstImg();
    }
    
    //显示商品第一个图片
    public function showLoadProductFirstImage($pid){
        $product = Product::model()->findByPk($pid);
        if($product === null) return '';
         return $product->getloadFirstImg();
    }
    
    //商品链接
    public function getProductLink($pid){
        return CHtml::normalizeUrl(array('product/view/id/' . $pid));
    }
    
    public function showUploadTag($uploadtime){
        $content = '';
        if(isset($uploadtime{0})){
            $content .= CHtml::openTag('div', array('class'=>'pick'));
            $content .= CHtml::openTag('a', array('herf'=>'javascript:void(0)'));
            $content .= CHtml::image(Yii::app()->baseUrl.'/img/upload.png', '上传时间:'.$uploadtime);
            $content .= CHtml::closeTag('a');
            $content .= CHtml::closeTag('div');
        }
        return $content;
    }


    //搜索
    public function search(){
        $command = Yii::app()->db->createCommand();
        $command->select('p.*, r.city,r.price,r.unit,r.stock,r.id, r.productTitle ,r.brandName,u.uploadTime,u.num_iid,u.url,u.station');
        $command->from('tbl_product r');
        $command->join('tbl_product_property p', 'r.id=p.productId');
        $command->join('tbl_uploadhistory u', 'u.userId=:userId AND u.productId=p.productId', array(':userId'=>Yii::app()->user->id));
        $command->where('p.userID=:userID', array(':userID'=>Yii::app()->user->id));       

        //站点
        if(isset($this->site{0})){
            $command->andWhere('u.station =  :station', array(':station'=>  $this->site));
         }
         
         //货号
        if(isset($this->pid{0})){
            $command->andWhere('p.productId =  :id', array(':id'=>  $this->pid));
         }
         
          //标题 array('like', 'name', '%tester%')
        if(isset($this->ptitle{0})){
            $find = "%{$this->ptitle}%";
            $command->andWhere(array('like', 'r.productTitle', $find));
         }
         
         //品牌 
        if(isset($this->brand{0})){
            $find = "%{$this->brand}%";
            $command->andWhere(array('like', 'r.brandName', $find));
         }
           
         //上传时间 
        if(isset($this->suploadtime{0})){
            $command->andWhere('u.uploadTime >= :suploadTime', array(':suploadTime'=>$this->suploadtime));
         }
         
         //上传时间 
        if(isset($this->euploadtime{0})){
            $command->andWhere('u.uploadTime <= :euploadTime', array(':euploadTime'=>$this->euploadtime));
         }
         
        
        $command->order = 'uploadTime DESC';
        $rawData = $command->queryAll();
        

        return new CArrayDataProvider($rawData, array(
                'id'=>'prodcut_array',
                'pagination' => array('pageSize' => 30,),
          ));
    }

}

?>
