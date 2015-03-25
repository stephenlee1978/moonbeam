<?php
/**
 * Description of WCarousel
 *
 * @author Administrator
 */
class WCarousel extends CWidget {

    public function init() {
        parent::init();
    }

    public function run() {
        $blob = Blob::model()->findAll('typeID=:typeID', array(':typeID'=>Yii::app()->params['carousel']));
        $this->render('_wCarousel', array('blob'=>$blob));
    }

}
