<?php
namespace ItsAPiece\PinkTown\A;
use Mage_Catalog_Model_Product as P;
// 2018-12-07
final class Desc {
	/**
	 * 2018-12-07
	 * @param P $p
	 * @param string $new
	 */
	static function p(P $p, $new) {
		/** 2018-12-09  use @uses strval(() to convert `null` to ''. */
		$prevL = strval($p['description']); /** @var string $prevL */
		$prevS = strval($p['short_description']); /** @var string $prevL */
		/**
		 * 2018-12-06
		 * Only a few products have a short description in Magento.
		 * The full list:
		 * PEC3188BLKRD: Body Chains
		 * PEC3203BROGD: Body Chains
		 * PEC3203WHTGD: Body Chains
		 * PEC3204BROGD: Body Chains
		 * PEC3204WHTGD: Body Chains
		 * CHE2750BLUGD: Wear your favorite stone color with this classic earrings. Post Back Closure. 4.5 Inches Long.
		 * VIE0069PNKGD: Cute and Romantic Pink Gold Stone Earring. Post Back Closure.
		 * CHE2938CLEGD: Gold twisted metal drop earrings with clear round stones. Post back closure
		 * TKN0203CREGD: Gold metal necklace set with dropping cream pearls. Lobster clasp closure
		 * HYE180CLEGD: Gold metal linked stud earrings with clear stones. Post back closure
		 * PEN1208REDGD: Gold metal chain
		 */
		if ($prevS) {
			//df_log("{$this->_p->getSku()}: $prevS");
		}
		if ($new !== $prevL) {
			df_log(['sku' => $p->getSku(), 'new' => $new, 'prevL' => $prevL]);
			$p->setData('description', $new);
		}
		/*df_log([
			'new' => $new
			,'prevL' => $prevL
			,'prevS' => $prevS
		]);*/
	}
}