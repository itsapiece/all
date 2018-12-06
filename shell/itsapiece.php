<?php
require_once '../app/Mage.php';
use ItsAPiece\PinkTown\Importer as I;
Mage::app();
/**
 * @param array(int|string => mixed) $a
 * @param string|string[]|int $k
 * @param mixed|callable $d
 * @return mixed|null|array(string => mixed)
 */
function dfa(array $a, $k, $d = null) {return isset($a[$k]) ? $a[$k] : $d;}
/**
 * 2018-12-06
 * @param mixed $v
 * @return string
 */
function df_json_encode($v) {return json_encode(
	$v, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);}
/**
 * 2018-12-06
 * @param string|mixed[] $m
 * @param string $file [optional]
 */
function df_log($m, $file = 'mage2pro.log') {
	$m = (!is_array($m) ? $m : df_json_encode($m)) . "\n";
	if (df_my()) {
		echo $m;
	}
	file_put_contents(\Mage::getBaseDir('var') . "/log/$file", $m, FILE_APPEND);
}
/**
 * 2017-04-17
 * @return bool
 */
function df_my() {return isset($_SERVER['DF_DEVELOPER']);}
$i = new I;
$i->p();