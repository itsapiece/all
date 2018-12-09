<?php
namespace ItsAPiece\PinkTown\A\Image;
use ItsAPiece\PinkTown\Importer as I;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Product_Attribute_Backend_Media as MediaB;
use Mage_Catalog_Model_Resource_Eav_Attribute as A;
use Varien_Data_Collection as C;
// 2018-12-09
final class Additional {
	/**
	 * 2018-12-09
	 * @param P $p
	 * @param string $new
	 */
	static function p(P $p, $new) {
		// 2018-12-09
		// «Faster way to load media images in a product collection»:
		// https://magento.stackexchange.com/a/153570
		$a = $p->getResource()->getAttribute('media_gallery'); /** @var A $a */
		$mediaB = $a->getBackend(); /** @var MediaB $mediaB */
		I::$break = false;
		$c = $p->hasDataChanges();
		$mediaB->afterLoad($p);
		/**
		 * 2018-12-09
		 * @uses \Mage_Catalog_Model_Product::getMediaGalleryImages() does not return
		 * the primary image for a product: it returns only the product's additional images.
		 */
		$prev = $p->getMediaGalleryImages(); /** @var C $prev */
		$p->setDataChanges($c);
		I::$break = true;
		if ($new && (!$prev || !$prev->count())) {
			$f = sys_get_temp_dir() . '/' . basename($new); /** @var string $f */
			unlink($f);
			file_put_contents($f, file_get_contents($new));
			/**
			 * 2018-12-09
			 * It works faster than `$p->addImageToMediaGallery($f, null, true);`
			 * @see \Mage_Catalog_Model_Product::addImageToMediaGallery()
			 */
			$mediaB->addImage($p, $f, null, true, false);
			df_log("[{$p->getSku()}] an additional image is added.");
		}
	}
}