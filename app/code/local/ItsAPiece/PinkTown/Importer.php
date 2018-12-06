<?php
namespace ItsAPiece\PinkTown;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Resource_Product_Collection as PC;
// 2018-12-05
final class Importer {
	/**
	 * 2018-12-05
	 * @used-by shell/itsapiece.php
	 */
	function p() {
		self::init();
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '0');
		Magmi::configure();
		$f = array_map('str_getcsv',
			explode("\r\n",
				str_replace("\n\"", '"',
					file_get_contents(\Mage::getBaseDir() . '/_my/Drop_Ship_Product_Feed.csv')
				)
			)
		);
		$keys = array_shift($f);
		$f = array_filter($f, function($v) {return '' !== trim($v[0]);});
		$f = array_map(function($v) use($keys) {return array_combine($keys, $v);}, $f);
		//df_log(array_values($f), 'products.json');
		$pc = new PC; /** @var PC $pc */
		$pc->addAttributeToSelect('*');
		/** @var array(string => P) $pMap */
		$pMap = array_combine(array_map(function(P $p) {return $p->getSku();}, $pc->getItems()), $pc->getItems());
		foreach ($f as $d) {
			$row = new Row($d); /** @var Row $row */
			/** @var array(string => mixed) $d */
			$sku = $d['sku']; /** @var string $sku */
			if ($p = dfa($pMap, $sku)) { /** @var P $p */
				df_log("Updating: $sku");
				Updater::p($p, $d);
			}
			else {
				//df_log("Inserting: $sku");
				//Inserter::p($d);
			}
			//\Mage::log($d['sku'], null, isset($pMap[$d['sku']]) ? 'exist.log' : 'new.log');
		}
		//file_put_contents(\Mage::getBaseDir('var') . '/log/skus.log', implode("\n", array_column($f, 0)));
		//xdebug_break();
	}

	/**
	 * 2018-12-06
	 * @used-by p()
	 */
	private static function init() {
		// 2017-04-25, 2017-12-13
		// Unfortunately, I have not found a way to make this code reusable among my modules.
		// I tried to move this code to a `/lib` function like df_lib(), but it raises a «chicken and egg» problem,
		// because Magento runs the `registration.php` scripts before any `/lib` functions are initalized,
		// whereas the `/lib` functions are initalized from the `registration.php` scripts.
		$base = dirname(__FILE__); /** @var string $base */
		if (is_dir($libDir = "{$base}/lib")) { /** @var string $libDir */
			// 2017-11-13
			// Today I have added the subdirectories support inside the `lib` folders,
			// because some lib/*.php files became too big, and I want to split them.
			$requireFiles = function($libDir) use(&$requireFiles) {
				// 2015-02-06
				// array_slice removes «.» and «..».
				// http://php.net/manual/function.scandir.php#107215
				foreach (array_slice(scandir($libDir), 2) as $c) {  /** @var string $resource */
					is_dir($resource = "{$libDir}/{$c}") ? $requireFiles($resource) : require_once "{$libDir}/{$c}";
				}
			};
			// 2015-02-06
			// array_slice removes «.» and «..».
			// http://php.net/manual/function.scandir.php#107215
			foreach (array_slice(scandir($libDir), 2) as $c) { /** @var string $resource */
				is_dir($resource = "{$libDir}/{$c}") ? $requireFiles($resource) : require_once "{$libDir}/{$c}";
			}
		}
	}
}