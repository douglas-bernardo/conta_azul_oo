<?php
namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

class Card extends Element
{
    private $header;
    private $body;
    private $footer;

    public function __construct($card_title = NULL)
    {        
        parent::__construct('div');
        $this->class = 'card mb-3';

        //cria o cabeçalho do card
        $this->header = new Element('h5');
        $this->header->class = 'card-header';

        //cria o corpo do card
        $this->body = new Element('div');
        $this->body->class = 'card-body';

        //se for informado um titulo no construtor esse titulo fica dentro do body do card
        if ($card_title)
        {
            $label = new Element('h5');
            $label->class = 'card-title';
            $label->add($card_title);
            $this->body->add($label);
        }

        //cria o footer do card
        $this->footer = new Element('div');
        $this->footer->class = 'card-footer';

    }

    public function setHeader($header)
    {   
        $this->header->add($header);
        parent::add($this->header);
    }

    public function setBody($content)
    {
        $this->body->add($content);
        parent::add($this->body);
    }

    public function setFooter($footer)
    {
        $this->footer->add($footer);
        parent::add($this->footer);
    }
}