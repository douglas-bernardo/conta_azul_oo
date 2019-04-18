<?php

namespace Livro\Log;
//classe filha da classe abstrata logger
class LoggerTXT extends Logger
{
    public function write($message){
        date_default_timezone_set('America/Sao_Paulo');
        $time = date("Y-m-d H:i:s");

        //Monta a string
        $text = "$time :: $message\n";

        //adiciona ao final do arquivo
        //$this->filename foi alimentado no construtor da classe Logger
        $handler = fopen($this->filename, 'a');
        fwrite($handler, $text);
        fclose($handler);
    }
}
