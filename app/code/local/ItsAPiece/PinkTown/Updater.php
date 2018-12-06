<?php
namespace ItsAPiece\PinkTown;
use Mage_Catalog_Model_Product as P;
// 2018-12-06
final class Updater {
	/**
	 * 2018-12-06
	 * @used-by p()
	 */
	private function _p() {
		$this->uName();
		$this->uDesc();
		//$this->_p->save();
	}

	private function uDesc() {
		$new = $this->_r->desc(); /** @var string $new */
		$prevL = $this->_p['description']; /** @var string $prevL */
		$prevS = $this->_p['short_description']; /** @var string $prevL */
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
			/*df_log([
				'sku' => $this->_p->getSku()
				,'new' => $new
				,'prevL' => $prevL
			]);*/
		}
		/*df_log([
			'new' => $new
			,'prevL' => $prevL
			,'prevS' => $prevS
		]);*/
	}

	/**
	 * 2018-12-06
	 * @used-by _p()
	 */
	private function uName() {
		$sku = $this->_r->sku(); /** @var string $sku */
		$name = $this->_r->name(); /** @var string $name */
		/**
		 * 2018-12-06
		 * The CSV file does not contain names for some products (the `title` column contain a sku).
		 * I do not want to re-write the product's name in Magento in this case.
		 * An example of such product:
		 *	{
		 *		"additional_i"additional_image_url": "https://www.pinktownusa.com/assets/Image/Product/detailsbig/BAN1413WHTGD_2.jpg",mage_url": "https://www.pinktownusa.com/assets/Image/Product/detailsbig/YB04241MLTRD_2.jpg",
		 *		"category": "Jewelry",
		 *		"color": "Multi Color",
		 *		"image1_url": "https://www.pinktownusa.com/assets/Image/Product/detailsbig/YB04241MLTRD.jpg",
		 *		"last_modified_date": "8/28/2018",
		 *		"map": "29",
		 *		"material": "Rhodium",
		 *		"quantity": "13",
		 *		"short_description": "Silver Multi Color Leg Anklet ",
		 *		"size": "11 inches",
		 *		"sku": "YB04241MLTRD",
		 *		"srp": "32",
		 *		"subcategory": "Anklet",
		 *		"tags": "Anklet^Silver^Multi Color^Large^Statement^Crystal^Leg^Long^Leg Chain^ Barefoot Sandals",
		 *		"thumb_url": "https://www.pinktownusa.com/assets/Image/Product/thumb/YB04241MLTRD.jpg",
		 *		"title": "YB04241MLTRD",
		 *		"vendor": "pinktownusa",
		 *		"weight": "0.03",
		 *		"wholesale": "16"
		 *	}
		 */
		$prev = $this->_p->getName(); /** @var string $prev */
		if ($sku !== $name && $prev !== $name &&
			/**
			 * 2018-12-06
			 * «Do you know why are product names in the Magento store are generally shorter
			 * than in the `Drop_Ship_Product_Feed.csv` file?
			 * Let's see the `PEN1852REDGD` product as an example.
			 * The `Drop_Ship_Product_Feed.csv` file sets the following name for it:
			 * «Short Necklace Irregular Resin Teardrop inset».
			 * The product's name in the Magento's shop is longer:
			 * «Short Necklace Irregular Resin Teardrop inset Red».
			 * My questions:
			 * 1) What should the module do in this case? Rewrite the longer name?
			 * 2) How did you produce the longer name in the previous time? Did you set in manually?»
			 * https://www.upwork.com/messages/rooms/room_a1e68b73e6a1422b3a0fb3b7c5d03a69/story_1b21b749fe8638366eb74d2a4f8f9a65
			 */
			!df_contains($prev, $name)
		) {
			//df_log("Old Name: {$this->_p->getName()}");
			//df_log("New name: {$name}");
			$this->_p['name'] = $name;
		}
		else {
			//xdebug_break();
		}
	}

	/**
	 * 2018-12-06
	 * @used-by \ItsAPiece\PinkTown\Importer::p()
	 * @param P $p
	 * @param Row $r
	 */
	static function p(P $p, Row $r) {
		$i = new self; $i->_p = $p; $i->_r = $r;
		$i->_p();
	}

	/**
	 * 2018-12-06
	 * @used-by p()
	 * @var P $p
	 */
	private $_p;

	/**
	 * 2018-12-06
	 * @var Row
	 */
	private $_r;
}