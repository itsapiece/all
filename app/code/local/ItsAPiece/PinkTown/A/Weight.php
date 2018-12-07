<?php
namespace ItsAPiece\PinkTown\A;
use Mage_Catalog_Model_Product as P;
// 2018-12-07
final class Weight {
	/**
	 * 2018-12-07
	 * @param P $p
	 * @param float $new
	 */
	static function p(P $p, $new) {
		$prev = floatval($p->getWeight()); /** @var float $prev */
		if ($prev !== $new) {
			$p->setData('weight', $new);
		}
	}
}


