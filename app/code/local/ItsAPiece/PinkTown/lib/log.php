<?php
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
	mkdir($dir = \Mage::getBaseDir('var') . "/log");
	file_put_contents("$dir/$file", $m, FILE_APPEND);
}