<?php
    $this->widget('zii.widgets.CMenu', array(
       'htmlOptions'=>array('class'=>'pull-right nav'),
        'submenuHtmlOptions' => array(
                    'class' => 'dropdown-menu',
                ),
        'items' => $items,
        'encodeLabel' => false,
    ));
?>
