<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * fix 2015/03/07
 */

class WSidebarNav extends CWidget {
    //菜单项
    private $items;

    public function init() {
        if (Yii::app()->user->level == 0) {//淘宝用户
            $this->setTaobaoMenuItems();
        } elseif (Yii::app()->user->level == 1) {//普通用户
            $this->setUserMenuItems();
        } elseif (Yii::app()->user->level == 2) {//超级用户
            $this->setAdminMenuItems();
        }

        parent::init();
    }

    //淘宝用户
    public function setTaobaoMenuItems() {
        $this->items = array(
                array('label' => '商品管理', 'url' =>array('/site/taobaoLogin/')),
                array('label' => '淘宝管理', 'url' =>array('/user/info/')),
                array('label' => '用户信息', 'url' =>array('/site/register')),
                array('label' => '项目管理', 'url' =>array('/site/logout')),
        );
    }

    //用户
    public function setUserMenuItems() {
        $this->items = array(
                array('label' => '商品管理', 'url' =>array('/site/taobaoLogin/')),
                array('label' => '淘宝管理', 'url' =>array('/user/info/')),
                array('label' => '用户信息', 'url' =>array('/site/register')),
                array('label' => '项目管理', 'url' =>array('/site/logout')),
        );
    }

    //超级用户菜单
    public function setAdminMenuItems() {
        $this->items = array(
                array('label' => '商品管理', 'url' =>array('/site/taobaoLogin/')),
                array('label' => '淘宝管理', 'url' =>array('/user/info/')),
                array('label' => '用户信息', 'url' =>array('/site/register')),
                array('label' => '项目管理', 'url' =>array('/site/logout')),
        );
    }

    public function run() {
        if (is_array($this->items)) {
            $this->renderHtml();
        }   
    }
    
    private function renderItem($item) {
        echo CHtml::openTag('li');
        echo CHtml::link($item['label'], $item['url']);
        echo CHtml::closeTag('li');
    }
    
    private function renderHtml() {
        echo CHtml::openTag('div', array('id'=>'sidebar-wrapper'));
        echo CHtml::openTag('ul', array('class'=>'sidebar-nav'));
        echo CHtml::openTag('li', array('class'=>'sidebar-brand'));
        echo CHtml::link(Yii::app()->name, 'javascript:void(0)');
        echo CHtml::closeTag('li');
        foreach ($this->items as $item) {
            $this->renderItem($item);
        }
        echo CHtml::closeTag('ul');
        echo CHtml::closeTag('div');
    }

}
