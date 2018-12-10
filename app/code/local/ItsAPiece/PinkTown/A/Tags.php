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
		/**
		 * 2018-12-08
		 * Previously I used the foloowing code to retrieve all tags of a product:
		 * 		$tagsPrev = new TC;
		 * 		// 2018-12-07 It prevents the failure:
		 * 		// «Item (Mage_Tag_Model_Tag) with the same id "..." already exist».
		 * 		$tagsPrev->addStoreFilter(self::storeId());
		 * 		// 2018-12-07 It prevents the failure:
		 * 		// «Unknown column 'relation.product_id' in 'where clause'».
		 * 		$tagsPrev->joinRel();
		 * 		$tagsPrev->addProductFilter($p->getId());
		 * This code is not quite correct
		 * because it loads the product's tags only for the self::storeId() store,
		 * but in the `itsapiece.com` website tags are assigned not only to the  self::storeId() store,
		 * but to the backend (0) store too.
		 * Other side, we can not remove the `$tagsPrev->addStoreFilter(self::storeId());` expression,
		 * because we will got the failure: «Item (Mage_Tag_Model_Tag) with the same id "..." already exist».
		 * That is why we now use a custom DB query to retrieve all tags of a particular product.
		 */
		$sel = df_select()->from(['t' => df_table('tag/tag')], 't.name');  /** @var \Varien_Db_Select $sel */
		$sel->joinInner(['r' => df_table('tag/relation')], 't.tag_id = r.tag_id');
		$sel->where('? = r.product_id', $p->getId());
		/**
		 * 2018-12-08
		 * 1) `$sel->distinct()` does not work properly here because Zend Framework translates it to:
		 * 		SELECT DISTINCT `t`.`name`, `r`.*
		 * instead of
		 * 		SELECT DISTINCT `t`.`name`
		 * 2) df_select()->from(['t' => df_table('tag/tag')], 'distinct(t.name)');
		 * 		does not work properly too: it is translated to:
		 * 		SELECT distinct(t.name), `r`.*
		 */
		$prev = array_unique(df_conn()->fetchCol($sel));  /** @var string[] $prev */
        $prevU = array_map('mb_strtoupper', $prev); /** @var string[] $prevU */
        $prevUMap = array_combine($prevU, $prev);  /** @var array(strinbg => string) $prevUMap */
		/**
		 * 2018-12-07
		 * @see \Mage_Core_Model_Resource_Db_Abstract::_checkUnique() will not allow us to have multiple tags
		 * in with the same name but in a different letters case: e.g.: «Clip on» and «Clip On».
		 * That is why we need to use @uses mb_strtoupper().
		 */
		$newU = array_map('mb_strtoupper', $new); /** @var string[] $newU */
        $newUMap = array_combine($newU, $new);  /** @var array(strinbg => string) $newU */
        $addU = array_diff($newU, $prevU); /** @var string[] $addU */
        $delU = array_diff($prevU, $newU); /** @var string[] $delU */
		// 2018-12-07
		// 3/4 of tags in the itsapiece.com website belong to the 1 store.
		// 1/4 tags belong to the 0 store.
		// So I decided to assign new tags to the 1 store.
		// \Mage::app()->getStore()->getId() returns `1`;
        $storeId = self::storeId(); /** @var int $storeId */
        if ($addU) {
        	$add = dfa_select($newUMap, $addU); /** @var string [] */
        	df_log("{$p->getSku()}: adding tags: %s.", [implode(', ', df_quote($add))]);
			foreach ($addU as $tsU) {  /** @var string $tsU */
				if (!($t = dfa(self::mapAll(), $tsU))) { /** @var T $t */
					$t = \Mage::getModel('tag/tag');
					$t->setName($newUMap[$tsU]);
					$t->setStatus(T::STATUS_APPROVED);
					$t->save();
					self::$_mapAll[$tsU] = $t;
				}
				$t->saveRelation($p->getId(), null, $storeId);
			}
		}
		// 2018-12-07 I think we do not need to delete manually added tags.
		if (false && $delU) {
        	$del = dfa_select($prevUMap, $delU); /** @var string[] $del */
        	df_log("{$p->getSku()}: removing tags: %s." . implode(', ', df_quote($del)));
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
	 * @see \Mage_Core_Model_Resource_Db_Abstract::_checkUnique() will not allow us to have multiple tags
	 * in with the same name but in a different letters case: e.g.: «Clip on» and «Clip On».
	 * That is why we need to use @uses mb_strtoupper().
	 * @used-by mapAll()
	 * @param TC $c
	 * @param bool $u [optional]
	 * @return array(string => T)
	 */
	private static function map(TC $c, $u = false) {return
		df_map_r($c->getItems(), function(T $t) use($u) {return [
			!$u ? $t->getName() : mb_strtoupper($t->getName()), $t
		];})
	;}

	/**
	 * 2018-12-07
	 * @return array(string => T)
	 */
	private static function mapAll() {
		if (null === self::$_mapAll) {
			self::$_mapAll = self::map(new TC, true);
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
	 * @var array(string => T)
	 */
	private static $_mapAll;
}