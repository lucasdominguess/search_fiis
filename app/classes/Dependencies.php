<?php 
declare(strict_types=1);
namespace App\classes;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\classes\CreateLogger;


abstract class Dependencies 
{
    protected CreateLogger $log;
    // protected Email $mailer;
    protected Client $Client;
    // protected Request $request;
    protected array $env;

    public function __construct()
    {   
        $env = parse_ini_file(__DIR__ . "/../../.env");
        if (!is_array($env)) {
            $env = [];
        }
         $this->env = $env; 
         define("ENV", $env);
        $this->log = new CreateLogger();
        $this->Client = new Client();
        // $this->mailer = new Email();

    }


}