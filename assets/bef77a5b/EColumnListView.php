<?php

/**
 * EColumnListView class file.
 *
 * @author Tasos Bekos <tbekos@gmail.com>
 * @copyright Copyright &copy; 2012 Tasos Bekos
 */
/**
 * EColumnListView represents a list view in multiple columns.
 *
 * @author Tasos Bekos <tbekos@gmail.com>
 */
Yii::import('zii.widgets.CListView');

class EColumnListView extends CListView {
    /**
     * Renders the item view.
     * This is the main entry of the whole view rendering.
     *
     * This is override function that supports multiple columns
     */
	public $itemsCssId='items_id';
	
    public function renderItems() {

        echo CHtml::openTag($this->itemsTagName, array('class' => $this->itemsCssClass,'id'=>$this->itemsCssId)) . "\n";
        $data = $this->dataProvider->getData();

        if (($n = count($data)) > 0) {

            $owner = $this->getOwner();
            $render = $owner instanceof CController ? 'renderPartial' : 'render';
            //$j = 0;
            foreach ($data as $i => $item) {
                $data = $this->viewData;
                $data['index'] = $i;
                $data['data'] = $item;
                $data['widget'] = $this;
                $owner->$render($this->itemView, $data);
            }
        } else {
            $this->renderEmptyText();
        }
        echo CHtml::closeTag($this->itemsTagName);
    }

}

?>