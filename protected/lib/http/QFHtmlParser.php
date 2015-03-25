<?php
include('httplib.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QFHtmlParser
 *
 * @author fengfeng
 */
class QFHtmlParser {

    private $dom;
    public $html;

    //构造函数
    function __construct() {
        $this->createDocument();
    }

    function __destruct() {
        $this->clear();
    }
    
    private function clear() {
        unset($this->html);
        $this->html = null;
        unset($this->dom);
        $this->dom = null;
    }

    private function createDocument() {
        if(isset($this->dom) && $this->dom !== null){
            $this->clear();
        }
        $this->dom = new DOMDocument();
    }
    
    public function loadURL($url, $cookie = '') {
        if (!isset($url{0}))
            return false;

        $this->html = curl_url($url, $cookie);
        if (isset($this->html{0}) > 0) {
            return $this->loadHTML();
        }

        return false;
    }
    
    public function loadFormHtml($html,$encoding = "UTF-8") {
        //$this->html = mb_convert_encoding($this->html, 'HTML-ENTITIES', $encoding);
        if(isset($html{0})){
            $this->createDocument();
            $this->html = $html;
        }
        libxml_use_internal_errors(true);
        return $this->dom->loadHTML($this->html); // suppress warnings
    }
    
    public function loadHTML($encoding = "UTF-8") {
        //$this->html = mb_convert_encoding($this->html, 'HTML-ENTITIES', $encoding);
        libxml_use_internal_errors(true);
        return $this->dom->loadHTML($this->html); // suppress warnings
    }

    public function getTagNodes($tag) {
        if (isset($this->dom))
            return $this->dom->getElementsByTagName($tag);
        return array();
    }

    public function getElementById($elementId) {
        if (isset($this->dom))
            return $this->dom->getElementById($elementId);
        return array();
    }

    public function getElementByClass($className, $tag = 'div') {
        foreach ($this->dom->getElementsByTagName($tag) as $node) {
            if (!$node->hasAttribute('class')) {
                continue;
            }
            if (stripos($node->getAttribute('class'), $className) !== false) {
                return $node;
            }
        }
        return NULL;
    }

    public function getElementByClassFromParent(&$parentNode, $tagName, $className) {
        $response = false;

        $childNodeList = $parentNode->getElementsByTagName($tagName);
        for ($i = 0; $i < $childNodeList->length; $i++) {
            $temp = $childNodeList->item($i);
            if (stripos($temp->getAttribute('class'), $className) !== false) {
                $response = $temp;
                break;
            }
        }

        return $response;
    }
    
    public function getElementByAttrFromParent(&$parentNode, $tagName, $Attr, $AttrValue) {
        $response = false;

        $childNodeList = $parentNode->getElementsByTagName($tagName);
        for ($i = 0; $i < $childNodeList->length; $i++) {
            $temp = $childNodeList->item($i);
            if (strcmp($temp->getAttribute($Attr), $AttrValue) === 0) {
                $response = $temp;
                break;
            }
        }

        return $response;
    }
    
    public function getElementsByAttrFromParent(&$parentNode, $tagName, $Attr, $AttrValue) {
        $response = array();

        $childNodeList = $parentNode->getElementsByTagName($tagName);
        for ($i = 0; $i < $childNodeList->length; $i++) {
            $temp = $childNodeList->item($i);
            if (strcmp($temp->getAttribute($Attr), $AttrValue) === 0) {
                $response[] = $temp;
            }
        }

        return $response;
    }

    public function getElementsByClassFromParent(&$parentNode, $tagName, $className) {
        $response = array();

        $childNodeList = $parentNode->getElementsByTagName($tagName);
        for ($i = 0; $i < $childNodeList->length; $i++) {
            $temp = $childNodeList->item($i);
            if (stripos($temp->getAttribute('class'), $className) !== false) {
                $response[] = $temp;
            }
        }

        return $response;
    }

    public function getElementByMutilClassFromParent(&$parentNode, $tagName, $className0, $className1) {
        $response = false;

        $childNodeList = $parentNode->getElementsByTagName($tagName);
        for ($i = 0; $i < $childNodeList->length; $i++) {
            $temp = $childNodeList->item($i);
            if (stripos($temp->getAttribute('class'), $className0) !== false || stripos($temp->getAttribute('class'), $className1) !== false) {
                $response = $temp;
                break;
            }
        }

        return $response;
    }

    public function getElementByIdFromParent(&$parentNode, $tagName, $idName) {
        $response = false;

        $childNodeList = $parentNode->getElementsByTagName($tagName);
        for ($i = 0; $i < $childNodeList->length; $i++) {
            $temp = $childNodeList->item($i);
            if (stripos($temp->getAttribute('id'), $idName) !== false) {
                $response = $temp;
                break;
            }
        }

        return $response;
    }
    
     public function getElementsByTagFromParent(&$parentNode, $tagName) {
        return $parentNode->getElementsByTagName($tagName);
    }

    public function innerXML($node) {
        $doc = $node->ownerDocument;
        $frag = $doc->createDocumentFragment();
        foreach ($node->childNodes as $child) {
            $frag->appendChild($child->cloneNode(TRUE));
        } return $doc->saveXML($frag);
    }

}

?>
