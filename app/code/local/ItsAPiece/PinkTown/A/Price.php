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
			// 2018-12-11
			// https://www.upwork.com/messages/rooms/room_a1e68b73e6a1422b3a0fb3b7c5d03a69/story_40b7b4659233920a9ad79e2575cfcbb9
			$p->setData('price', $new);
			df_log(['sku' => $p->getSku(), 'prev' => $prev, 'new' => $new]);
		}
	}
}