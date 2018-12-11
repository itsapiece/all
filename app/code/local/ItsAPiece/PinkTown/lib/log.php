<?php
/**
 * 2018-12-06
 * @param string|mixed[] $m
 * @param mixed[]|string $p [optional]
 * @param string $file [optional]
 */
function df_log($m, $p = [], $file = 'mage2pro.log') {
	list($p, $file) = is_array($p) ? [$p, $file] : [[], $p];
	$m = (!is_array($m) ? (!$p ? $m : vsprintf($m, $p)) : df_json_encode($m)) . "\n";
	$argv = dfa($GLOBALS, 'argv'); /** @var mixed[] $argv */
	if (df_my() || ($argv && '-v' === dfa($argv, 1))) {
		echo $m;
	}
	mkdir($dir = \Mage::getBaseDir('var') . "/log");
	file_put_contents("$dir/$file", $m, FILE_APPEND);
}