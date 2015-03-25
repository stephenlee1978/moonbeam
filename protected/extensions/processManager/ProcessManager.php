<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of processManager
 *
 * @author Administrator
 */
class ProcessManager {
    
    private function scriptCommand($url){
        $script = '';
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $script = 'start /b '.dirname(Yii::app()->basePath).'/protected/yiic catch '.$url;
        }else{
            $script = dirname(Yii::app()->basePath).'/protected/yiic catch '.$url.' /dev/null &';
        } 
        return $script;
    }

    public function __construct() {
    }
    
    public function __destruct() {
    }
    
    //运行进程
    public function run($url){
        $script = $this->scriptCommand($url);
        
        $hRes = popen($script, 'r');
        if($hRes === false) {
            echo 'Error: $this->script $url';
            return;
        }
        
        pclose($hRes);
    }
}

?>
