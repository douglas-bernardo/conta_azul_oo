<?php
namespace Livro\Database;

use Exception;

abstract class Record
{    
    protected $data; //array contendo os dados do obj
    public function __construct($id = NULL)
    {
        if($id){//se o ID for informado carrega o OBJ correspondente
            $object = $this->load($id);
            if($object){
                $this->fromArray($object->toArray());
            }
        }
    }

    public function __clone()
    {
        //limpa o id sempre que um obj for clonado
        unset($this->data['id']);
    }

    public function __set($prop, $value)
    {
        if (method_exists($this, 'set_'.$prop)){
            call_user_func(array($this, 'set_'.$prop), $value);
        }
        else {
            if ($value ==  NULL){
                unset($this->data[$prop]);
            }
            else{
                $this->data[$prop] = $value; //atribui o valor da propriedade
            }
        }
    }

    public function __get($prop)
    {
        if (method_exists($this, 'get_'.$prop)){
            //executa o metodo get_<prop>
            return call_user_func(array($this, 'get_'.$prop));
        }
        else {
            if(isset($this->data[$prop])){
            return $this->data[$prop];
            }
        }
    }

    public function __isset($prop)
    {
        return isset($this->data[$prop]);
    }

    public function getEntity()
    {
        $class = get_class($this); //obtém o nome da classe
        //Retorna o valor da constante TABLENAME presente na classe filha de Record, instanciada 
        return constant("{$class}::TABLENAME"); 
    }

    public function fromArray($data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function store()
    {
        $prepare = $this->prepare($this->data);
        //verifica se tem ID ou se existe no banco de dados
        if(empty($this->data['id']) or (!$this->load($this->id))){
            //incrementa o ID
            if (empty($this->data['id'])){
                $this->id = $this->getLast() + 1;
                $prepare['id'] = $this->id;
            }
            //cria uma instrução de INSERT
            $sql = "INSERT INTO {$this->getEntity()} " .
                '('. implode(',', array_keys($prepare)) . ')'.
                ' VALUES ' .
                '('. implode(',', array_values($prepare)) . ')';
        } else {
            //monta uma string de UPDATE
            $sql = "UPDATE {$this->getEntity()}";
            //monta os pares: coluna=valor,...
            if ($prepare) {
                foreach ($prepare as $column => $value) {
                    if ($column !== 'id') {
                        $set[] = "{$column} = {$value}";
                    }
                }
            }
            $sql .= ' SET ' . implode(', ', $set);
            $sql .= ' WHERE id=' . (int) $this->data['id']; //(int), (integer) - converte para inteiro
        }
        //obtém a transação ativa
        if($conn = Transaction::get()){
            Transaction::log($sql);
            $result = $conn->exec($sql);
            return $result;
        } else {
            throw new Exception("Não há transação ativa!");
        }
    }

    public function load($id)
    {
        //monta a instrução SELECT
        $sql = "SELECT * FROM {$this->getEntity()}";
        $sql .= ' WHERE id=' . (int) $id;
        //obtém a transação ativa
        if ($conn = Transaction::get()){
            //cria msg de log e executa a consulta
            Transaction::log($sql);
            $result = $conn->query($sql);
            //se retornou algum dado:
            if ($result){
                $object = $result->fetchObject(get_class($this));
            }
            return $object;
        } else {
            throw new Exception("Não há transação ativa!");            
        }
    }

    public function delete($id = NULL)
    {
        //o ID é o paramentro ou a propriedade ID
        $id = $id ? $id : $this->id;
        //monta a string de DELETE
        $sql = "DELETE FROM {$this->getEntity()}";
        $sql .= ' WHERE id=' . (int) $this->data['id'];
        //obtém a transação ativa
        if ($conn = Transaction::get()) {
            //faz o log e executa o SQL
            Transaction::log($sql);
            $result = $conn->exec($sql);
            return $result; //retorna o resultado
        } else {
            throw new Exception("Não há transação ativa!");            
        }
    }

    public static function find($id)
    {
        $classname = get_called_class(); //Obtém o nome da classe em que o método estático é chamado.
        $ar = new $classname;
        return $ar->load($id);
    }

    public static function all()
    {
        $classname = get_called_class();
        $rep = new Repository($classname);
        return $rep->load(new Criteria);
    }

    private function getLast()
    {
        if ($conn = Transaction::get()) {
            $sql = "SELECT max(id) FROM {$this->getEntity()}";
            //cria log e executa instrução SQL
            Transaction::log($sql);
            $result = $conn->query($sql);            
            //retorna os dados do banco (no caso o id)
            $row = $result->fetch();
            return $row[0];
        } else {
            throw new Exception("Não há transação ativa!");            
        }
    }

    public function prepare($data)
    {
        $prepared = array();
        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                if ($key == 'password'){
                    $prepared[$key] = $this->escape(md5($value));//adaptado
                } else {
                    $prepared[$key] = $this->escape($value);
                }
            }
        }
        return $prepared;
    }

    public function escape($value)
    {
        if (is_string($value) and (!empty($value))){
            //adiciona \ em aspas
            $value = addslashes($value);
            return "'$value'";
        }
        else if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }
        else if ($value !== '') {
            return $value;
        }
        else {
            return "NULL";
        }
    }
}