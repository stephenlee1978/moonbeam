<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="author" content="stephen.lee">

                <?php
                Yii::app()->clientscript->registerCssFile(Yii::app()->baseUrl . '/css/bootstrap.css');
                Yii::app()->clientscript->registerCssFile(Yii::app()->baseUrl . '/css/style.css');
                Yii::app()->clientScript->registerCoreScript('jquery');
                Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-dropdown.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-transition.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-carousel.js', CClientScript::POS_HEAD);
                Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/siderbarCtrl.js', CClientScript::POS_HEAD);
                ?>

                <title><?php echo CHtml::encode($this->pageTitle); ?></title>
                </head>

                <body style="background-color:#E9EAED" data-offset="50" data-target=".subnav" data-spy="scroll">
                    <div id="wrapper" class="toggled">
                        <?php $this->widget('application.widgets.WSidebarNav'); ?>
                        
                        <div id="page-content-wrapper">   
                            <div class="navbar qfnavbar_blue navbar-fixed-top">
                                <div class="container_content">
                                    <a class="brand" id="siderctrl" title="<?php echo Yii::app()->name; ?>" href="javascript:void(0)">
                                        <?php echo CHtml::image(Yii::app()->baseUrl . '/img/logo1.png', ''); ?></a>
                                    <div class="nav-collapse">
                                        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'psearch_search_form',
            'action' => Yii::app()->createUrl('/product/index/'),
            'method' => 'POST',
            'htmlOptions' => array('class' => 'navbar-form navbar-left')
        ));
        ?>
                                            <div class="input-group input-group-sm" style="max-width:360px;">
                                                <input class="form-control" placeholder="商品信息" name="srch-term" id="srch-term" type="text">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                                    </div>    
                                            </div>
                                        <?php $this->endWidget(); ?>
                                        <?php $this->widget('application.widgets.WNavMenu'); ?>
                                    </div><!--/.nav-collapse -->
                                </div>
                            </div>

                            <div class="cont maincont">
                                <div class="container_content">
                                    <div class="row">
                                        <?php echo $content; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </body>
                </html>