<?php

class TestCommand extends CConsoleCommand {

    public function run($args) {

        try {
            Yii::log("TestCommand");
        } catch (CException $ex) {
            
        }
        echo 'clear cache.';
    }

    public function getHelp() {
        $out = "清除缓存!.\n\n";
        return $out . parent::getHelp();
    }

}