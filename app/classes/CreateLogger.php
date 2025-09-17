<?php
namespace App\classes;


use Exception;
use Monolog\Level;
use Monolog\Logger;
use App\classes\Email;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Formatter\LineFormatter;


/**
 * Classe responsavel por criar logs de sistema 

 */
class CreateLogger {
    
/**
 * @method mixed logger() Log cria um arquivo CSV nomeado com data atual em seu conteudo ira conter msg com erro especificado e seu level 
 * @param string $title Titulo do log (ira formatar para maiusculo e substituir caracteres especiais por _ )
 * @param string $msg Menssagem com erro detalhado   
 * @param string $nivel Nivel do log setado por padrao INFO 
 * @param array|string $extra Conteudo extra (opcional) enviando com pushProcessor , setado como null por padrao

    */
    public function loggerCSV (string $title ,string $msg, ?string $nivel = 'info', ?string $path){
        try {
           
            $now = new \DateTimeZone( 'America/Sao_Paulo');
            $now_form =(new \DateTime('now',$now))->format('d-m-Y');
            
            $ftitle = strtoupper(str_replace([' ','.','-','*'],'_',$title));
            $date = $GLOBALS['days'] ?? $now_form ;
            $logger = new Logger($ftitle);

            // $ext = $extra === NULL ? 'IP REMOVIDO' :  $extra ;
            // // $extra= $_SERVER['REMOTE_ADDR'];
            // $logger->pushProcessor(function ($record) use ($ext) { 
            //     $record["extra"]["SERVER"] = $ext ;
            //     return $record ;
            // });
        } catch (\Throwable $th) {
            throw new Exception("Erro ao criar log CSV :".$th->getMessage());
        }
        $path = null ? __DIR__."logs/LOG_$date.csv" : "$path/LOG_$date.csv";
        $logger->pushHandler(new StreamHandler(dirname(__FILE__).$path));
        $logger->$nivel($msg);
}
/**
* @method mixed loggerTelegram() ferramenta do telegram ira mandar msg para o desenvolvedor com erro de nivel Warning 
* Invocando tbm metodo logger e logEmail 
* @param string $title Titulo do log (ira formatar para maiusculo e substituir caracteres especiais por _ )
* @param string $msg Menssagem com erro detalhado   
* @param bool $sendemail Torna o envio de log por email Opçional 
* @param string $nivel Nivel do log setado por padrao WARNING 
* @param array|string $extra Conteudo extra (opcional) enviando com pushProcessor , setado como null por padrao
**/
    public function loggerTelegram(string $title ,string $msg, string $nivel = 'warning',  $channel = null,$apikey = null,) {
        try {

            $env = ENV;
            $apikey ??= $env['API_KEY'];
            $channel ??= $env['CHANNEL'];
            $ftitle = strtoupper(str_replace([' ','.','-'],'_',$title));
            $logger = new Logger(strtoupper($ftitle)
        );
            $handler = new TelegramBotHandler(
                apiKey: $apikey,
                channel: $channel,
                level: Level::Info
            );
            $formatter = new LineFormatter(null, 'd/m/Y H:i:s', true, true);
            $handler->setFormatter($formatter);

            $logger->pushHandler($handler);
          
            $logger->$nivel($msg);
            
        } catch (\Throwable $th) {
            $this->loggerCSV('Erro_logTelegram','Tentativa de criar Log Telegran falhou :'.$th->getMessage(),'error',null);
            throw new Exception('Erro ao criar LogTelegran');
        }
    
}  
/**
 * @method mixed loggerEmail() Envia um email para o desenvolvedor responsavel com erro Critico
 * @param string $title (subject) Titulo do email (ira formatar para maiusculo e substituir caracteres especiais por _ )
 * @param string $msg (body)Corpo do email com Menssagem de erro detalhado   
 * @param string $nivel Nivel do log setado por padrao critical 
 * @param array|string $extra Conteudo extra (opcional) enviando com pushProcessor , setado como null por padrao
 */
//     public function loggerEmail(string $title ,string $msg ,string|array $to ="lucasdomingues25.dev@gmail.com
// ",array|string $extra = null) { 

//         try {
           
//             $fsubject = strtoupper(str_replace(['.','-'],'_',$title));
//             $logger = new Logger ($fsubject); 
            
//             if ($extra !== null) {
//             $logger->pushProcessor(function ($record) use ($extra) { 
//                 $record["extra"]["server"] = $extra ;
//                 return $record ;
//             });
//         }
//             $e = new Email;
//             $e->sendEmail($to,null,$fsubject,$msg);
//             $logger->critical('Esta é uma mensagem de erro crítico!');
//         } catch (\Throwable $th) {
//             $this->loggerCSV('Erro_log_Email','Tentativa de criar Log Por email falhou :'.$th->getMessage());
//             throw new Exception('Erro ao criar LogEmail');
//         }
        
        
//     }


}