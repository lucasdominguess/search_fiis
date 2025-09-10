<?php

use App\classes\CreateLogger;

require 'vendor/autoload.php';
require 'config.php';



$searchFII = new App\classes\searchFII();
$bot = new CreateLogger();

try {
    $data= $searchFII->searchFII_statusInvest('VGIR11');

    print_r($data);

} catch (\Throwable $th) {

}