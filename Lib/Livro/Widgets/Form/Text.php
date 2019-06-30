<?php

namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

class Text extends Field implements FormElementInterface {
    private $width;
    private $height;
    public function __construct($name){
        parent::__construct($name);
        $this->tag = new Element('textarea');
        $this->tag->class = 'field';    //classe CSS
        
        //define a altura padrão da caixa de texto
        $this->height = 100;
    }

    public function setSize($width, $height = NULL){
        $this->size = $width;
        if(isset($height)){
            $this->height = $height;
        }
    }


    public function show(){
        $this->tag->name = $this->name; //nome da tag
        $this->tag->style = "width:{$this->size};height:{$this->height}";//tamanho em pixels

        //se o campo não é editavel
        if(!parent::getEditable()){
            $this->tag->readonly = "1";
        }
        
        //adiciona conteúdo ao text área
        $this->tag->add(\htmlspecialchars($this->value));

        //exibe a tag
        $this->tag->show();
    }
}
