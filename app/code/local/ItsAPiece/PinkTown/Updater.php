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
		A\Color::p($this->_p, $this->_r->color());
		A\Desc::p($this->_p, $this->_r->desc());
		A\Name::p($this->_p, $this->_r);
		A\Weight::p($this->_p, $this->_r->weight());
		//$this->_p->save();
	}

	/**
	 * 2018-12-06
	 * @used-by \ItsAPiece\PinkTown\Importer::p()
	 * @param P $p
	 * @param Row $r
	 */
	static function p(P $p, Row $r) {
		$i = new self; $i->_p = $p; $i->_r = $r;
		$i->_p();
	}

	/**
	 * 2018-12-06
	 * @used-by p()
	 * @var P $p
	 */
	private $_p;

	/**
	 * 2018-12-06
	 * @var Row
	 */
	private $_r;
}