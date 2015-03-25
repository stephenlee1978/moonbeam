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
                ?>
                <title><?php echo CHtml::encode($this->pageTitle); ?></title>
                </head>

                <body data-offset="50" data-target=".subnav" data-spy="scroll">
                    <div class="mainnavbar qfnavbar_blue">
                        <div class="container_content">
                            <a class="brand" title="<?php echo Yii::app()->name; ?>" href="<?php Yii::app()->homeUrl ?>">
                                <?php echo CHtml::image(Yii::app()->baseUrl . '/img/logo.png', ''); ?></a>
                        </div>
                    </div>

                    <div class="cont logincont">
                        <div class="container_content">
                            <div class="row">
                                <?php echo $content; ?>
                            </div>
                        </div>
                    </div>

                    <div class="pageFooter" id="footer">
                        <div class="col-sm-6 contentCurve"></div>
                        <div class="col-rt"><p>© 2012-15 Moonbeam Inc. 2015 <?php echo Yii::app()->params['version']; ?> Powered by stephen.lee</p></div>
                    </div>

                </body>
                </html>