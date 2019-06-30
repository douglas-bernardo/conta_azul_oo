<?php
namespace Livro\Database;
use PDO;
use Exception;

final class Connection{//final class não pode ter descendentes - n pode ser super classe
    //marcando o método construtor como 'private', fará com que esse metodo
    //só possa ser chamado dentro do escopo da propria classe
    private function __construc(){}

    public static function open($name){
        //verifica se existe o arquivo de configuração para este banco de dados
        if(file_exists("App/Config/{$name}.ini")){
            //le o arquivo em forma de array
            $db = parse_ini_file("App/Config/{$name}.ini");
        }
        else {
            throw new Exception("Arquivo '$name' não encontrado!");
        }

        //lê as informações contidas no arquivo
        $user = isset($db['user'])? $db['user'] : NULL;
        $pass = isset($db['pass'])? $db['pass'] : NULL;
        $name = isset($db['name'])? $db['name'] : NULL;
        $host = isset($db['host'])? $db['host'] : NULL;
        $type = isset($db['type'])? $db['type'] : NULL;
        $port = isset($db['port'])? $db['port'] : NULL;

        //descobre qual o tipo (driver) de banco de dados
        switch ($type) {
            case 'pgsql':
                $port = $port ? $port : '5432';
                $conn = new PDO("pgsql:dbname={$name};user={$user};password={$pass};
                                 host=$host;port={$port}");
                break;
  
            case 'mysql':
                $port = $port ? $port : '3306';//operador ternario
                $conn = new PDO("mysql:host={$host};port={$port};dbname={$name}",$user,$pass);
                break;                
        }

        //define para que o PDO lance as exceções na ocorrência de erros
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }    
}