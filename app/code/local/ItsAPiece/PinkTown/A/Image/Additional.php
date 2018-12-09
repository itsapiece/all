<?php
namespace ItsAPiece\PinkTown\A\Image;
use ItsAPiece\PinkTown\Importer as I;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Product_Attribute_Backend_Media as Backend;
use Mage_Catalog_Model_Resource_Eav_Attribute as A;
use Varien_Data_Collection as C;
// 2018-12-09
final class Additional extends \ItsAPiece\PinkTown\A\Image {
	/**
	 * 2018-12-09
	 * @override
	 * @see \ItsAPiece\PinkTown\A\Image::_p()
	 * @used-by \ItsAPiece\PinkTown\A\Image::p()
	 * @param P $p
	 * @param string $new
	 */
	protected function _p(P $p, $new) {
		// 2018-12-09
		// «Faster way to load media images in a product collection»:
		// https://magento.stackexchange.com/a/153570
		$b = $this->b(); /** @var Backend b */
		I::$break = false;
		$c = $p->hasDataChanges();
		$b->afterLoad($p);
		/**
		 * 2018-12-09
		 * @uses \Mage_Catalog_Model_Product::getMediaGalleryImages() does not return
		 * the primary image for a product: it returns only the product's additional images.
		 */
		$prev = $p->getMediaGalleryImages(); /** @var C $prev */
		$p->setDataChanges($c);
		I::$break = true;
		if ($new && (!$prev || !$prev->count())) {
			/**
			 * 2018-12-09
			 * It works faster than `$p->addImageToMediaGallery($f, null, true);`
			 * @see \Mage_Catalog_Model_Product::addImageToMediaGallery()
			 */
			$b->addImage($p, $this->new_(), null, true, false);
			df_log("[{$p->getSku()}] an additional image is added.");
		}
	}
}