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
    ['VGIR11', 59, 'fii'],
    // ['BPML11', 163, 'fii'],
    // ['HCTR11', 53, 'fii'],
    // ['WHGR11', 425, 'fii'],
    // ['VGHF11', 222, 'fii'],
];
$msg = '';
$dataArray = [];
foreach ($tickets as $ticket) {
    $msg = $searchFII->searchFII_rangeDate($ticket[0], $ticket[1], '30');

    try {
        $bot->loggerTelegram('', $msg, 'info', $channel);
    } catch (\Throwable $th) {
        $bot->loggerTelegram('', $th->getMessage(), 'error');
    }
}