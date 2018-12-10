<?php
namespace ItsAPiece\PinkTown;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Resource_Product_Collection as PC;
use Mage_Index_Model_Indexer as I;
use Mage_Index_Model_Process as IP;
use Mage_Index_Model_Resource_Process_Collection as IPC;
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
		df_log('Downloading...');
		$f = array_map('str_getcsv', explode("\r\n", strtr(
			file_get_contents(
				df_my()
				? \Mage::getBaseDir() . '/_my/Drop_Ship_Product_Feed.csv'
				// 2018-12-09
				// «spoke to provider. they confirmed the link will always remain the same»
				// https://www.upwork.com/messages/rooms/room_a1e68b73e6a1422b3a0fb3b7c5d03a69/story_d8708d21f658b497bc84f966f2d288ef
				: 'https://www.dropbox.com/s/9hmm9lghx7vwt23/Drop_Ship_Product_Feed.csv?dl=1'
			)
			,[
				"\n\"" => '"'
				,'additional_ Image_url' => 'additional_image_url'
			]
		)));
		df_log('Processing...');
		$keys = array_shift($f);
		$f = array_filter($f, function($v) {return '' !== trim($v[0]);});
		$f = array_map(function($v) use($keys) {return array_combine($keys, $v);}, $f);
		//df_log(array_values($f), 'products.json');
		$pc = new PC; /** @var PC $pc */
		$pc->addAttributeToSelect('*');
		/** @var array(string => P) $pMap */
		$pMap = df_map_r($pc->getItems(), function(P $p) {return [$p->getSku(), $p];});
		$t = count($f); $c = 0;
		$added = 0;
		foreach ($f as $d) { /** @var array(string => mixed) $d */
			$c++;
			try {
				$r = new Row($d); /** @var Row $r */
				$sku = $r->sku(); /** @var string $sku */
				if (!($p = dfa($pMap, $sku))) { /** @var P $p */
					$p = Inserter::p($r);
					$added++;
				}
				self::$break = false;
				$p->setDataChanges(false);
				self::$break = true;
				Updater::p($p, $r);
				if ($p->hasDataChanges()) {
					$pr = number_format($c * 100 / $t, 2);
					df_log("{$c}[{$pr}%] Saving {$p->getSku()} «{$p->getName()}»");
					$p->save();
				}
				if ($added) {
					break;
				}
			}
			catch (\Exception $e) {
				df_log($e->getMessage() ?: $e->getTraceAsString(), [], 'mage2pro.error.log');
			}
			//\Mage::log($d['sku'], null, isset($pMap[$d['sku']]) ? 'exist.log' : 'new.log');
		}
		df_log('Cleaning the cache...'); \Mage::app()->cleanCache();
		df_log('Reindexing...');
		$i = \Mage::getSingleton('index/indexer'); /** @var I $i = */
		$ipc = $i->getProcessesCollection(); /** @var IPC $ipc */
		foreach ($ipc as $ip) {  /** @var IP $ip */
			$ip->reindexEverything();
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

	/**
	 * 2018-0=12-09
	 * @used-by p()
	 * \Varien_Object::setData()
	 * \Varien_Object::setDataChanges()
	 * \Varien_Object::unsetData()
	 * @var bool
	 */
	static $break = false;
}