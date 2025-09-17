<?php
require 'vendor/autoload.php';
require 'config.php';

use App\classes\SearchFII;
use App\classes\CreateLogger;

$bot = new CreateLogger();
$searchFII = new SearchFII();

$tickets = [
    ['VGIR11', 59, 'fii', 0.12],
    ['WHGR11', 425, 'fii', 0.10],
    ['VGHF11', 222, 'fii', 0.09],
    ['PETR4', 4, 'acao', 0.63],
];
$msg = '';
$dataArray = [];
foreach ($tickets as $ticket) {
    $data = $searchFII->searchFII_Investidor10($ticket[0], $ticket[1], $ticket[2]);
    $dataArray[$ticket[0]] = $data;
    $hora = date('H:i', strtotime($data['last_update']));

    $preco = (float) $data['price'];
    $dividendo = (float) $ticket[3];
    $percentual = $preco > 0 ? ($dividendo / $preco) * 100 : 0;



    $msg .= "\n{$ticket[0]} - 
Valor: {$preco} 
Hora: {$hora}
Dividendo: {$dividendo} (" . number_format($percentual, 2, ',', '.') . "%)\n";
}
try {
    $bot->loggerTelegram('', $msg, 'info');
    print_r($msg);
} catch (\Throwable $th) {
    print_r($th);
}

