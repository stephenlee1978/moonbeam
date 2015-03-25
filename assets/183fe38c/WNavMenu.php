<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * fix 2014/12/1
 */

class WNavMenu extends CWidget {
    //菜单项
    private $items = array();

    public function init() {
        if (Yii::app()->user->isGuest) {
            $this->setGuestMenuItems();
        } elseif (Yii::app()->user->level == 0) {//淘宝用户
            $this->setTaobaoMenuItems();
        } elseif (Yii::app()->user->level == 1) {//普通用户
            $this->setUserMenuItems();
        } elseif (Yii::app()->user->level == 2) {//超级用户
            $this->setUserMenuItems();
        }

        parent::init();
    }

    //游客
    public function setGuestMenuItems() {
        $this->items[] = array('label' => '<i class="icon-user icon-white"></i>游客<b class="caret"></b>',
            'itemOptions' => array('class' => 'dropdown'),
            'linkOptions' => array(
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
            ),
            'url' => 'javascript:void(0)', 'items' => array(
                array('label' => '用户注册', 'url' => array('/site/register/')),
        ));
    }

    //淘宝用户
    public function setTaobaoMenuItems() {
        $this->items[] = array('label' => '<i class="glyphicon glyphicon-home"></i>首页','url' => array('/product/index/'));
        $this->items[] = array('label' => '<i class="icon-user icon-white"></i>' . Yii::app()->user->username . '<b class="caret"></b>',
            'itemOptions' => array('class' => 'dropdown'),
            'linkOptions' => array(
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
            ),
            'url' => 'javascript:void(0)', 'items' => array(
                array('label' => '淘宝授权', 'url' => array('/site/taobaoLogin/')),
                array('label' => '用户信息', 'url' => array('/user/info/')),
                array('label' => '用户注销', 'url' => array('/site/logout')),
        ));
    }

    //用户
    public function setUserMenuItems() {
        $this->items[] = array('label' => '<i class="glyphicon glyphicon-home"></i>首页','url' => array('/product/index/'));
        $this->items[] = array('label' => '<i class="glyphicon glyphicon-plus"></i>商品采集','url' => array('/taobao/index/'));
        $this->items[] = array('label' => '<i class="icon-user icon-white"></i>' . Yii::app()->user->username . '<b class="caret"></b>',
            'itemOptions' => array('class' => 'dropdown'),
            'linkOptions' => array(
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
            ),
            'url' => 'javascript:void(0)', 'items' => array(
                array('label' => '淘宝授权', 'url' => array('/site/taobaoLogin/')),
                array('label' => '用户信息', 'url' => array('/user/info/')),
                array('label' => '用户注销', 'url' => array('/site/logout')),
        ));
    }

    public function run() {
            $this->render('wNavMenu', array('items' => $this->items));

    }

}
