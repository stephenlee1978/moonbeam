<?php

/**
 * 产品搜索功能模块
 * fix 2014/12/1
 * @author stephen
 */
class ProductSearch extends CFormModel {

    public $pagination;
    //采集站点
    public $site;
    //商品标题
    public $pinfo;
    //是否上传
    public $uploaded = 0;
    //上传
    public $uploadtime;
    //价格范围
    public $pricerangb = '';
    //价格范围
    public $pricerange = '';
    //上传排行
    public $orderuploadtime = '1';

    public function rules() {
        return array(
            array('orderuploadtime, uploaded, site, pinfo, uploadtime, pricerangb, pricerange', 'safe'),
        );
    }

    public function attributeLabels() {
        return array(
            'orderuploadtime' => 'orderuploadtime',
            'site' => '站点',
            'pinfo' => '商品信息 ',
            'uploadtime' => '上传时间 ',
            'uploaded' => '是否上传 ',
            'pricerangb' => '价格范围 ',
            'pricerange' => '价格最大范围',
        );
    }

    protected function getProductFristImg($data, $row) {
        $content = '';
        $image = ProductImages::getFirstImage($data['id']);
        if ($image !== false) {
            echo CHtml::openTag('a', array('href' => CHtml::normalizeUrl(array('product/view/id/' . $data['id'])), 'target' => '_blank'));
            echo CHtml::image(Yii::app()->params['product_path'] . $data['id'] . '/' . $image, '', array('width' => 80, 'height' => 80));
            echo '</a>';
        } else {
            echo CHtml::image('http://placehold.it/80x80', '', array('width' => 80, 'height' => 80));
        }
    }

    protected function getStocks($data, $row) {
        $stocks = explode(",", $data['stock']);
        return array_sum($stocks);
    }

    protected function isUpload($data, $row) {
        if (isset($data['uploadTime']{0}))
            echo '是';
        else {
            echo '否';
        }
    }

    //显示商品第一个图片
    public function showProductFirstImage($pid) {
        $product = Product::model()->findByPk($pid);
        if ($product === null)
            return '';
        return $product->getFirstImg();
    }

    //显示商品第一个图片
    public function showLoadProductFirstImage($pid) {
        $product = Product::model()->findByPk($pid);
        if ($product === null)
            return '';
        return $product->getloadFirstImg();
    }

    //商品链接
    public function getProductLink($pid) {
        return CHtml::normalizeUrl(array('product/view/id/' . $pid));
    }

    //搜索
    public function search() {
        $command = Yii::app()->db->createCommand();
        $command->select('p.*');
        $command->from('v_product_info p');
        $command->group = 'p.id';
        $command->where('p.uid =:userID', array(':userID' => Yii::app()->user->id));

        //站点
        if (isset($this->site{0})) {
            $command->andWhere('p.station =  :station', array(':station' => $this->site));
        }

        //商品信息
        if (isset($this->pinfo{0})) {
            if (stripos($this->pinfo, ',') !== false) {
                $orInfos = explode(',', $this->pinfo);
                foreach ($orInfos as $key => $value) {
                    $value = "%{$value}%";
                    if ($key == 0) {
                        $command->andWhere('p.id like  :info' . $key . ' OR p.productTitle like :info' . $key . ' OR p.brandName like :info' . $key, array(':info' . $key => $value));
                    } else {
                        $command->orWhere('p.id like  :info' . $key . ' OR p.productTitle like :info' . $key . ' OR p.brandName like :info' . $key, array(':info' . $key => $value));
                    }
                }
            } elseif (stripos($this->pinfo, '+') !== false) {
                $addInfos = explode('+', $this->pinfo);
                foreach ($addInfos as $key => $value) {
                    $value = "%{$value}%";
                    $command->andWhere('p.id like  :info' . $key . ' OR p.productTitle like :info' . $key . ' OR p.brandName like :info' . $key, array(':info' . $key => $value));
                }
            } else {
                $find = "%{$this->pinfo}%";
                $command->andWhere('p.id like  :info OR p.productTitle like :info OR p.brandName like :info', array(':info' => $find));
            }
        }

        //上传时间 
        if (isset($this->uploadtime{0})) {
            switch ($this->uploadtime) {
                case '0'://今天
                    $command->andWhere('date(p.uploadTime) > date_sub(curdate(),interval 1 day)');
                    break;
                case '1'://三天前
                    $command->andWhere('date(p.uploadTime) >= date_sub(curdate(),interval 3 day)');
                    break;
                case '2'://一周前
                    $command->andWhere('date(p.uploadTime) >= date_sub(curdate(),interval 7 day)');
                    break;
                case '3'://一个月
                    $command->andWhere('date(p.uploadTime) >= date_sub(curdate(),interval 1 month)');
                    break;
                case '4'://三个月
                    $command->andWhere('date(p.uploadTime) >= date_sub(curdate(),interval 3 month)');
                    break;
                default:
                    break;
                    $this->uploaded = 1;
            }
        }

        //是否上传 
        if ($this->uploaded == 1) {//用户上传过
            $command->andWhere('p.uploadTime IS NOT NULL');
        } elseif ($this->uploaded == 0) {
            $command->andWhere('p.uploadTime IS NULL');
        }

        //开始价格
        if (isset($this->pricerangb{0})) {
            $command->andWhere('p.price >=  :priceb', array(':priceb' => $this->pricerangb));
        }

        //结束价格 
        if (isset($this->pricerange{0})) {
            $command->andWhere('p.price <=  :pricee', array(':pricee' => $this->pricerange));
        }

        switch ($this->orderuploadtime) {
            case '0'://更新升序
                $command->order = 'updateTime ASC';
                break;
            case '1'://更新降序
                $command->order = 'updateTime DESC';
                break;
            case '2'://上传升序
                $command->order = 'p.uploadTime ASC';
                break;
            case '3'://上传降序
                $command->order = 'p.uploadTime DESC';
                break;
            default:
                $command->order = 'updateTime DESC';
                break;
        }

        $rawData = $command->queryAll();

        return new CArrayDataProvider($rawData, array(
            'id' => 'prodcut_array',
            'pagination' => array('pageSize' => 50,),
        ));
    }

    //搜索
    public function searchAll() {
        $command = Yii::app()->db->createCommand();
        $command->select('p.*');
        $command->from('v_product_info p');
        $command->group = 'p.id';
        $command->where('1=1');

        //站点
        if (isset($this->site{0})) {
            $command->andWhere('p.station =  :station', array(':station' => $this->site));
        }

        //商品信息
        if (isset($this->pinfo{0})) {
            if (stripos($this->pinfo, ',') !== false) {
                $orInfos = explode(',', $this->pinfo);
                foreach ($orInfos as $key => $value) {
                    $value = "%{$value}%";
                    if ($key == 0) {
                        $command->andWhere('p.id like  :info' . $key . ' OR p.productTitle like :info' . $key . ' OR p.brandName like :info' . $key, array(':info' . $key => $value));
                    } else {
                        $command->orWhere('p.id like  :info' . $key . ' OR p.productTitle like :info' . $key . ' OR p.brandName like :info' . $key, array(':info' . $key => $value));
                    }
                }
            } elseif (stripos($this->pinfo, '+') !== false) {
                $addInfos = explode('+', $this->pinfo);
                foreach ($addInfos as $key => $value) {
                    $value = "%{$value}%";
                    $command->andWhere('p.id like  :info' . $key . ' OR p.productTitle like :info' . $key . ' OR p.brandName like :info' . $key, array(':info' . $key => $value));
                }
            } else {
                $find = "%{$this->pinfo}%";
                $command->andWhere('p.id like  :info OR p.productTitle like :info OR p.brandName like :info', array(':info' => $find));
            }
        }

        //上传时间 
        if (isset($this->uploadtime{0})) {
            switch ($this->uploadtime) {
                case '0'://今天
                    $command->andWhere('date(p.uploadTime) > date_sub(curdate(),interval 1 day)');
                    break;
                case '1'://三天前
                    $command->andWhere('date(p.uploadTime) >= date_sub(curdate(),interval 3 day)');
                    break;
                case '2'://一周前
                    $command->andWhere('date(p.uploadTime) >= date_sub(curdate(),interval 7 day)');
                    break;
                case '3'://一个月
                    $command->andWhere('date(p.uploadTime) >= date_sub(curdate(),interval 1 month)');
                    break;
                case '4'://三个月
                    $command->andWhere('date(p.uploadTime) >= date_sub(curdate(),interval 3 month)');
                    break;
                default:
                    break;
                    $this->uploaded = 1;
            }
        }

        //是否上传 
        if (Yii::app()->user->level == 2) {
            if ($this->uploaded == 1) {//用户上传过
                $command->andWhere('p.uploadTime IS NOT NULL');
            } elseif ($this->uploaded == 0) {
                $command->andWhere('p.uploadTime IS NULL');
            }
        } else {
            if ($this->uploaded == 1) {//用户上传过
                $command->andWhere('p.uid =:userID', array(':userID' => Yii::app()->user->id));
            } elseif ($this->uploaded == 0) {
                $command->andWhere('p.uid !=:userID OR p.uid IS NULL', array(':userID' => Yii::app()->user->id));
            }
        }

        //开始价格
        if (isset($this->pricerangb{0})) {
            $command->andWhere('p.price >=  :priceb', array(':priceb' => $this->pricerangb));
        }

        //结束价格 
        if (isset($this->pricerange{0})) {
            $command->andWhere('p.price <=  :pricee', array(':pricee' => $this->pricerange));
        }

        switch ($this->orderuploadtime) {
            case '0'://更新升序
                $command->order = 'updateTime ASC';
                break;
            case '1'://更新降序
                $command->order = 'updateTime DESC';
                break;
            case '2'://上传升序
                $command->order = 'p.uploadTime ASC';
                break;
            case '3'://上传降序
                $command->order = 'p.uploadTime DESC';
                break;
            default:
                $command->order = 'updateTime DESC';
                break;
        }

        $rawData = $command->queryAll();

        Yii::log($command->text);
        return new CArrayDataProvider($rawData, array(
            'id' => 'prodcut_array',
            'pagination' => array('pageSize' => 50,),
        ));
    }

}

?>
