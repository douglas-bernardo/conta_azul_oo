<?php
namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Widgets\Dialog\Message;
use Exception;

trait ReloadTrait
{
    function onReload()
    {
        try{
            Transaction::open($this->connection);               //abre a transação
            $repository = new Repository($this->activeRecord);  //cria um repositório
            $criteria = new Criteria;                           //cria um critério de seleção de dados
            $criteria->setProperty('order', 'id');
            //verifica se há filtro
            if(isset($this->filter)){
                $criteria->add($this->filter);
            }
            //carrega os objetos que satisfazem o critério
            $objects = $repository->load($criteria);
            $this->datagrid->clear();
            if($objects){
                foreach($objects as $object){
                    //adiciona objeto no datagrid
                    $this->datagrid->addItem($object);
                }
            }
            Transaction::close();
        }
        catch(Exception $e){
            new Message('warning', $e->getMessage());
        }
    }
}
