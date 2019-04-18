<?php

use Livro\Database\Record;

class ItemVenda extends Record
{
    const TABLENAME = 'item_venda';
    private $produto;
    public function set_Produto(Produto $p)
    {
        $this->produto = $p;
        $this->id_produto = $p->id;
    }

    public function get_Produto()
    {
        if(empty($this->produto)){
            $this->produto = new Produto($this->id_produto);
        }
        return $this->produto;
    }
}