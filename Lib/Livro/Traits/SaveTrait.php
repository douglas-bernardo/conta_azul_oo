<?php
namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;
use Exception;

trait SaveTrait
{
    function onSave()
    {
        try{
            Transaction::open($this->connection);//abre a transação
            $class = $this->activeRecord;        //classe de active record
            $dados = $this->form->getData();     //lê dados do form
            $object = new $class;                //instancia o objeto
            $object->fromArray((array) $dados);  //carrega os dados
            $object->store();                    //armazena o objeto

            Transaction::close();
            new Message('info', 'Dados armazenados com sucesso');
        }
        catch(Exception $e){
            new Message('error', $e->getMessage());
        }
    }
}
