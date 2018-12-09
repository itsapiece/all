<?php
namespace ItsAPiece\PinkTown\A\Image;
use ItsAPiece\PinkTown\Importer as I;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Product_Attribute_Backend_Media as Backend;
use Mage_Catalog_Model_Resource_Eav_Attribute as A;
use Varien_Data_Collection as C;
// 2018-12-09
final class Primary extends \ItsAPiece\PinkTown\A\Image {
	/**
	 * 2018-12-09
	 * @override
	 * @see \ItsAPiece\PinkTown\A\Image::_p()
	 * @used-by \ItsAPiece\PinkTown\A\Image::p()
	 * @param P $p
	 * @param string $new
	 */
	protected function _p(P $p, $new) {
		if ($new && !$p->getData('image')) {
			$b = $this->b(); /** @var Backend $b */
			/**
			 * 2018-12-09
			 * It works faster than `$p->addImageToMediaGallery($f, null, true);`
			 * @see \Mage_Catalog_Model_Product::addImageToMediaGallery()
			 */
			$f = $b->addImage($p, $this->new_(), null, true, false); /** @var string $t */
			foreach (['image', 'small_image', 'thumbnail'] as $a) { /** @var string $a */
				$b->setMediaAttribute($p, $a, $f);
			}
			df_log("[{$p->getSku()}] a primary image is added.");
		}
	}
}