<?php
namespace ItsAPiece\PinkTown\A;
use ItsAPiece\PinkTown\Importer as I;
use ItsAPiece\PinkTown\Row as R;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Product_Attribute_Backend_Media as Backend;
use Mage_Catalog_Model_Resource_Eav_Attribute as A;
use Varien_Data_Collection as C;
// 2018-12-09
final class Images {
	/**
	 * 2018-12-07
	 * @param P $p
	 * @param R $r
	 */
	static function p(P $p, R $r) {
		$a = $p->getResource()->getAttribute('media_gallery'); /** @var A $a */
		$b = $a->getBackend(); /** @var Backend $b */	
		// 2018-12-09
		// «Faster way to load media images in a product collection»:
		// https://magento.stackexchange.com/a/153570
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
		// 2018-12-09
		// It is important to import the primary images before the additional one:
		// images are shown on the frontend product pages in the order of its addition
		// (the «Sort Order» property is ignored for an unknown reason).
		$new = $r->imgPrimary();
		if ($new && !$p->getData('image')) {
			/**
			 * 2018-12-09
			 * It works faster than `$p->addImageToMediaGallery($f, null, true);`
			 * @see \Mage_Catalog_Model_Product::addImageToMediaGallery()
			 */
			$b->addImage($p, self::new_($new), ['image', 'small_image', 'thumbnail'], true, false);
			df_log("[{$p->getSku()}] a primary image is added.");
		}		
		$new = $r->imgAdditional();
		if ($new && (!$prev || !$prev->count())) {
			/**
			 * 2018-12-09
			 * It works faster than `$p->addImageToMediaGallery($f, null, true);`
			 * @see \Mage_Catalog_Model_Product::addImageToMediaGallery()
			 */
			$b->addImage($p, self::new_($new), null, true, false);
			df_log("[{$p->getSku()}] an additional image is added.");
		}
	}
	
	/**
	 * 2018-12-09
	 * @used-by p()
	 * @param string $url
	 * @return string
	 */
	private static function new_($url) {
		$r = sys_get_temp_dir() . '/' . basename($url); /** @var string $f */
		unlink($r);
		file_put_contents($r, file_get_contents($url));
		return $r;
	}	
}