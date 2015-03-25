<?php
/*
 * main params
 * fix 2014/12/1
 */
return array(
    'params' => array(        
      'version'=>'v5.2',
      'email' => '44477009@qq.com',
      'product_path' => '/product/',
      'upload_path' => '/uploads/',
        
      //设置属性
     'attributes'=>array(
         0=>'商品附加标题',
         1=>'重量计算公式',
         2=>'特价附加标题',
         3=>'详情附加信息',
     ),
        
   'attributevalues'=>array(
         'title'=>0,
         'weight'=>1,
         'offer'=>2,
         'desc'=>3,   
     ),
     //广告ID
     'callboard'=>0,
     'carousel'=>1,
     'debug'=>true,
    ),
);
