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
		$prevAdditional = $p->getMediaGalleryImages(); /** @var C $prev */
		$p->setDataChanges($c);
		I::$break = true;
		if ($new && !$prevAdditional || !$prevAdditional->count()) {
			df_log("[{$p->getSku()}] an additional image will be added");
		}
	}
}