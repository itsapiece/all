<?php
namespace ItsAPiece\PinkTown\A;
use Mage_Catalog_Model_Product as P;
use Mage_Tag_Model_Resource_Tag_Collection as TC;
use Mage_Tag_Model_Tag as T;
// 2018-12-07
final class Tags {
	/**
	 * 2018-12-07
	 * @param P $p
	 * @param string[] $new
	 */
	static function p(P $p, $new) {
		$tagsPrev = self::c(); /** @var TC $tagsPrev */
		$tagsPrev->addProductFilter($p->getId());
        $mapPrev = self::map($tagsPrev); /** @var array(string => T) $mapPrev */
        $prev = array_keys($mapPrev);  /** @var string[] $prev */
        $add = array_diff($new, $prev); /** @var string[] $add */
        $del = array_diff($prev, $new); /** @var string[] $del */
		// 2018-12-07
		// 3/4 of tags in the itsapiece.com website belong to the 1 store.
		// 1/4 tags belong to the 0 store.
		// So I decided to assign new tags to the 1 store.
		// \Mage::app()->getStore()->getId() returns `1`;
        $storeId = self::storeId(); /** @var int $storeId */
        if ($add) {
        	df_log("{$p->getSku()}: adding tags: %s." . implode(', ', df_quote($add)));
			foreach ($add as $ts) {  /** @var string $ts */
				if (!dfa(self::mapAll(), $ts)) {
					$t = \Mage::getModel('tag/tag'); /** @var T $t */
					$t->setName($ts);
					$t->setStatus(T::STATUS_APPROVED);
					$t->save();
					self::$_mapAll[$ts] = $t;
					$t->saveRelation($p->getId(), null, $storeId);
				}
			}
		}
		// 2018-12-07 I think we do not need to delete manually added tags.
		if (false && $del) {
        	df_log("{$p->getSku()}: removing tags: %s." . implode(', ', df_quote($add)));
            df_conn()->delete(df_table('tag/relation'), [
            	'? = product_id' => $p->getId()
				,'tag_id IN (?)' =>
					array_map(function(T $t) {return $t->getId();}, dfa_select(self::mapAll(), $del))
            ]);
            self::$_mapAll = dfa_unset(self::$_mapAll, $del);
		}
	}

	/**
	 * 2018-12-07
	 * @used-by mapAll()
	 * @used-by p()
	 * @return TC
	 */
	private static function c() {
		$r = new TC; /** @var TC $r */
		// 2018-12-07 It prevents the failure: «Item (Mage_Tag_Model_Tag) with the same id "..." already exist».
		$r->addStoreFilter(self::storeId());
		// 2018-12-07 It prevents the failure: «Unknown column 'relation.product_id' in 'where clause'».
		$r->joinRel();
		return $r;
	}

	/**
	 * 2018-12-07
	 * @used-by mapAll()
	 * @param TC $c
	 * @return array(string => TC)
	 */
	private static function map(TC $c) {return df_map_r($c->getItems(), function(T $t) {return [
		$t->getName(), $t
	];});}

	/**
	 * 2018-12-07
	 * @return array(string => TC)
	 */
	private static function mapAll() {
		if (null === self::$_mapAll) {
			self::$_mapAll = self::map(self::c());
		}
		return self::$_mapAll;
	}

	/**
	 * 2018-12-07
	 * @used-by p()
	 * @return int
	 */
	private static function storeId() {return \Mage::app()->getStore()->getId();}

	/**
	 * 2018-12-07
	 * @used-by mapAll()
	 * @var array(string => TC)
	 */
	private static $_mapAll;
}


