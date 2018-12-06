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