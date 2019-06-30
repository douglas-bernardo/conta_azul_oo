<?php

use Livro\Database\Record;
use Livro\Database\Criteria;
use Livro\Database\Repository;
use Livro\Database\Filter;

class Venda extends Record
{
    const TABLENAME = 'venda';
    private $itens;
    private $cliente;

    public function setCliente(Pessoa $c)
    {
        $this->cliente = $c;
        $this->id_cliente = $c->id;
    }

    public function getCliente()
    {
        if(empty($this->cliente)){
            $this->cliente = new Pessoa($this->id_cliente);
        }
        return $this->cliente;//retorna o objeto selecionado
    }

    public function addItem(Produto $p, $quantidade)
    {
        $item = new ItemVenda;
        $item->produto = $p;
        $item->preco = $p->preco_venda;
        $item->quantidade = $quantidade;
        $this->itens[] = $item;
        $this->valor_venda += ($item->preco * $quantidade);
    }

    public function store()
    {
        parent::store();//armazena a venda
        //percorre os itens da venda
        foreach($this->itens as $item){
            $item->id_venda = $this->id;
            $item->store();//armazena o item
        }
    }

    public function getItens()
    {
        //instancia um repositorio de item
        $repository = new Repository('ItemVenda');
        //define o critério de filtro
        $criterio = new Criteria;
        $criterio->add(new Filter('id_venda', '=', $this->id));
        $this->itens = $repository->load($criterio); //carrega a coleção
        return $this->itens;//retorna os itens
    }
}