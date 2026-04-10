<?php
require 'vendor/autoload.php';
require 'config.php';

use App\classes\SearchFII;
use App\classes\CreateLogger;

$bot = new CreateLogger();
$searchFII = new SearchFII();

/*
|--------------------------------------------------------------------------
| Função que define o emoji da tendência
|--------------------------------------------------------------------------
*/
function trendEmoji(?float $old, float $current): array
{
    if ($old === null) return ['🆕', 0];

    $diff = $current - $old;

    if ($diff > 0) return ['📈', $diff];
    if ($diff < 0) return ['📉', $diff];

    return ['➖', 0];
}

$tickets = [
    ['VGIR11', 59, 'fii', 0.13],
    ['PETR4', 4, 'acao', 0.63],
    ['VGRI11', 559, 'fii', 0.12],
    ['GGRC11', 35, 'fii', 0.10]
];

$channel02 = ENV['CHANNEL02'];

$msg = " \n";

foreach ($tickets as $ticket) {

    $data = $searchFII->searchFII_Investidor10($ticket[0], $ticket[1], $ticket[2]);
    $lastDividend = $searchFII->getLastDIvidend($ticket[1],30);

    $hora = date('H:i', strtotime($data['last_update']));
    $preco = (float) $data['price'];
    $lastDividend = (float) $lastDividend[0]->price;

    $percentual = $preco > 0 ? ($lastDividend / $preco) * 100 : 0;

    /*
    |--------------------------------------------------------------------------
    | LER PREÇO ANTERIOR
    |--------------------------------------------------------------------------
    */
    $file = __DIR__ . "/storage/{$ticket[0]}.json";
    $precoAnterior = null;

    if (file_exists($file)) {
        $oldData = json_decode(file_get_contents($file), true);
        if (isset($oldData['price'])) {
            $precoAnterior = (float) $oldData['price'];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DESCOBRIR TENDÊNCIA
    |--------------------------------------------------------------------------
    */
    [$emojiTrend, $diff] = trendEmoji($precoAnterior, $preco);

    $variacao = $precoAnterior !== null
        ? number_format($diff, 2, ',', '.')
        : null;

    /*
    |--------------------------------------------------------------------------
    | MONTAR MENSAGEM
    |--------------------------------------------------------------------------
    */
    $msg .= "📊 {$ticket[0]}\n";
    $msg .= "💰 Preço: R$ ".number_format($preco, 2, ',', '.')." {$emojiTrend}\n";

    if ($precoAnterior !== null) {
        $msg .= "🔄 Variação: {$variacao}\n";
    } else {
        $msg .= "🆕 Primeiro registro\n";
    }

    $msg .= "🕐 Hora: {$hora}\n";
    $msg .= "💸 lastDividend: R$ ".number_format($lastDividend, 2, ',', '.').
            " (".number_format($percentual, 2, ',', '.')."%)\n\n";

    /*
    |--------------------------------------------------------------------------
    | SALVAR NOVO PREÇO
    |--------------------------------------------------------------------------
    */
    if (!is_dir(__DIR__ . "/storage")) {
        mkdir(__DIR__ . "/storage", 0777, true);
    }

    file_put_contents($file, json_encode([
        'price' => $preco,
        'time'  => date('Y-m-d H:i:s')
    ]));
}

try {
    // enviar ao telegram
    $bot->loggerTelegram('', $msg, 'INFO', $channel02);

    // debug opcional
    echo $msg;

} catch (\Throwable $th) {
    print_r($th);
}
