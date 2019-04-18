<?php
namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

class Card extends Element
{
    private $body;
    public function __construct($card_header = NULL, $card_tittle = NULL){
        parent::__construct('div');
        $this->class = 'card mb-3';
        if($card_header){
            $label = new Element('h5');
            $label->class = 'card-header';
            $label->add($card_header);
            parent::add($label);
        }

        $this->body = new Element('div');
        $this->body->class = 'card-body';
        parent::add($this->body);

        if ($card_tittle) {
            $label = new Element('h5');
            $label->class = 'card-title';
            $label->add($card_tittle);
            $this->body->add($label);
        }

    }
    public function add($content){
        $this->body->add($content);
    }
}