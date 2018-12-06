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
	 * 2018-12-06
	 * @used-by \ItsAPiece\PinkTown\Updater::uName()
	 * @return string
	 */
	function name() {return $this->v('title');}

	/**
	 * 2018-12-06
	 * @return string
	 */
	function sku() {return $this->v('sku');}

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