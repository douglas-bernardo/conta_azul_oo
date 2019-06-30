<?php

namespace Livro\Widgets\Form;

class Password extends Field implements FormElementInterface {
    public function show(){
        $this->tag->name = $this->name; //nome da tag
        $this->tag->value = $this->value; //value da tag
        $this->tag->type = 'password'; //tipo de input
        $this->tag->style = "width:{$this->size}";//tamanho em pixels
        if (!parent::getEditable()){
            $this->tag->readonly = "1";
        }
        $this->tag->show();
    }
}
