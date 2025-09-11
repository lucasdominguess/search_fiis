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
    return [
        'price' => $data->price,
        'last_update' => $data->last_update
    ];
    } catch (\Exception $e) {
    echo 'erro: ', $e->getMessage(), "\n";
    exit;
}
}
public function searchFII_rangeDate(string $ticket,int|string $ticket_id,string $days='30')
{
    $env = $this->env;
    $urlPart = "$ticket_id/{$days}/real/adjusted/true";
    $url = $env['URL_RANGE_DATE'];
    $url = "{$url}{$urlPart}";

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
    try {
        $request = new Request('GET', $url);
        $res = $this->Client->sendAsync($request, $options)->wait();
        $data = json_decode($res->getBody());

        $msg = "Histórico do Ativo $ticket( $days dias) :\n";
            foreach ($data->real as  $value) {
                $msg .= "{$value->created_at} - Preço: {$value->price}\n";
            }
    } catch (\Throwable $th) {
        print_r('Erro:'.$th);
    }
    return $msg;
    }
}
