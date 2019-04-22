<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Container\Row;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Dialog\Modal;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Base\Element;

class ClientesForm extends Page
{
    private $form;
    public function __construct() {
        parent::__construct();

        //page title
        $row = new Row;
        $row->class .= ' pl-3 pt-3 mb-3';
        $label = new Element('h3');
        $label->add('Clientes');
        $col = $row->addCol($label);
        $col->class = 'col-3';
        parent::add($row);

    }
}