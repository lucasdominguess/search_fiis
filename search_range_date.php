<?php

use App\classes\CreateLogger;

require 'vendor/autoload.php';
require 'config.php';



$searchFII = new App\classes\searchFII();
$bot = new CreateLogger();


$tickets = [
            ['VGIR11',59,'fii'],
            ['WHGR11',425,'fii'],
            ['VGHF11',222,'fii'],
            // ['PETR4',4,'acao'],
        ];
        $msg = '';
        $dataArray = [];
        foreach ($tickets as $ticket) {
            $msg = $searchFII->searchFII_rangeDate($ticket[0], $ticket[1],'7');
            $bot->loggerTelegram('',$msg,'info');
         
        }