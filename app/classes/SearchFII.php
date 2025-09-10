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
    public function searchFII_Investidor10(?string $ticker, int|string $ticker_id, string $typeTicket ='fii'): array
    {
        $env = $this->env;
        $url = "{$env['URL_FII_INV10']}/$ticker_id";

         if ($typeTicket == 'acao') {
            $url = "{$env['URL_ACAO_INV10']}/$ticker_id";
         }
        try {
            $this->Client;
            $options = [
                'multipart' => [
                    [
                        'name' => 'ticker',
                        'contents' => $ticker
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

   
    $request = new Request('GET', $url);
    $res = $this->Client->sendAsync($request, $options)->wait();
   
    $data = json_decode($res->getBody());
    // $this->log->loggerCSV("Sucesso_busca_fii","Busca realizada com sucesso para o ticker {$ticker}",'','');
    // $this->log->loggerTelegram("","Valor atual R$: {$data->price}");
    // print_r($data);
    return [
        'price' => $data->price,
        'last_update' => $data->last_update
    ];

    } catch (\Exception $e) {
    echo 'erro: ', $e->getMessage(), "\n";
    exit;
}
}
public function searchFII_statusInvest(string $ticket)
{
    $env = $this->env;
    $url = $env['URL_STATUS_INVEST'];

        $headers = [
        'Cookie' => '_adasys=16b7ac60-cbad-477a-9e30-e0c8dc34fcf0'
        ];
        $options = [
        'multipart' => [
            [
            'name' => 'ticker',
            'contents' => $ticket
            ],
            [
            'name' => 'type',
            'contents' => '1'
            ],
            [
            'name' => 'currences[]',
            'contents' => '1'
            ]
        ]];
    $request = new Request('POST', $url);
    $res = $this->Client->sendAsync($request, $options)->wait();
    $data = json_decode($res->getBody());

    print_r($data);
    return $data;
            }
}
