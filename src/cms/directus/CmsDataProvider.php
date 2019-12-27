<?php
namespace ant\cms\components;

class CmsDataProvider extends \yii\data\BaseDataProvider {
	public $collection;
	public $status;
	
	protected $_items;
	protected $_limit;
	protected $_offset;
	
	public function limit($limit) {
		$this->_limit = $limit;
		return $this;
	}
	
	public function offset($offset) {
		$this->_offset = $offset;
		return $this;
	}
	
	protected function prepareModels()
    {
		return $this->getItems();
	}
	
	protected function prepareKeys($models) {
		return array_keys($models);
	}
	
	protected function prepareTotalCount()
    {
		return count($this->getItems());
	}
	
	protected function getItems() {
		if (!isset($this->_items)) {
			$this->_items = \Yii::$app->directus->getItems($this->collection, $this->_limit, $this->_offset, $this->status);
		}
		//throw new \Exception(print_r($this->_items,1));
		return $this->_items;
	}
}