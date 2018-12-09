<?php
namespace ItsAPiece\PinkTown\A;
use ItsAPiece\PinkTown\Importer as I;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Product_Attribute_Backend_Media as Backend;
use Mage_Catalog_Model_Resource_Eav_Attribute as A;
use Varien_Data_Collection as C;
/**
 * 2018-12-09
 * @see \ItsAPiece\PinkTown\A\Image\Additional
 * @see \ItsAPiece\PinkTown\A\Image\Primary
 */
abstract class Image {
	/**
	 * 2018-12-09
	 * @param P $p
	 * @param string $new
	 * @used-by p()
	 */
	abstract protected function _p(P $p, $new);

	/**
	 * 2018-12-09
	 * @used-by \ItsAPiece\PinkTown\A\Image\Additional::_p()
	 * @return Backend
	 */
	final protected function b() {
		if (!isset($this->{__METHOD__})) {
			$a = $this->_p->getResource()->getAttribute('media_gallery'); /** @var A $a */
			$this->{__METHOD__} = $a->getBackend();
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2018-12-09
	 * @used-by \ItsAPiece\PinkTown\A\Image\Additional::_p()
	 * @return string
	 */
	final protected function new_() {
		if (!isset($this->{__METHOD__})) {
			$r = sys_get_temp_dir() . '/' . basename($this->_new); /** @var string $f */
			unlink($r);
			file_put_contents($r, file_get_contents($this->_new));
			$this->{__METHOD__} = $r;
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2018-12-09
	 * @param P $p
	 * @param string $new
	 */
	final static function p(P $p, $new) {
		// 2018-12-09 `new static` works correctly even PHP >= 5.3: https://3v4l.org/vGGMM
		$i = new static; $i->_new = $new; $i->_p = $p; /** @var self $i */
		$i->_p($p, $new);
	}

	/**
	 * 2018-12-09
	 * @used-by new_()
	 * @used-by p()
	 * @var string
	 */
	private $_new;

	/**
	 * 2018-12-09
	 * @used-by backend()
	 * @used-by p()
	 * @var P
	 */
	private $_p;
}