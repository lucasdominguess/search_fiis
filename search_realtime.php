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
            ['PETR4',4,'acao'],
        ];
        $msg = '';
        $dataArray = [];
        foreach ($tickets as $ticket) {
            $data = $searchFII->searchFII_Investidor10($ticket[0], $ticket[1], $ticket[2]);
            $dataArray[$ticket[0]] = $data;
            $hora = date('H:i', strtotime($data['last_update']));

            $msg .= "\n{$ticket[0]} - \nValor: {$data['price']} - \nHora: {$hora}\n";
        }
        $bot->loggerTelegram('',$msg,'info');
        print_r($msg);

