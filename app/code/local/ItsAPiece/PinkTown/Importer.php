<?php
namespace ItsAPiece\PinkTown;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Resource_Product_Collection as PC;
// 2018-12-05
class Importer {
	/**
	 * 2018-12-05
	 * @used-by shell/itsapiece.php
	 */
	function p() {
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
		df_log(array_values($f), 'products.json');
		$pc = new PC; /** @var PC $pc */
		$pc->addAttributeToSelect('*');
		/** @var array(string => P) $pMap */
		$pMap = array_combine(array_map(function(P $p) {return $p->getSku();}, $pc->getItems()), $pc->getItems());
		foreach ($f as $d) {
			/** @var array(string => mixed) $d */
			$sku = $d['sku']; /** @var string $sku */
			if ($p = dfa($pMap, $sku)) { /** @var P $p */
				df_log("Updating: $sku");
				Updater::p($p, $d);
			}
			else {
				df_log("Inserting: $sku");
				Inserter::p($d);
			}
			//\Mage::log($d['sku'], null, isset($pMap[$d['sku']]) ? 'exist.log' : 'new.log');
		}
		//file_put_contents(\Mage::getBaseDir('var') . '/log/skus.log', implode("\n", array_column($f, 0)));
		//xdebug_break();
	}
}