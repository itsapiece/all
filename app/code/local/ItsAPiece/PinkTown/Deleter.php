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
		/**
		 * 2018-12-12
		 * 1) «The images need sto be compressed because my disk space jumped to 80% usage.»
		 * https://www.upwork.com/messages/rooms/room_a1e68b73e6a1422b3a0fb3b7c5d03a69/story_c93aaf6519f6e41ab4b313c42040986c
		 * 2) «Regarding the disk usage by images, I implemented a better solution:
		 * the module now detects images of products which are absent in the new Drop_Ship_Product_Feed.csv file and deletes images of these product from the server.
		 * It should save disk space without decreasing images quality.»
		 */
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