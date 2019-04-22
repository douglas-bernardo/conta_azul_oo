<?php

namespace Livro\Widgets\Form;

use Livro\Control\Action;
use Livro\Control\ActionInterface;

class Button extends Field implements FormElementInterface
{
    private $action;
    private $label;
    private $formName;
    //private $class;

    public function setAction(ActionInterface $action, $label)
    {
        $this->action = $action;
        $this->label = $label;
    }

    public function setFormName($name)
    {
       $this->formName = $name;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function show()
    {
        $url = $this->action->serialize();
        //define as propriedades do botão
        $this->tag->name = $this->name; //nome da tag
        $this->tag->type = 'button'; //tipo de input
        //$this->tag->class = $this->class;    
        $this->tag->value = $this->label; //rótulo do botão

        //define a ação do botão
        $this->tag->onclick = "document.{$this->formName}.action='{$url}';".
                              "document.{$this->formName}.submit()";
        //exibe o botão
        $this->tag->show();
    }
}

