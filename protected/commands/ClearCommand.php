<?php

class ClearCommand extends CConsoleCommand {

    public function run($args) {

        try {
            Yii::app()->cache->flush();
            Yii::app()->cache->gc(false);
        } catch (CException $ex) {
            
        }
        echo 'clear cache.';
    }

    public function getHelp() {
        $out = "清除缓存!.\n\n";
        return $out . parent::getHelp();
    }

}