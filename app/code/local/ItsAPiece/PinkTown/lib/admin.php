<?php
use ItsAPiece\PinkTown\AdminMode as A;
/**    
 * 2018-12-10   
 * @used-by \ItsAPiece\PinkTown\A\Category::p()
 * @used-by \ItsAPiece\PinkTown\Inserter::p()
 * @param \Closure $f
 * @return mixed
 * @throws Exception
 */
function df_admin_call(\Closure $f) {return A::call($f);}