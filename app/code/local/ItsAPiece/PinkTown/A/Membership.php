<?php
namespace ItsAPiece\PinkTown\A;
use Mage_Catalog_Model_Product as P;
// 2018-12-11
final class Membership {
	/**
	 * 2018-12-11
	 * @used-by p()
	 * @return array(int => int[])
	 */
	private function map() {
		if (!isset($this->{__METHOD__})) {
			/** @var \Varien_Db_Select $sel */
			$sel = df_select()->from([df_table('membership/packageproduct')], ['package_id', 'product_id']);
			$rows = df_conn()->fetchAll($sel);
			$r = []; /** @var array(int => int[]) $r */
			foreach ($rows as $d) {  /** @var array(string => int) $d */
				$pa = intval(dfa($d, 'package_id')); /** @var int $pa */
				if (!isset($r[$pa])) {
					$r[$pa] = [];
				}
				$r[$pa][]= intval(dfa($d, 'product_id'));
			}
			$this->{__METHOD__} = $r;
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2018-12-11
	 * @used-by \ItsAPiece\PinkTown\Inserter::p()
	 * @param P $p
	 */
	static function p(P $p) {
		static $i; /** @var self $i */
		$i = $i ?: new self;
		$id = intval($p->getId()); /** @var int $id */
		$add = []; /** @var int[] $add */
		foreach ($i->map() as $paId => $pa) { /** @var int $paId */ /** @var int[] $pa */
			if (!in_array($id, $pa)) {
				$add[]= $paId;
			}
		}
		if ($add) {
			df_log("[{$p->getSku()}] Upding membership prices...");
			df_conn()->insertMultiple(df_table('membership/packageproduct'), array_map(function($paId) use($id) {
				return ['product_id' => $id, 'package_id' => $paId];
			}, $add));
		}
	}
}