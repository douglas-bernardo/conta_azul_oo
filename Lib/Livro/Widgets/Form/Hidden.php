<?php

namespace Livro\Widgets\Form;

class Hidden extends Field implements FormElementInterface {
    public function show(){
        $this->tag->name = $this->name; //nome da tag
        $this->tag->value = $this->value; //value da tag
        $this->tag->type = 'hidden'; //tipo de input
        $this->tag->style = "width:{$this->size}";//tamanho em pixels
        $this->tag->show();
    }
}
