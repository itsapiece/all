<?php
namespace ItsAPiece\PinkTown\A;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Resource_Eav_Attribute as A;
// 2018-12-07
final class Color {
	/**
	 * 2018-12-07
	 * @param P $p
	 * @param string $new
	 */
	static function p(P $p, $new) {
		$prev = $p->getAttributeText('color'); /** @var string $prev */
		if ($new !== $prev) {
			df_log([
				'sku' => $p->getSku()
				,'new' => $new
				,'prev' => $prev
			]);
			$a = $p->getResource()->getAttribute('color'); /** @var A $a */
			df_assert($a->usesSource());
			$id = $a->getSource()->getOptionId($new);
			df_assert($id);
			$p['color'] = $id;
		}
	}
}


