<?php
namespace ItsAPiece\PinkTown;
// 2018-12-05
class Importer {
	/**
	 * 2018-12-05
	 */
	static function process() {
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
		$f = array_filter($f, function($v) {return '' !== $v[0];});
		file_put_contents(\Mage::getBaseDir('var') . '/log/skus.log', implode("\n", array_column($f, 0)));
		//xdebug_break();
	}
}