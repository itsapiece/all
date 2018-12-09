<?php
namespace ItsAPiece\PinkTown\A;
use Mage_Catalog_Model_Product as P;
use Mage_CatalogInventory_Model_Stock_Item as S;
// 2018-12-07
final class Qty {
	/**
	 * 2018-12-07
	 * @param P $p
	 * @param int $new
	 */
	static function p(P $p, $new) {
		$s = \Mage::getModel('cataloginventory/stock_item'); /** @var S $s */
		$s->loadByProduct($p);
		$prev = intval($s->getQty()); /** @var int $prev */
		if ($prev !== $new) {
			$s->setQty($new);
			$s->setIsInStock(!!$new);
			$s->save();
			//df_log(['sku' => $p->getSku(), 'new' => $new, 'prev' => $prev]);
		}
	}
}