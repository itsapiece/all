<?php
namespace ItsAPiece\PinkTown\A;
use Mage_Catalog_Model_Product as P;
use Mage_Catalog_Model_Category as C;
use Mage_Catalog_Model_Resource_Category_Collection as CC;
// 2018-12-09
final class Category {
	/**
	 * 2018-12-09
	 * @param P $p
	 * @param string $new
	 */
	static function p(P $p, $new) {
		// 2018-12-10 The «subcategory» field in the `Drop_Ship_Product_Feed.csv` file can be empty.
		if ($new) {
			$prev = array_keys(self::map($p->getCategoryCollection())); /** @var string[] $prev */
			if (!in_array($new, $prev)) {
				self::mapAll();
				if (!($newC = dfa(self::$_mapAll, $new))) {  /** @var C $newC */
					$newC = df_admin_call(function() use($new, $p) {
						$more = self::$_mapAll['More'];  /** @var C $more */
						$newC = new C;
						$newC->addData([
							'display_mode' => 'PRODUCTS'
							,'is_active' => 1
							,'is_anchor' => 1
							,'name' => $new
							,'path' => $more->getPath()
							,'store_id' => \Mage::app()->getStore()->getId()
						]);
						return $newC->save();						
					});
					self::$_mapAll[$new] = $newC;
				}
				$p->setCategoryIds(array_merge($p->getCategoryIds(), [$newC->getId()]));
				df_log(['sku' => $p->getSku(), 'new' => $new, 'current' => [implode(', ', df_quote($prev))]]);
			}
		}
	}

	/**
	 * 2018-12-07
	 * @see \Mage_Core_Model_Resource_Db_Abstract::_checkUnique() will not allow us to have multiple tags
	 * in with the same name but in a different letters case: e.g.: «Clip on» and «Clip On».
	 * That is why we need to use @uses mb_strtoupper().
	 * @used-by mapAll()
	 * @used-by p()
	 * @param CC $cc
	 * @return array(string => T)
	 */
	private static function map(CC $cc) {return df_map_r(
		$cc->addAttributeToSelect('name')->getItems(), function(C $c) {return [$c->getName(), $c];}
	);}

	/**
	 * 2018-12-09
	 * @return array(string => C)
	 */
	private static function mapAll() {
		if (null === self::$_mapAll) {
			self::$_mapAll = self::map(new CC);
		}
		return self::$_mapAll;
	}

	/**
	 * 2018-12-09
	 * @used-by mapAll()
	 * @var array(string => C)
	 */
	private static $_mapAll;
}