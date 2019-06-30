<?php
namespace Livro\Traits;

use Livro\Control\Action;
use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Exception;

trait DeleteTrait
{
    function onDelete($param)
    {
        $id = $param['id'];
        $action1 = new Action(array($this, 'Delete'));
        $action1->setParameter('id', $id);
        //confirmação
        new Question('Deseja realmente excluir o registro?', $action1);
    }

    function Delete($param)
    {
        try{
            $id = $param['id'];
            $activeRecord = $param['activeRecord'];
            Transaction::open($this->connection); //abre a transação
            $class  = $activeRecord;              //cria um repositório
            $object = $class::find($id);
            $object->delete();
            Transaction::close();
            $this->onReload();
        }
        catch(Exception $e){
            new Message('error', $e->getMessage());
        }
    }
}
