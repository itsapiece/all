<?php
require_once '../app/Mage.php';
use ItsAPiece\PinkTown\Importer as I;
Mage::app();
echo I::process();