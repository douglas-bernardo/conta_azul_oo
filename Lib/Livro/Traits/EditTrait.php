<?php

namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;
use Exception;

trait EditTrait
{
    function onEdit($param)
    {
        try{
            if(isset($param['id'])){
                $id = $param['id'];
                Transaction::open($this->connection);//abre a transação
                $class = $this->activeRecord;        //classe de active record
                $object = $class::find($id);         //instancia o active record
                $this->form->setData($object);       //lança os dados no formulário
                Transaction::close();
            }
        }
        catch(Exception $e){
            new Message('warning', "<b>Erro:</b> " . $e->getMessage());
            Transaction::rollback();
        }
    }
}
