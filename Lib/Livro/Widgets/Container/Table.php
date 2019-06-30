<?php
namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

Class Table extends Element
{
    public function __construct()
    {
        parent::__construct('table');
    }

    public function addRow()
    {
        // instancia obj linha
        $row = new TableRow;//composição
        //armazena no array de linhas
        parent::add($row);//classe pai Element
        return $row; //retorna o obj linha instanciado
    }
}

