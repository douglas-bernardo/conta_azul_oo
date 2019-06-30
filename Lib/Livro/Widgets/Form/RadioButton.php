<?php

namespace Livro\Widgets\Form;

class RadioButton extends Field implements FormElementInterface {
    public function show(){
        //atribui as propriedades da tag
        $this->tag->name = $this->name; //nome da tag
        $this->tag->value = $this->value; //value da tag
        $this->tag->type = 'radio'; //tipo de input

        //se o campo não é editável
        if (!parent::getEditable()){
            $this->tag->readonly = "1";
        }
        $this->tag->show();
    }
}
