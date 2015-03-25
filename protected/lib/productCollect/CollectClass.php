<?php
header("Content-Type:text/html;charset=utf-8");

/**
 * Description of CCollectClass
 *
 * @author Administrator
 */
class CollectClass {

    public $errors = array();

    public static function getListData() {
        return array(
            '0' => '单产品页 ',
            '1' => '整页产品 ',
        );
    }

    //销毁函数
    function __destruct() {
        unset($errors);
    }

    public function excute($url, $city = '', $list = '0') {
        $station = Station::getStationClass($url);

        $bRet = false;
        if (!isset($station{0})) {
            echo '对不起,现在不支持该网站的采集。';
            return $bRet;
        }

        Yii::import('application.lib.productCollect.*');

        $obj = null;
        try {
            switch ($list) {
                case "0":
                    $obj = new $station(true, true);
                    if (($pid = $obj->productAnaly($url, $city))!==false) {
                        $this->saveUserProduct($pid);
                        $obj->updateUpload($pid);
                    }
                    break;
                case "1":
                    $obj = new $station(true, false);
                    $obj->collectSinglePage($url, $city);
                    break;
            }
            $bRet = true;
        } catch (Exception $ex) {
            Yii::log('CollectClass excute exception. code=' . $ex->getCode() . ' msg=' . $ex->getMessage());
        }

        unset($obj);

        return $bRet;
    }

    private function saveUserProduct($pid) {
        if ($pid===false) return;
            
        UserProduct::saveUserProduct($pid);
    }
    
    public function batchExcute($ids) {
        Yii::import('lib.productCollect.*');
        Yii::import('lib.Functions');

        if (is_array($ids)) {
            foreach ($ids as $id) {
                $url = Product::getProductUrl($id);
                if ($url === false) {
                    Functions::message($id . ' 未能找到商品的网站！');
                    continue;
                }
                $this->excute($url);
            }
        } else {
            Functions::message('未能找到采集的商品！');
        }
    }

}
