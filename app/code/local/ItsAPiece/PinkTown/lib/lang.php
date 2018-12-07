<?php
use Closure as F;
/**
 * 2017-04-15
 * @param F $try
 * @param F|bool|mixed $onError [optional]
 * @return mixed
 * @throws \Exception
 */
function df_try(F $try, $onError = null) {
	try {return $try();}
	catch(\Exception $e) {return $onError instanceof F ? $onError($e) : (
		true === $onError ? df_error($e) : $onError
	);}
}