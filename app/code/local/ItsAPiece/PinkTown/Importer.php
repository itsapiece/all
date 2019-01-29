<?php
namespace ItsAPiece\PinkTown;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Product_Image as PI;
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
		ini_set('display_errors', 1);
		ini_set('max_execution_time', '0');
		ini_set('memory_limit', '-1');
		df_log('Downloading...');
		$f = array_map('str_getcsv', explode("\r\n", strtr(
			self::stripBOM(file_get_contents(
				df_my()
				? \Mage::getBaseDir() . '/_my/Drop_Ship_Product_Feed.csv'
				// 2018-12-09
				// «spoke to provider. they confirmed the link will always remain the same»
				// https://www.upwork.com/messages/rooms/room_a1e68b73e6a1422b3a0fb3b7c5d03a69/story_d8708d21f658b497bc84f966f2d288ef
				: 'https://www.dropbox.com/s/9hmm9lghx7vwt23/Drop_Ship_Product_Feed.csv?dl=1'
			))
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
		/** @var P[] $productsWithoutSku */
		$productsWithoutSku = array_filter($pc->getItems(), function(P $p) {return
			!$p->getSku() && !$this->preserve($p)
		;});
		foreach ($productsWithoutSku as $p) {
			Deleter::p($p);
		}	
		/** @var P[] $productsWithoutSku */
		$productsWithSku = array_filter($pc->getItems(), function(P $p) {return !!$p->getSku();});
		/** @var array(string => P) $pMap */
		$pMap = df_map_r($productsWithSku, function(P $p) {return [$p->getSku(), $p];});
		$t = count($f); $c = 0;
		$changed = false;
		// 2018-12-12
		// «did you delete the current items before you import the update?
		// the moduel would have to delete the current items»
		// https://www.upwork.com/messages/rooms/room_a1e68b73e6a1422b3a0fb3b7c5d03a69/story_fcc2e6ceea4f2674059727aa84181816
		$toDelete = array_filter(
			array_diff(array_keys($pMap), array_column($f, 'sku'))
			// 2018-12-12
			// «the moduel would have to delete the current items
			// (except the $5 jewelry, $10 jewlery set and $1 dream)
			// those items are from my in-house inventory.»
			// https://www.upwork.com/messages/rooms/room_a1e68b73e6a1422b3a0fb3b7c5d03a69/story_fcc2e6ceea4f2674059727aa84181816
			,function($sku) use($pMap) {return !$this->preserve($pMap[$sku]);}
		); /** @var string[] $toDelete */
		df_log('Products to delete: %s', [count($toDelete)]);
		foreach ($toDelete as $sku) {
			$p = $pMap[$sku]; /** @var P $p */
			Deleter::p($p);
			unset($pMap[$sku]);
		}
		/**
		 * 2018-12-12
		 * 1) «The images need sto be compressed because my disk space jumped to 80% usage.»
		 * https://www.upwork.com/messages/rooms/room_a1e68b73e6a1422b3a0fb3b7c5d03a69/story_c93aaf6519f6e41ab4b313c42040986c
		 * 2) «Regarding the disk usage by images, I implemented a better solution:
		 * the module now detects images of products which are absent in the new Drop_Ship_Product_Feed.csv file and deletes images of these product from the server.
		 * It should save disk space without decreasing images quality.»
		 */
		if ($toDelete) {
			df_log('Deleting images cache...');
			$pi = \Mage::getModel('catalog/product_image'); /** @var PI $pi */
			$pi->clearCache();
		}
		foreach ($f as $d) { /** @var array(string => mixed) $d */
			$c++;
			try {
				$r = new Row($d); /** @var Row $r */
				$sku = $r->sku(); /** @var string $sku */
				if (!($p = dfa($pMap, $sku))) { /** @var P $p */
					$p = Inserter::p($r);
					$changed = true;
				}
				self::$break = false;
				$p->setDataChanges(false);
				self::$break = true;
				Updater::p($p, $r);
				if ($p->hasDataChanges()) {
					$pr = number_format($c * 100 / $t, 2);
					df_log("{$c}[{$pr}%] Saving {$p->getSku()} «{$p->getName()}»");
					$p->save();
					$changed = true;
				}
			}
			catch (\Exception $e) {
				df_log($e->getMessage() ?: $e->getTraceAsString(), [], 'mage2pro.error.log');
			}
		}
		// 2018-12-22
		// I have added the !df_my() condition because sometimes the other conditions do not evauate properly.
		// So it is just a quick and dirty workaround.
		if ($changed || $toDelete || !df_my()) {
			df_log('Cleaning the cache...'); \Mage::app()->cleanCache();
			df_log('Reindexing...');
			$i = \Mage::getSingleton('index/indexer'); /** @var I $i = */
			$ipc = $i->getProcessesCollection(); /** @var IPC $ipc */
			foreach ($ipc as $ip) {  /** @var IP $ip */
				$ip->reindexEverything();
			}
		}
	}

	/**
	 * 2019-01-30
	 * @used-by p()
	 * @return int[]
	 */
	private function membershipProductIds() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = df_int(array_unique(df_conn()->fetchCol(
				df_select()->from(df_table('membership/package'), 'product_id')
			)));
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2019-01-30
	 * @used-by p()
	 * @param P $p
	 * @return bool
	 */
	private function preserve(P $p) {return
		in_array(intval($p->getId()), $this->membershipProductIds())
		|| array_intersect(df_int($p->getCategoryIds()), [6, 27, 44])
	;}

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
	 * 2019-01-30
	 * @used-by p()
	 * @param string $s
	 * @return string
	 */
	private static function stripBOM($s) {return
		0 !== strncmp($s, pack('CCC', 0xEF, 0xBB, 0xBF), 3) ? $s : substr($s, 3)
	;}

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