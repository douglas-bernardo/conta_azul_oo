<?php

namespace Livro\Widgets\Form;

class File extends Field implements FormElementInterface {
    public function show(){
        $this->tag->name = $this->name; //nome da tag
        $this->tag->value = $this->value; //value da tag
        $this->tag->type = 'file'; //tipo de input
        if (!parent::getEditable()){
            $this->tag->readonly = "1";
        }
        $this->tag->show();
    }
}
