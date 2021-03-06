<?php
namespace ItsAPiece\PinkTown\A;
use ItsAPiece\PinkTown\Row as R;
use Mage_Catalog_Model_Product as P;
// 2018-12-07
final class Name {
	/**
	 * 2018-12-07  
	 * @used-by \ItsAPiece\PinkTown\Inserter::p()   
	 * @used-by \ItsAPiece\PinkTown\Updater::p()
	 * @param P $p
	 * @param R $r
	 */
	static function p(P $p, R $r) {
		$sku = $r->sku(); /** @var string $sku */
		$name = $r->name(); /** @var string $name */
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
		$prev = $p->getName(); /** @var string $prev */
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
			 *
			 * 2018-12-09
			 * «not sure about name.
			 * No, it was not set maunally.
			 * I don't know how my previous prorammer uploaed file,
			 * I believe he created a script to scrap and create file from wholesaler website
			 * before they stated providing csv file feed.
			 * As for what should be done, I think it should right the longer description
			 * we can keep it shorter if its eaiser.
			 * what's your suggestion?»
			 */
			!df_contains($prev, $name)
		) {
			df_log("Old name: {$p->getName()}");
			df_log("New name: {$name}");
			$p->setData('name', $name);
		}
	}
}