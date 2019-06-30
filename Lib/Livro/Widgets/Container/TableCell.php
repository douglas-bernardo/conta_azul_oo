<?php
namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

class TableCell extends Element
{
    public function __construct($value)
    {
        parent::__construct('td');
        parent::add($value);
    }
}
