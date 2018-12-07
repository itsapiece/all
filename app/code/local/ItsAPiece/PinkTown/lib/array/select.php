<?php
/**
 * 2015-02-11
 * Из ассоциативного массива $source выбирает элементы с ключами $keys.
 * В отличие от @see dfa_select_ordered() не учитывает порядок ключей $keys
 * и поэтому работает быстрее, чем @see dfa_select_ordered().
 * @param array(string => string)|\Traversable $source
 * @param string[] $keys
 * @return array(string => string)
 */
function dfa_select($source, array $keys)  {return
	array_intersect_key(df_ita($source), array_fill_keys($keys, null))
;}