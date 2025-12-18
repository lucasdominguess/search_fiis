<?php 

require 'vendor/autoload.php';
require 'config.php';

use App\classes\SearchFII;
use App\classes\CreateLogger;

$bot = new CreateLogger();
$searchFII = new SearchFII();


$data = $searchFII->calcPercent(1,1);
print_r(json_decode($data));