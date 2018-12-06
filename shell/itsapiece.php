<?php
require_once dirname(dirname(__FILE__)) . '/app/Mage.php';
use ItsAPiece\PinkTown\Importer as I;
Mage::app();
$i = new I;
$i->p();