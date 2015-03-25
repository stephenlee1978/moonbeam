<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = '用户信息-' . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-modal.js', CClientScript::POS_END);
?>
<script>
    jQuery(document).ready(function($) {
        //显示对话框
        $('#modifyPassword').live('click', function() {
            $('#myModal').modal('show');
        })

        //修改密码
        $('.save_btn').live('click', function() {

            if ($('#password').val() == '' || $('#modifyPassWord').val() == '') {
                alert('请正确填写新密码!');
                return;
            }

            if ($('#password').val() != $('#modifyPassWord').val()) {
                alert('两个新密码不一致!');
                return;
            }

            var data = {'password': $('#password').val()};

            $.ajax({
                type: 'POST',
                url: "<?php echo $this->createUrl('user/modifyPassword'); ?>",
                data: data,
                dataType: 'json',
                async: true,
                success: function(data) {
                    if (data.success == true) {
                        alert('修改密码成功!');
                    } else {
                        alert('修改密码失败!');
                    }
                }
            });
        })
    })
</script>

<div class="modal hide fade" id="myModal" style="display: none;">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" onclick="$('#myModal').modal('hide');">×</a>
        <h3>修改密码</h3>
    </div>
    <div class="modal-body">
        <?php
        echo CHtml::label('新密码:', 'password') .
        CHtml::passwordField('password', '', array('placeholder' => '请填写新密码')) .
        CHtml::label('重复新密码:', 'modifyPassWord') .
        CHtml::passwordField('modifyPassWord', '', array('placeholder' => '请重复新密码'));
        ?>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" class="btn" onclick="$('#myModal').modal('hide');">关闭</a>
        <a href="javascript:void(0)" class="save_btn btn btn-primary">修改密码</a>
    </div>
</div>


    <h1>用户 <small><?php echo ''.Yii::app()->user->username; ?></small>
    </h1>


<div class="row">
    <div class="span3">
        <ul class="thumbnails">
            <li class="span3">
                <a href="javascript:void(0)" class="thumbnail" style="width:128px; height:128px">
                <?php echo CHtml::image(Yii::app()->baseUrl . '/img/sex'.$model->sex.'.png', '', array('width'=>128, 'height'=>128)); ?>
                </a>
            </li>
        </ul>
    </div>
    <div class="span9">
<?php echo $model->showWarningMessage(); ?>
    </div>
</div>

<div>   
    <?php
    $this->widget('zii.widgets.CDetailView', array(
        'htmlOptions' => array('class' => 'table'),
        'nullDisplay' => '未设置',
        'data' => $model,
        'attributes' => array(
            'username',
            'nickname',
            array(
                'label' => '用户级别',
                'type' => 'raw',
                'value' => $model->getLevelDes(),
            ),
            'email',
            'logintime',
            'outtime',
            array(
                'label' => '开通消息服务',
                'type' => 'raw',
                'value' => $model->getJmsDes(),
            ),
            array(
                'label' => '修改密码',
                'type' => 'raw',
                'value' => $model->getPasswordBtn(),
            ),
        ),
    ));
    ?>
</div>
</div>