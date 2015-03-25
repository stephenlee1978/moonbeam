<?php

/*
 * fix 2014/12/1
 */
Yii::import('application.lib.productCollect.*');

class ProductController extends AuthController {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * 显示一个商品详情.
     * 
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        if ($model === false) {
            throw new CHttpException(77, '未发现该商品!');
            Yii::app()->end();
        }

        $this->render('view', array(
            'model' => $model,
        ));
    }

    //商品管理
    public function actionAdmin() {

        $model = new ProductSearch();
        $model->unsetAttributes();  // clear any default values

        if (isset($_POST['ProductSearch'])) {
            $model->attributes = $_POST['ProductSearch'];
        } else if (isset($_GET['ProductSearch'])) {
            $model->attributes = $_GET['ProductSearch'];
        }


        $this->render('admin', array(
            'model' => $model,
        ));
    }

    //上传列表
    public function actionUploaded() {
        $model = new UploadedSearch();
        $model->unsetAttributes();  // clear any default values

        if (isset($_POST['UploadedSearch'])) {
            $model->attributes = $_POST['UploadedSearch'];
        }

        $this->render('uploaded', array(
            'model' => $model,
        ));
    }

    //批量删除商品
    public function actionDeleteAll() {
        if (isset($_POST['selectdel'])) {
            $selArry = $_POST['selectdel'];
            foreach ($selArry as $id) {
                $this->loadModel($id)->delete();
            }
            echo CJSON::encode(array('success' => true));
        }
    }

    //删除商品
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionUpdate() {
        if (!Yii::app()->request->isPostRequest)
            Yii::app()->end();

        if (isset($_POST['pk'])) {
            $modle = $this->loadModel($_POST['pk']);
            if ($modle !== null) {
                Yii::import('lib.Functions');
                $modle->setAttribute($_POST['name'], $_POST['value']);
                $modle->save();
            }
        }
    }

    //设置重量
    public function actionSetWeight() {
        if (isset($_POST['pk'])) {
            $modle = $this->loadModel($_POST['pk']);
            if ($modle !== null) {
                echo CJSON::encode(array('success' => true, 'html' => $modle->countTaobaoPriceDes($_POST['value'])));
            } else {
                echo CJSON::encode(array('success' => false));
            }
            Yii::app()->end();
        }
        echo CJSON::encode(array('success' => false));
    }

    //设置重量
    public function actionSetFreight() {
        if (isset($_POST['pk'])) {
            $modle = $this->loadModel($_POST['pk']);
            if ($modle !== null) {
                echo CJSON::encode(array('success' => true, 'html' => $modle->countTaobaoPriceDesFromFreight($_POST['value'])));
            } else {
                echo CJSON::encode(array('success' => false));
            }
            Yii::app()->end();
        }
        echo CJSON::encode(array('success' => false));
    }

    //保存上传历史 mender stephen 2013-06-10
    private function saveUploadHistory($product, $num_iid) {

        echo '保存上传历史.';

        $uploadhistory = Uploadhistory::model()->findByAttributes(array('productId' => $product->id, 'num_iid' => $num_iid, 'userId' => Yii::app()->user->getId()));
        if ($uploadhistory == NULL) {
            $uploadhistory = new Uploadhistory;
            $uploadhistory->userId = Yii::app()->user->getId();
            $uploadhistory->productId = $product->id;
            $uploadhistory->station = $product->station;
            $uploadhistory->num_iid = $num_iid;
            $uploadhistory->uploadTime = NULL;
            $uploadhistory->count++;
            $uploadhistory->save();
        } else {
            $uploadhistory->count++;
            $uploadhistory->uploadTime = NULL;
            $uploadhistory->save();
        }
    }

    //批量下架
    public function actionDelisting() {
        if (isset($_POST['sel'])) {
            $data = $_POST['sel'];
            $arry = explode(",", $data);
            $uploadhistory = Uploadhistory::model()->findByAttributes(array('userId' => $arry[0], 'productId' => $arry[1]));

            $message = '下架失败';
            $res = 0;
            $ret = false;

            if ($uploadhistory !== null) {

                $TbIf = new TbInterface;
                try {
                    $ret = $TbIf->itemUpdateDelistingRequest($uploadhistory->num_iid);

                    if ($ret == false) {
                        $message = $TbIf->errMsg;
                    } else {
                        $res = 1;
                        $message = '下架失败';
                    }
                } catch (Exception $e) {
                    
                }

                unset($TbIf);
            }

            $row = Order::model()->findByAttributes(array('user_id' => $arry[0], 'product_id' => $arry[1]));
            $row->upload = $res;
            $row->message = $message;
            $row->save();


            echo CJSON::encode(array('success' => $ret));
        }
    }

    /**
     * 显示商品列表.
     * id：商品类型ID
     */
    public function actionIndex() {

        $model = new ProductSearch();

        if (isset($_POST['srch-term'])) {
            
        } elseif (isset($_POST['ProductSearch'])) {
            $model->unsetAttributes();
            $model->attributes = $_POST['ProductSearch'];
        } elseif (isset($_GET['ProductSearch'])) {
            $model->unsetAttributes();
            $model->attributes = $_GET['ProductSearch'];
        }

        $dataProvider = $model->search();
        
        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Product the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {

        $model = Product::model()->findByPk(strval($id));
        if ($model === null) {
            return false;
        }
        return $model;
    }

}

