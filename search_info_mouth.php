<?php
require 'vendor/autoload.php';
require 'config.php';

use App\classes\SearchFII;
use App\classes\CreateLogger;

$bot = new CreateLogger();
$searchFII = new SearchFII();
$env = ENV;
$channel = $env['CHANNEL02'];

$tickets = [
   ['VGIR11', 59, 'fii', 0.13],
   # ['WHGR11', 425, 'fii', 0.10],
   # ['VGHF11', 222, 'fii', 0.08],
    // ['PETR4', 4, 'acao', 0.63],
    // ['VGRI11', 559, 'fii', 0.12],
    ['GGRC11', 35, 'fii', 0.10]
];
$msg = '';
$dataArray = [];
foreach ($tickets as $ticket) {
    $msg = $searchFII->searchFII_rangeDate($ticket[0], $ticket[1],'60');

    try {
        $bot->loggerTelegram('', $msg, 'info', $channel);
    } catch (\Throwable $th) {
        $bot->loggerTelegram('', $th->getMessage(), 'error');
    }
}