<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="stephen.lee">

        <?php
        Yii::app()->clientscript->registerCssFile(Yii::app()->baseUrl . '/css/bootstrap.css');
        Yii::app()->clientscript->registerCssFile(Yii::app()->baseUrl . '/css/style.css');
        ?>


        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    <script>
        
    </script>
    
    <body data-offset="50" data-target=".subnav" data-spy="scroll">

        <div class="cont">
            <div class="container-fluid">
                <div class="row-fluid">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
        <br>
        <br>

        <div class="footer navbar-fixed-bottom">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div id="footer-copyright" class="col-md-6">
                    </div> <!-- /span6 -->
                    <div id="footer-terms" class="col-md-6">
                        © 2012-14 ColorBox Inc. 2014V5.0 Powered by stephen.lee
                    </div> <!-- /.span6 -->
                </div> <!-- /row -->
            </div> <!-- /container -->
        </div>

    </body>
</html>