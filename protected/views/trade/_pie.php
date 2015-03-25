<?php
$this->Widget('ext.highcharts.HighchartsWidget', array('options' => array('title' => array('text' => '成交对比'),
        'tooltip' => array('formatter' => 'js:function() { 
        return "<b>"+this.point.name+"</b>: "+Math.round(this.percentage)+"%"
          }'),
        'credits' => array('enabled' => true),
        'exporting' => array('enabled' => true),
        'plotOptions' => array('pie' => array('allowPointSelect' => true, 'cursor' => 'pointer',
                'dataLabels' => array('enabled' => true),
                'showInLegend' => true)
        ),
        'series' => array(array('type' => 'pie', 'name' => '成交对比',
                'data' => $model->getPieData())
        )
    )
        )
);
?>
