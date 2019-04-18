<?php

namespace Livro\Widgets\Form;

class CheckButton extends Field implements FormElementInterface {
    public function show(){
        //atribui as propriedades da tag
        $this->tag->name = $this->name; //nome da tag
        $this->tag->value = $this->value; //value da tag
        $this->tag->type = 'checkbox'; //tipo de input
        $this->tag->style = "width:{$this->size}px";//tamanho em pixels

        //se o campo não é editável
        if (!parent::getEditable()){
            $this->tag->readonly = "1";
        }
        $this->tag->show();
    }
}
