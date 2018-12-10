<?php
use \Exception as E;

/**
 * @param mixed $cond
 * @param string|\Exception $m [optional]
 * @return mixed
 * @throws E
 */
function df_assert($cond, $m = null) {return $cond ?: df_error($m);}

/**
 * @param string $m
 * @throws E
 */
function df_error($m) {throw new E($m);}

/**
 * @param mixed|mixed[] $v
 * @param bool $allowNull [optional]
 * @return int|int[]
 */
function df_int($v, $allowNull = true) {
	/** @var int|int[] $r */
	if (is_array($v)) {
		$r = df_map(__FUNCTION__, $v, $allowNull);
	}
	else {
		if (is_int($v)) {
			$r = $v;
		}
		elseif (is_bool($v)) {
			$r = $v ? 1 : 0;
		}
		else {
			if ($allowNull && (is_null($v) || ('' === $v))) {
				$r = 0;
			}
			else {
				$r = (int)$v;
			}
		}
	}
	return $r;
}