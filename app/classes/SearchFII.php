<?php
namespace App\classes;

use GuzzleHttp\Psr7\Request;
use App\classes\Dependencies;

class searchFII extends Dependencies
{
    public function __construct()
    {
        parent::__construct();
    }
    public function buscarCotacaoFII(?string $ticker): array
    {
        $env = $this->env;
        try {
            $this->Client;
            $options = [
                'multipart' => [
                    [
                        'name' => 'ticker',
                        'contents' => 'VGIR11'
                    ],
                    [
                        'name' => 'type',
                        'contents' => '1'
                    ],
                    [
                        'name' => 'currences[]',
                        'contents' => '1'
                    ]
                ]
            ];
    $request = new Request('GET', "https://investidor10.com.br/api/cotacao/fii/{$env['FII_ID']}");
    $res = $this->Client->sendAsync($request, $options)->wait();
   
    $data = json_decode($res->getBody());
    // $this->log->loggerCSV("Sucesso_busca_fii","Busca realizada com sucesso para o ticker {$ticker}",'','');
    $this->log->loggerTelegram("","Valor atual R$: {$data->price}");
    print_r($data);
    return [
        'price' => $data->price,
        'last_update' => $data->last_update
    ];

    } catch (\Exception $e) {
    echo 'erro: ', $e->getMessage(), "\n";
    exit;
}
}
}
