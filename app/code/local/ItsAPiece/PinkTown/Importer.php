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
	}
}