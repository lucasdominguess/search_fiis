<?php


require 'vendor/autoload.php';
require 'config.php';

$searchFII = new App\classes\searchFII();

$data = $searchFII->buscarCotacaoFII('VGIR11');

