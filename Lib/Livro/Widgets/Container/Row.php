<?php
namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

class Row extends Element
{
    public function __construct()
    {
        parent::__construct('div');
        $this->class = 'row';
    }

    public function addCol($content)
    {
        //instancia o obj coll
        $col = new Col($content);
        //armazena no obj pai
        parent::add($col);
        //retorna o bj
        return $col;
    }
}