<?php
namespace ItsAPiece\PinkTown;
// 2018-12-10
final class AdminMode {
	/**
	 * 2018-12-10
	 * @used-by call()
	 * @return void
	 */
	private function begin() {
		$this->_counter++;
		if (1 === $this->_counter) {
			$this->_currentStore = \Mage::app()->getStore();
			$this->_updateMode = \Mage::app()->getUpdateMode();
			/**
			 * Очень важный момент!
			 * Если Magento находится в режиме обновления,
			 * то Mage_Core_Model_App::getStore()
			 * всегда будет возвращать Mage_Core_Model_App::getDefaultStore(),
			 * даже для такого кода: Mage_Core_Model_App::getStore(999).
			 * Это приводит к весьма некорректному поведению системы в некоторых ситуациях,
			 * когда мы обновляем товарные разделы своим установочным скриптом:
			 * @see Mage_Catalog_Model_Resource_Abstract::_saveAttributeValue():
			 * $storeId = (int)Mage::app()->getStore($object->getStoreId())->getId();
			 * Этот код заведомо вернёт неправильный результат!
			 */
			\Mage::app()->setUpdateMode(false);
			\Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
		}
	}

	/**
	 * 2018-12-10
	 * @used-by call()
	 * @return void
	 */
	private function end() {
		$this->_counter--;
		if (0 === $this->_counter) {
			\Mage::app()->setCurrentStore($this->_currentStore);
			\Mage::app()->setUpdateMode($this->_updateMode);
			unset($this->_currentStore);
			unset($this->_updateMode);
		}
	}

	/**
	 * 2018-12-10
	 * @used-by begin()
	 * @used-by end()
	 * @var int
	 */
	private $_counter = 0;
	/**
	 * 2018-12-10
	 * @used-by begin()
	 * @used-by end()
	 * @var \Mage_Core_Model_Store
	 */
	private $_currentStore;
	/**
	 * 2018-12-10
	 * @used-by begin()
	 * @used-by end()
	 * @var bool
	 */
	private $_updateMode;

	/**
	 * 2018-12-10
	 * @used-by df_admin_call()
	 * @param \Closure $f
	 * @return self
	 */
	static function call(\Closure $f) {
		static $i; /** @var self $i */ 
		$i = $i ?: new self;
		$i->begin();
		try {$r = $f();} /** @var mixed $r */
		catch (\Exception $e) {$i->end(); throw $e;}
		$i->end();
		return $r;
	}
}