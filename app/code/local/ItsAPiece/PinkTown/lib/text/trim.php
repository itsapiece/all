<?php
/**
 * Обратите внимание, что иногда вместо данной функции надо применять @see trim().
 * Например, @see df_trim() не умеет отсекать нулевые байты,
 * которые могут образовываться на конце строки
 * в результате шифрации, передачи по сети прямо в двоичном формате, и затем обратной дешифрации
 * посредством @see Varien_Crypt_Mcrypt.
 * @see Df_Core_Model_RemoteControl_Coder::decode()
 * @see Df_Core_Model_RemoteControl_Coder::encode()
 * 2017-07-01 Добавил параметр $throw.
 * @used-by df_parse_colon()
 * @param string|string[] $s
 * @param string $charlist [optional]
 * @param bool|mixed|\Closure $throw [optional]
 * @return string|string[]
 */
function df_trim($s, $charlist = null, $throw = false) {return df_try(function() use($s, $charlist, $throw) {
	/** @var string|string $result */
	if (is_array($s)) {
		$result = df_map('df_trim', $s, [$charlist, $throw]);
	}
	else {
		if (!is_null($charlist)) {
			/** @var string[] $addionalSymbolsToTrim */
			$addionalSymbolsToTrim = ["\n", "\r", ' '];
			foreach ($addionalSymbolsToTrim as $addionalSymbolToTrim) {
				/** @var string $addionalSymbolToTrim */
				if (!df_contains($charlist, $addionalSymbolToTrim)) {
					$charlist .= $addionalSymbolToTrim;
				}
			}
		}
		/**
		 * Обратите внимание, что класс Zend_Filter_StringTrim может работать некорректно
		 * для строк, заканчивающихся заглавной кириллической буквой «Р».
		 * http://framework.zend.com/issues/browse/ZF-11223
		 * Однако решение, которое предложено по ссылке выше
		 * (http://framework.zend.com/issues/browse/ZF-11223)
		 * может приводить к падению интерпретатора PHP
		 * для строк, начинающихся с заглавной кириллической буквы «Р».
		 * Такое у меня происходило в методе @see Df_Autotrading_Model_Request_Locations::parseLocation()
		 * Кто виноват: решение или исходный класс @see Zend_Filter_StringTrim — не знаю
		 * (скорее, решение).
		 * Поэтому мой класс @see \Df\Zf\Filter\StringTrim дополняет решение по ссылке выше
		 * программным кодом из Zend Framework 2.0.
		 */
		/** @var \Zend_Filter_StringTrim $filter */
		$filter = new \Zend_Filter_StringTrim($charlist);
		$result = $filter->filter($s);
		/**
		 * @see Zend_Filter_StringTrim::filter() теоретически может вернуть null,
		 * потому что этот метод зачастую перепоручает вычисление результата функции @uses preg_replace()
		 * @url http://php.net/manual/function.preg-replace.php
		 */
		$result = df_nts($result);
		// Как ни странно, Zend_Filter_StringTrim иногда выдаёт результат « ».
		if (' ' === $result) {
			$result = '';
		}
	}
	return $result;
}, false === $throw ? $s : $throw);}