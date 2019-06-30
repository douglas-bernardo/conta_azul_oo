<?php
namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Widgets\Dialog\Message;
use Exception;

trait ReloadTraitTeste
{
    function onReload()
    {
        try{

            foreach ($this->activeRecord as $atv => $dtg){                
                Transaction::open($this->connection);    //abre a transação
                $repository = new Repository($atv);      //cria um repositório
                $criteria = new Criteria;                //cria um critério de seleção de dados
                $criteria->setProperty('order', 'id');                
                if(isset($this->filter)){                //verifica se há filtro
                    $criteria->add($this->filter);
                }                
                $objects = $repository->load($criteria); //carrega os objetos que satisfazem o critério
                $dtg->clear();
                if($objects){
                    foreach($objects as $object){                        
                        $dtg->addItem($object);         //adiciona objeto no datagrid
                    }
                }                
                Transaction::close();
            }
            
        }
        catch(Exception $e){
            new Message('warning', $e->getMessage());
        }
    }
}
