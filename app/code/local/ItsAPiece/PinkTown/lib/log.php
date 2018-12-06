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
	file_put_contents(\Mage::getBaseDir('var') . "/log/$file", $m, FILE_APPEND);
}