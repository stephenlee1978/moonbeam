<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 淘宝缓存类
 *
 * @author Administrator
 */
class TaobaoCache {
    CONST TIME_OUT = 600000;
    
    public static function getValue($id) {
        $value = Yii::app()->cache->get($id);
        if($value === false) return false;
        return $value;
    }
    
    public static function setValue($id, $value) {
        Yii::app()->cache->set($id, $value, TaobaoCache::TIME_OUT);
    }
}

?>
