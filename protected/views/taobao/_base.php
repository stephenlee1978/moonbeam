<script type="text/javascript">
    itempopsurl = "<?php echo Yii::app()->createUrl("taobao/itemProps"); ?>";
</script>

<table class="table">
    <tr>
        <th>店铺类目</th>
        <td>
            <span><?php
                $this->widget('widgets.WSellercats.WSellercats', array(
                    'model' => $property,
                    'sellercats' => $edit->sellercats
                ));
                ?></span>
        </td>
    </tr>
    <tr>
        <th>宝贝标题:</th>
        <td><?php echo CHtml::activeTextArea($property, 'subTitle', array('id' => 'productSubTitle', 'class' => 'input-xlarge')); ?>
            <?php echo CHtml::openTag('span', array('class' => 'arttxt label label-info help-inline')) . $property->getTilteLenght() . '个字符' . CHtml::closeTag('span'); ?>
        </td>
    </tr>

    <tr>
        <th>上传位置:</th>
        <td><?php echo CHtml::activeDropDownList($property, 'approveStatus', array('onsale' => '出售中', 'instock' => '仓库中')); ?>
        </td>
    </tr>

    <tr>
        <th>运费计算:</th>
        <td>
            <?php
            $this->widget('ext.editable.EditableField', array(
                'type' => 'text',
                'model' => $model,
                'attribute' => 'freight',
                'placement' => 'right',
                'title' => '附加运费',
                'url' => $this->createUrl('product/SetFreight'),
                'htmlOptions' => array('id' => 'editableField_freight'),
                'success' => 'js: function(data) {
                        if(data.success){ 
                            $(".taobaototal").html(data.html);
                         }
                    }',
                'onUpdate' => 'js: function(e, editable) {
                        $("#ProductProperty_freight").attr("value", editable.value);
                }'
            ));
            ?>￥
            <?php echo CHtml::activeHiddenField($property, 'freight', array('id' => 'ProductProperty_freight')); ?>
        </td>
    </tr>

    <tr>
        <th>淘宝属性:</th>
        <td>
            <div class="">
                <?php echo CHtml::activeHiddenField($property, 'pidPath'); ?>
                <?php echo CHtml::activeHiddenField($property, 'itemCats'); ?>
                <?php
                if (isset($property->itemCats{0}))
                    echo CHtml::link($property->pidPath, '', array('class' => 'btn olditemcats'));
                ?>
                <?php echo CHtml::link('选择类目', '', array('class' => 'btn itemcats', 'url' => Yii::app()->createUrl("taobao/itemcats"))); ?>
                <span class="taobaoCatDiv"></span>
            </div>

            <div class="">
                <?php
                $this->widget('widgets.WProps.WProps', array(
                    'itemProps' => $itemProps
                ));
                ?>
            </div>
        </td>
    </tr>
</table>
