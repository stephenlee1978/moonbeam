<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' 关于';
?>
<?php if ($this->beginCache('about', array('duration' => 7600))) { ?>
    <h1>关于我们</h1>


    <div>
        <ul class="thumbnails">
            <li class="span4">
                <a href="javascript:void(0)" class="thumbnail">
                    <img src="<?php echo Yii::app()->baseUrl . '/img/about/main.jpg'; ?>" alt="" width="360" height="268">
                </a>
            </li>
            <li class="span2">
                <a href="javascript:void(0)" class="thumbnail">
                    <img src="<?php echo Yii::app()->baseUrl . '/img/about/1.jpg'; ?>" alt="" width="160" height="120">
                </a>
            </li>
            <li class="span2">
                <a href="javascript:void(0)" class="thumbnail">
                    <img src="<?php echo Yii::app()->baseUrl . '/img/about/2.jpg'; ?>" alt="" width="160" height="120">
                </a>
            </li>
            <li class="span2">
                <a href="javascript:void(0)" class="thumbnail">
                    <img src="<?php echo Yii::app()->baseUrl . '/img/about/3.jpg'; ?>" alt="" width="160" height="120">
                </a>
            </li>
            <li class="span2">
                <a href="javascript:void(0)" class="thumbnail">
                    <img src="<?php echo Yii::app()->baseUrl . '/img/about/4.jpg'; ?>" alt="" width="160" height="120">
                </a>
            </li>
            <li class="span2">
                <a href="javascript:void(0)" class="thumbnail">
                    <img src="<?php echo Yii::app()->baseUrl . '/img/about/5.jpg'; ?>" alt="" width="160" height="120">
                </a>
            </li>
            <li class="span2">
                <a href="javascript:void(0)" class="thumbnail">
                    <img src="<?php echo Yii::app()->baseUrl . '/img/about/6.jpg'; ?>" alt="" width="160" height="120">
                </a>
            </li>
            <li class="span2">
                <a href="javascript:void(0)" class="thumbnail">
                    <img src="<?php echo Yii::app()->baseUrl . '/img/about/7.jpg'; ?>" alt="" width="160" height="120">
                </a>
            </li>
            <li class="span2">
                <a href="javascript:void(0)" class="thumbnail">
                    <img src="<?php echo Yii::app()->baseUrl . '/img/about/8.jpg'; ?>" alt="" width="160" height="120">
                </a>
            </li>
        </ul>
        <p><i class="icon-tag"></i>是针对欧洲、美国、香港等海外正品采集的专业电子商务平台。
            目前站内已经拥有大量海量货品，与海外商网同步、计价，编辑，批量上传为您淘宝商店创造一个充分简单便捷的商品管理平台。</p>
        <p><i class="icon-tag"></i>
            以流行时尚为主题，
            主打Gucci 古琦、Prada 普拉达、Marc Jacobs 马克·雅可布、Burberry 巴宝莉 、Miu Miu 缪缪、Fendi 芬迪、Alexander McQueen 
            阿历山大·麦昆 等国际一线名牌，拥有女装、女鞋、男装、男鞋、配饰、包袋等各类产品，所有商品100%均来自海外正规渠道。
        </p>
        
        <h3>申请流程</h3>
        <blockquote>
        <p>用户注册</p>
        <small>在首页进行用户注册，注册后，后台会以邮件通知您的申请情况。</small>
        </blockquote>
        <blockquote>
        <p>服务条款</p>
        <small>请用户注册看清服务条款，注册后，能看到具体服务收费条款。</small>
        </blockquote>
    </div>

<?php $this->endCache();

} ?>