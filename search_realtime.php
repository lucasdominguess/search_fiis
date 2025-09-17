<?php
require 'vendor/autoload.php';
require 'config.php';

use App\classes\SearchFII;
use App\classes\CreateLogger;

$bot = new CreateLogger();
$searchFII = new SearchFII();

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
        try {
            $bot->loggerTelegram('',$msg,'info');
            print_r($msg);
        } catch (\Throwable $th) {
            print_r($th);
        }

