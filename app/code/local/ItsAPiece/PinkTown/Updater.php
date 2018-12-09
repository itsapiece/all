<?php
namespace ItsAPiece\PinkTown;
use Mage_Catalog_Model_Product as P;
// 2018-12-06
final class Updater {
	/**
	 * 2018-12-06
	 * @used-by \ItsAPiece\PinkTown\Importer::p()
	 * @param P $p
	 * @param Row $r
	 */
	static function p(P $p, Row $r) {
		A\Color::p($p, $r->color());
		A\Desc::p($p, $r->desc());
		// 2018-12-09
		// It is important to import the primary images before the additional one:
		// images are shown on the frontend product pages in the order of its addition
		// (the «Sort Order» property is ignored for an unknown reason).
		A\Image\Primary::p($p, $r->imgPrimary());
		A\Image\Additional::p($p, $r->imgAdditional());
		A\Material::p($p, $r->material());
		A\Name::p($p, $r);
		A\Qty::p($p, $r->qty());
		A\Size::p($p, $r->size());
		A\Tags::p($p, $r->tags());
		A\Weight::p($p, $r->weight());
	}
}