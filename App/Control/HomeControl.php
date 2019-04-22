<?php
use Livro\Control\Page;
use Livro\Widgets\Container\Card;
use Livro\Widgets\Container\Row;
use Livro\Widgets\Container\Col;

class HomeControl extends Page
{
    public function __construct() {
        parent::__construct();

        //criando uma row Bootstrap:
        $row = new Row;

        //criando um card:
        $card = new Card('Special title treatment');
        $card->setHeader('Featured');
        $card->setBody('With supporting text below as a natural lead-in to additional content.');
        $card->setFooter('teste');

        // //criando um card2:
        $card2 = new Card('Special title treatment');
        $card2->setHeader('Featured');
        $card2->setBody('With supporting text below as a natural lead-in to additional content.');
        $card2->setFooter('teste');


        // //criando uma col Bootstrap:
        $col = $row->addCol($card);// add conteudo a coluna
        $col->class = 'col-sm-6';

        $col2 = $row->addCol($card2);// add conteudo a coluna
        $col2->class = 'col-sm-6';

        parent::add($row);
    }
}
