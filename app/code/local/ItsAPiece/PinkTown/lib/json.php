<?php
/**
 * 2015-12-06
 * @param mixed $v
 * @return string
 */
function df_json_encode($v) {return json_encode(df_json_sort($v),
	JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE
);}

/**
 * 2017-09-07
 * I use the @uses df_is_assoc() check,
 * because otherwise @uses df_ksort_r_ci() will convert the numeric arrays to associative ones,
 * and their numeric keys will be ordered as strings.
 * @used-by df_json_decode()
 * @used-by df_json_encode()
 * @param mixed $v
 * @return mixed
 */
function df_json_sort($v) {return !is_array($v) ? $v : (df_is_assoc($v) ? df_ksort_r_ci($v) :
	/**
	 * 2017-09-08
	 * @todo It would be nice to use df_sort($v) here,
	 * but now it will break the «Sales Documents Numeration» extension,
	 * because @see \Df\Config\Settings::_matrix() relies on an exact items ordering, e.g:
	 * [["ORD-{Y/m}-",null],["INV-",null],["SHIP-{Y-M}",null],["RET-{STORE-ID}-",null]]
	 * If we reorder these values, the «Sales Documents Numeration» extension will work incorrectly.
	 * I need to think how to improve it.
	 */
	$v
);}