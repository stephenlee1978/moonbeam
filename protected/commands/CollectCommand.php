<?php
Yii::import('application.lib.productCollect.*');
/* * *******************************************************************************
 * Copyright(C),2014, Glory
 * FileName: CollectCommand.php
 * Author:  stephen.lee
 * Version: v1.1
 * Date:  14:07 2014-08-27
 * Description:  批量采集类
 * ******************************************************************************** */
class CollectCommand extends CConsoleCommand {

    public function run($args) {
		
        $rows = ProductLink::searchProductLink(200);
	
        if (count($rows)) {
            foreach ($rows as $row) {
                @$this->collect($row['url']);
                @ProductLink::deleteProductLink($row['url']);
            }
        }
    }

    private function collect($url) {
        try {
            $station = Station::getStationClass($url);

            if (isset($station{0})) {
                $obj = new $station(false, true);
                @$obj->productAnaly($url, '');
                unset($obj);
            }
        } catch (CException $ex) {
            echo('collect exception msg:' . $ex->getMessage() . ' file:' . $ex->getFile() . ' line:' . $ex->getLine());
        }
        
    }

    public function getHelp() {
        $out = "后台采集.\n\n";
        return $out . parent::getHelp();
    }

}