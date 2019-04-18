<?php

namespace Livro\Log;

abstract class Logger{

    protected $filename; //local do arquivo de LOG path

    public function __construct($filename){
        $this->filename = $filename;
        //Se filename não existir, o arquivo é criado. 
        file_put_contents($filename, ''); //limpa o conteúdo do arquivo
    }

    //define o método write como obrigatório nas classes filhas (assinatura)
    abstract function write($message);
}
