<?php
/**
 * @param mixed|mixed[] $v
 * @return mixed[]|string[]|float[]|int[]
 */
function df_array($v) {return is_array($v) ? $v : [$v];}

/**
 * 2015-02-11
 * Эта функция отличается от @see iterator_to_array() тем, что допускает в качестве параметра
 * не только @see \Traversable, но и массив.
 * @param \Traversable|array $t
 * @return array
 */
function df_ita($t) {return is_array($t) ? $t : iterator_to_array($t);}

/**
 * Раньше функция @see dfa() была универсальной:
 * она принимала в качестве аргумента $entity как массивы, так и объекты.
 * В 99.9% случаев в качестве параметра передавался массив.
 * Поэтому ради ускорения работы системы
 * вынес обработку объектов в отдельную функцию @see dfo()
 * @param array(int|string => mixed) $a
 * @param string|string[]|int $k
 * @param mixed|callable $d
 * @return mixed|null|array(string => mixed)
 */
function dfa(array $a, $k, $d = null) {return isset($a[$k]) ? $a[$k] : $d;}

/**
 * 2017-02-18
 * [array|callable, array|callable] => [array, callable]
 * @param callable|array(int|string => mixed)|array[]\Traversable $a
 * @param null|callable|array(int|string => mixed)|array[]|\Traversable $b [optional]
 * @return array(int|string => mixed)
 */
function dfaf($a, $b) {return is_callable($a) ? [$b, $a] : [$a, $b];}