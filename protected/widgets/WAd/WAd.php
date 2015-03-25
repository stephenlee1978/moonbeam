<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WAd
 *
 * @author stephen
 */
class WAd extends CWidget {

    private $ads;
    public $groupnum = 9;

    public function init() {
        $this->ads = Advert::getAllAdvert();
        parent::init();
    }

    public function run() {

        if (count($this->ads) == 0)
            return parent::run();

        $this->renderHtml();
    }

    private function renderAd() {
        $n = $this->groupnum;
        while (1) {
            $itemclass = 'item';
            if ($n == $this->groupnum) {
                $itemclass = 'active item';
            }

            echo CHtml::openTag('div', array('class' => $itemclass));
            echo CHtml::openTag('ul', array('class' => 'thumbnails'));
            $ret = $this->renderChildAd($n - $this->groupnum, $n);
            echo CHtml::closeTag('ul');
            echo CHtml::closeTag('div');

            $n *= 2;
            if (!isset($this->ads[$n])) {
                break;
            }
        }
    }

    private function renderChildAd($begin, $end) {
        for ($n = $begin; $n <= $end; ++$n) {
            if (isset($this->ads[$n])) {
                $content = CHtml::openTag('li', array('class' => 'span3'));
                $content .= CHtml::openTag('div', array('style' => 'height:200px;'));
                $content .= $this->ads[$n]['html'];
                $content .= CHtml::closeTag('div');
                $content .= CHtml::closeTag('li');
                echo $content;
            }
        }
    }

    private function renderHtml() {

        echo CHtml::openTag('div', array('class' => 'span12'));
        echo CHtml::openTag('div', array('class' => 'carousel', 'id' => 'myAd'));
        echo CHtml::openTag('div', array('class' => 'carousel-inner'));
        echo $this->renderAd();
        echo CHtml::closeTag('div');
        echo CHtml::link('‹', '#myAd', array('class' => 'left carousel-control', 'data-slide' => 'prev'));
        echo CHtml::link('›', '#myAd', array('class' => 'right carousel-control', 'data-slide' => 'next'));
        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
    }

}
