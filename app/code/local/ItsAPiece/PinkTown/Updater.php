<?php
namespace ItsAPiece\PinkTown;
use Mage_Catalog_Model_Product as P;
// 2018-12-06
final class Updater {
	/**
	 * 2018-12-06
	 * @used-by p()
	 */
	private function _p() {
		$this->updateName();
		//$this->_p->save();
	}

	/**
	 * 2018-12-06
	 * @param string $k
	 * @param string|null $d [optional]
	 * @return string|null
	 */
	private function d($k, $d = null) {return dfa($this->_d, $k, $d);}

	/**
	 * 2018-12-06
	 * @return string
	 */
	private function sku() {return $this->d('sku');}

	private function updateName() {
		xdebug_break();
		$sku = dfa($d, 'sku');
		$desc = dfa($d, 'title');
		$p->addData([
			'name' => dfa($d, 'title')
		]);
	}

	/**
	 * 2018-12-06
	 * @used-by \ItsAPiece\PinkTown\Importer::p()
	 * @param P $p
	 * @param array(string => string) $d
	 */
	static function p(P $p, array $d) {
		$i = new self; $i->_p = $p; $i->_d = $d;
		$i->_p();
	}

	/**
	 * 2018-12-06
	 * @used-by a()
	 * @var array(string => string)
	 */
	private $_d;
	/**
	 * 2018-12-06
	 * @used-by p()
	 * @var P $p
	 */
	private $_p;
}