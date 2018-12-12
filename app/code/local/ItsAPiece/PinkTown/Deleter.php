<?php
namespace ItsAPiece\PinkTown;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Product_Attribute_Backend_Media as Backend;
use Mage_Catalog_Model_Resource_Eav_Attribute as A;
use Varien_Data_Collection as C;
use Varien_Io_File as IO;
// 2018-12-12
final class Deleter {
	/**
	 * 2018-12-12
	 * @used-by \ItsAPiece\PinkTown\Importer::p()
	 * @param P $p
	 */
	static function p(P $p) {
		df_log("[{$p->getSku()}] Deleting the product images...");
		$dir = \Mage::getConfig()->getOptions()->getMediaDir() . '/catalog/product'; /** @var string $dir */
		self::rm($dir . $p['image']);
		$a = $p->getResource()->getAttribute('media_gallery'); /** @var A $a */
		$b = $a->getBackend(); /** @var Backend $b */
		$b->afterLoad($p);
		$images = $p->getMediaGalleryImages(); /** @var C $prev */
		foreach ($images as $i) {
			self::rm($dir . $i['file']);
		}
		df_log("[{$p->getSku()}] Deleting the product...");
		df_admin_call(function() use($p) {$p->delete();});
	}

	/**
	 * 2018-12-12
	 * @used-by p()
	 * @param string $f
	 */
	private static function rm($f) {
		$io = new IO;
		if ($io->fileExists($f)) {
			$io->rm($f);
		}
	}
}