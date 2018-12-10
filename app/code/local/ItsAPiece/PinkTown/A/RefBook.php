<?php
namespace ItsAPiece\PinkTown\A;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Resource_Eav_Attribute as A;
use Mage_Catalog_Model_Resource_Product as PR;
/**
 * 2018-12-07
 * @see \ItsAPiece\PinkTown\A\Color
 * @see \ItsAPiece\PinkTown\A\Material
 */
abstract class RefBook {
	/**
	 * 2018-12-07
	 * @param P $p
	 * @param string $new
	 */
	final static function p(P $p, $new) {
		$n = df_class_llc(get_called_class()); /** @var string $n */
		if (0 !== strcasecmp($new, $prev = $p->getAttributeText($n))) { /** @var string $prev */
			df_log(['sku' => $p->getSku(), 'new' => $new, 'prev' => $prev]);
			$pr = $p->getResource(); /** @var PR $pr */
			$a = $pr->getAttribute($n); /** @var A $a */
			df_assert($a->usesSource());
			//if ('Tortoiseshell' === $new) {
			//	xdebug_break();
			//}
			if (!($id = $a->getSource()->getOptionId($new))) {
				df_log("Adding the «{$new}» option to the «{$n}» attribute.");
				$options = $a->getSource()->getAllOptions(false);
				$k = 'option_' . (1 + count($options)); /** @var string $k */
				$a->addData(['option' => ['order' => [$k => 0], 'value' => [$k => [$new]]]]);
				$a->save();
				$pr->unsetAttributes($n);
				// 2018-12-10 We need to reload the attribute to reset its cache.
				$a = $pr->getAttribute($n);
				$id = $a->getSource()->getOptionId($new);
				df_assert($id);
			}
			$p->setData($n, $id);
		}
	}
}