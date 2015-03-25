<?php
/**
 * Description of WCallboard
 *
 * @author Administrator
 */
class WCallboard extends CWidget {

    public function init() {
        parent::init();
    }

    public function run() {
        $blob = Blob::model()->findAll('typeID=:typeID', array(':typeID'=>Yii::app()->params['callboard']));
        $this->render('_wCallboard', array('blob'=>$blob));
    }

}

