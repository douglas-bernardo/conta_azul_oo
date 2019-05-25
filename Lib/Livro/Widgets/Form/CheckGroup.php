<?php

namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

class CheckGroup extends Field implements FormElementInterface {
    private $layout = 'vertical';
    private $items;

    public function setLayout($dir){
        $this->layout = $dir;
    }

    public function addItems($items){
        $this->items = $items;
    }

    public function show()
    {
        if ($this->items) {
            //percorre cada uma das opções do radio
            foreach ($this->items as $index => $label) {
                $button = new CheckButton("{$this->name}[]");
                $button->class = "custom-control-input";
                $button->id = "p_$index";
                $button->setValue($index);

                //verifica se deve ser marcado
                if (in_array($index, (array)$this->value)) {
                    $button->setProperty('checked','1');
                }
                
                $obj = new Label($label);
                $obj->for = "p_$index";
                $obj->class = "custom-control-label";

                $div = new Element('div');
                $div->class = "custom-control custom-checkbox";

                $div->add($button);
                $div->add($obj);
                $div->show();
            }
        }
    }
}
