<?php
namespace ItsAPiece\PinkTown;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Product_Visibility as V;
// 2018-12-06
final class Inserter {
	/**
	 * 2018-12-06
	 * @used-by \ItsAPiece\PinkTown\Importer::p()
	 * @param Row $r
	 * @return P
	 */
	static function p(Row $r) {
		$wid = (int)\Mage::app()->getWebsite()->getId(); /** @var int $wid */
		return df_admin_call(function() use($r, $wid) {
			$p = new P;
			$p->addData([
				'attribute_set_id' => $p->getDefaultAttributeSetId()
				,'sku' => $r->sku()
				,'status' => 1
				,'tax_class_id' => 0
				,'type_id' => 'simple'
				,'visibility' => V::VISIBILITY_BOTH
				,'website_ids' => [$wid]
			]);
			A\Name::p($p, $r);
			df_log("Creating product {$p->getSku()} «{$p->getName()}»");
			$p->save();
			return $p;
		});
	}
}