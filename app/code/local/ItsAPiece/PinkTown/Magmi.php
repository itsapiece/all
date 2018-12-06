<?php
namespace ItsAPiece\PinkTown;
// 2018-12-05
class Magmi {
	/**
	 * 2018-12-05
	 * @used-by \ItsAPiece\PinkTown\Importer::p()
	 */
	static function configure() {file_put_contents (\Mage::getBaseDir() . '/magmi/conf/magmi.ini', self::ini());}

	/**
	 * 2018-12-05
	 * @used-by configure()
	 * @return string
	 */
	private static function ini() {
		/** @var array(string => string) $db */
		$db  = \Mage::getConfig()->getResourceConnectionConfig('default_setup')->asArray();
		$cfg = [
			'[DATABASE]' => [
				'connectivity' => 'net'
				,'dbname' => $db['dbname']
				,'host' => $db['host']
				,'password' => $db['password']
				,'port' => '3306'
				,'resource' => 'default_setup'
				,'table_prefix' => ''
				,'user' => $db['username']
			]
			,'[MAGENTO]' => [
				'basedir' => \Mage::getBaseDir()
				,'version' => '1.9.x'
			]
			,'[GLOBAL]' => [
				'dirmask' => 755
				,'filemask' => 644
				,'multiselect_sep' => ','
				,'step' => '0.5'
			]
		]; /** @var array(string => array(string => string|int)) $cfg */
		return implode("\n", array_map(function($k, $v) {return
			"$k\n" . implode("\n", array_map(function($k, $v) {return
				"$k = \"$v\""
			;}, array_keys($v), $v))
		;}, array_keys($cfg), $cfg));
	}
}