<?php

class RbacCommand extends CConsoleCommand {
    private $_authManager;
    public function run($args) {
        if(($this->_authManager=Yii::app()->authManager)===NULL){
            echo 'Error:not here authManager.';
            return;
        }
        
        echo 'create new authManager,clear auth db data! [Y]or[N]';
        if(strnatcasecmp(trim(fgets(STDIN)), 'y')===0){
            echo 'create new authManager success.';
            
            $this->_authManager->clearAll();
            
            $this->_authManager->createOperation('createPost','create a post');

$this->_authManager->createOperation('readPost','read a post');

$this->_authManager->createOperation('updatePost','update a post');

$this->_authManager->createOperation('deletePost','delete a post');

$role=$this->_authManager->createRole('reader');

$role->addChild('readPost');

$role=$auth->createRole('author');

$role->addChild('reader');

$role->addChild('createPost');

$role->addChild('updateOwnPost');

$role=$this->_authManager->createRole('editor');

$role->addChild('reader');

$role->addChild('updatePost');

$role=$auth->createRole('admin');

$role->addChild('editor');

$role->addChild('author');

$role->addChild('deletePost');

$this->_authManager->assign('reader','readerA');

$this->_authManager->assign('author','authorB');

$this->_authManager->assign('editor','editorC');
        
return;
        }
        
        echo 'canel create new authManager success.';
    }

    public function getHelp() {
        $out = "清除缓存!.\n\n";
        return $out . parent::getHelp();
    }

}