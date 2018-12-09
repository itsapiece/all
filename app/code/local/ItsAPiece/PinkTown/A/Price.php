<?php
namespace ItsAPiece\PinkTown\A;
use Mage_Catalog_Model_Product as P;
// 2018-12-09
final class Price {
	/**
	 * 2018-12-07
	 * @param P $p
	 * @param float $new
	 */
	static function p(P $p, $new) {
		$prev = floatval($p->getPrice()); /** @var float $prev */
		if ($prev !== $new) {
			//$p->setData('price', $new);
			//df_log(['sku' => $p->getSku(), 'prev' => $prev, 'new' => $new]);
		}
	}
}