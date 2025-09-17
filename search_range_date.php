<?php

use App\classes\CreateLogger;

require 'vendor/autoload.php';
require 'config.php';

$searchFII = new App\classes\searchFII();
$bot = new CreateLogger();
$env = ENV;

$channel = $env['CHANNEL02'];
$tickets = [
            ['VGIR11',59,'fii'],
            // ['WHGR11',425,'fii'],
            // ['VGHF11',222,'fii'],
        ];
        $msg = '';
        $dataArray = [];
        foreach ($tickets as $ticket) {
            $msg = $searchFII->searchFII_rangeDate($ticket[0], $ticket[1],'30');
            // $bot->loggerTelegram('',$msg['msg'],'info');
            try {
                $bot->loggerTelegram('',$msg,'info', $channel);
            } catch (\Throwable $th) {
                echo $th->getMessage();
            }
            print_r($msg);
            // print_r($msg['menor']);
        }