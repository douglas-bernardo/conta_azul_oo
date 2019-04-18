<?php

namespace Livro\Database;

final class Transaction {//final class não pode ser super classe
    private static $conn; //conexão ativa - atributos estaticos pertencem a classe n ao obj
    private static $logger;//objeto de log


    private function __construct(){} //marcando o construtor como 'private' a classe só poderá ter instancias dentro do escopo da propria classe (Transaction)

    public static function open($database){//recebe o nome do arquivo .ini
        if(empty(self::$conn))//checa se não há uma transação ativa
        {
            self::$conn = Connection::open($database);
            self::$conn->beginTransaction();//inicia a transação
            self::$logger = NULL; //desliga o log de SQL
        }
    }

    public static function get(){
        return self::$conn;//retorna a conexão ativa
    }

    public static function rollback(){
        if (self::$conn){
            self::$conn->rollback(); //desfaz as operações realizadas
            self::$conn = NULL;
        }
    }

    public static function close(){
        if (self::$conn){
            self::$conn->commit(); //aplica as operções realizadas
            self::$conn = NULL;
        }
    }
    //recebe a instancia de alguma classe concreta filha da classe abstrata Logger (LoggerXML ou LoggerTXT)
    public static function setLogger(Logger $logger){//injeção de dependencia
        self::$logger = $logger;
    }

    public static function log($message){
        if (self::$logger) {
            self::$logger->write($message);
        }
    }
}