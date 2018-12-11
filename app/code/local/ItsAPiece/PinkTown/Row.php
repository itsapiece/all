<?php
namespace ItsAPiece\PinkTown;
/**
 * 2018-12-06
 * A row's structure:
 *	{
 * 		"additional_image_url": "https://www.pinktownusa.com/assets/Image/Product/detailsbig/BAN1413WHTGD_2.jpg",
 *		"category": "Jewelry",
 *		"color": "White",
 *		"image1_url": "https://www.pinktownusa.com/assets/Image/Product/detailsbig/BAN1413WHTGD.jpg",
 *		"last_modified_date": "11/1/2018",
 *		"map": "16.5",
 *		"material": "Gold",
 *		"quantity": "12",
 *		"short_description": "White Long Link Necklace Set Featuring Gold Rings and Drop Detail",
 *		"size": "26 inches",
 *		"sku": "BAN1413WHTGD",
 *		"srp": "27.5",
 *		"subcategory": "Necklace Set",
 *		"tags": "Necklace Set^Link^Celluloid^Gold^Ring^Long",
 *		"thumb_url": "https://www.pinktownusa.com/assets/Image/Product/thumb/BAN1413WHTGD.jpg",
 *		"title": "White Long Link Necklace Set Featuring Gold Rings and Drop Detail",
 *		"vendor": "pinktownusa",
 *		"weight": "0.5",
 *		"wholesale": "11"
 *	}
 */
final class Row {
	/**
	 * 2018-12-06
	 * @param array(string => string) $a
	 */
	function __construct(array $a) {$this->_a = $a;}

	/**
	 * 2018-12-06
	 * @return array(string => mixed)
	 */
	function a() {return $this->_a;}
	
	/**
	 * 2018-12-09
	 * @used-by \ItsAPiece\PinkTown\Updater::_p()
	 * @return string
	 */
	function category() {return str_replace('Do It Yourself !', 'Do It Yourself!', $this->v('subcategory'));}

	/**
	 * 2018-12-07
	 * Magento uses the «Matt» spelling, the `Drop_Ship_Product_Feed.csv` file uses the «Matte» spelling.
	 * @used-by \ItsAPiece\PinkTown\Updater::_p()
	 * @return string
	 */
	function color() {return str_replace('Matte', 'Matt', $this->v('color'));}

	/**
	 * 2018-12-06
	 * @used-by \ItsAPiece\PinkTown\Updater::_p()
	 * @return string
	 */
	function desc() {return $this->v('short_description');}

	/**
	 * 2018-12-09
	 * @used-by \ItsAPiece\PinkTown\A\Images::p()
	 * @return string
	 */
	function imgAdditional() {return $this->v('additional_image_url');}

	/**
	 * 2018-12-09
	 * @used-by \ItsAPiece\PinkTown\A\Images::p()
	 * @return string
	 */
	function imgPrimary() {return $this->v('image1_url');}

	/**
	 * 2018-12-07
	 * Magento uses the «Black Coating» name,
	 * the `Drop_Ship_Product_Feed.csv` file uses the «Black Material» name.
	 * @used-by \ItsAPiece\PinkTown\Updater::_p()
	 * @return string
	 */
	function material() {return str_replace('Black Material', 'Black Coating', $this->v('material'));}

	/**
	 * 2018-12-06
	 * @used-by \ItsAPiece\PinkTown\Updater::uName()
	 * @return string
	 */
	function name() {return $this->v('title');}
	
	/**
	 * 2018-12-09
	 * 2018-12-11
	 * https://www.upwork.com/messages/rooms/room_a1e68b73e6a1422b3a0fb3b7c5d03a69/story_40b7b4659233920a9ad79e2575cfcbb9
	 * @used-by \ItsAPiece\PinkTown\Updater::_p()
	 * @return float
	 */
	function price() {return 2 * floatval($this->v('wholesale'));}

	/**
	 * 2018-12-07
	 * @used-by \ItsAPiece\PinkTown\Updater::_p()
	 * @return int
	 */
	function qty() {return intval($this->v('quantity'));}

	/**
	 * 2018-12-07
	 * @used-by \ItsAPiece\PinkTown\Updater::_p()
	 * @return string
	 */
	function size() {return str_replace('  inches', ' inches', $this->v('size'));}

	/**
	 * 2018-12-06
	 * @used-by \ItsAPiece\PinkTown\A\Name::p()
	 * @used-by \ItsAPiece\PinkTown\Importer::p()
	 * @used-by \ItsAPiece\PinkTown\Inserter::p()
	 * @return string
	 */
	function sku() {return $this->v('sku');}

	/**
	 * 2018-12-07
	 * 1) Sometimes tags in the `Drop_Ship_Product_Feed.csv` file
	 * have a space at the beginning, e.g.: « Barefoot Sandals».
	 * 2) The `Drop_Ship_Product_Feed.csv` file uses 2 different tag delimeters in different rows: `^` and `,`.
	 * @used-by \ItsAPiece\PinkTown\Updater::p()
	 * @return string[]
	 */
	function tags() {return df_clean(df_trim(df_explode_multiple(['^', ','], $this->v('tags'))));}

	/**
	 * 2018-12-07
	 * @used-by \ItsAPiece\PinkTown\Updater::_p()
	 * @return float
	 */
	function weight() {return floatval($this->v('weight'));}

	/**
	 * 2018-12-06
	 * @used-by sku()
	 * @param string $k
	 * @param string|null $d [optional]
	 * @return string|null
	 */
	private function v($k, $d = null) {return dfa($this->_a, $k, $d);}

	/**
	 * 2018-12-06
	 * @var array(string => string)
	 */
	private $_a;
}