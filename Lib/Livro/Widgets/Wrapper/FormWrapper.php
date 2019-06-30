<?php
namespace Livro\Widgets\Wrapper;

use Livro\Widgets\Form\Form;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Form\Button;
use Livro\Widgets\Container\Card;

class FormWrapper
{
    private $decorated;
    private $layout;
    private $btnCssClass;

    public function __construct(Form $form, $layout = null, $btnCssClass = '')
    {
        $this->decorated = $form;
        $this->layout = $layout;
        $this->btnCssClass = $btnCssClass;
    }

    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->decorated, $method), $parameters);
    }

    public function show()
    {
        $element = new Element('form');
        //$element->class = "form-horizontal";
        $element->enctype = "multipart/form-data";
        $element->method = 'post';
        $element->name = $this->decorated->getName();

        foreach ($this->decorated->getFields() as $field){
            
            $group = new Element('div');
            $group->class = ($this->layout == 'row') ? 'form-group row' : 'form-group' ;//*
            
            if(NUll != $field->getLabel()){
                $label = new Element('label');
                $label->for = $field->id;
                $label->class = ($this->layout == 'row') ? 'col-sm-2 col-form-label' : '' ;//label ocupando duas colunas*
                $label->add($field->getLabel());
                $group->add($label);
            }            

            $col = new Element('div');
            $col->class = ($this->layout == 'row') ? 'col-sm-10' : '' ;//field ocupando dez colunas*
            $col->add($field);

            $field->class = 'form-control';

            $group->add($col);
            $element->add($group);
        }

        //actions container
        $group = new Element('div');
        $group->class = 'form-group row mb-0';

        $col = new Element('div');
        //$col->class = 'col-sm-10';
        $col->class = 'col';

            $i = 0;
            foreach ($this->decorated->getActions() as $label => $action)
            {
                $name = \strtolower(str_replace(' ', '_', $label));
                $button = new Button($name);
                $button->setFormName($this->decorated->getName());
                $button->setAction($action, $label);
                $button->class = "{$this->btnCssClass} btn " . ( ($i==0) ? 'btn-primary' : 'btn-secondary');
                $col->add($button);
                $i++;
            }

        $group->add($col);
        $card = new Card;
        $card->setHeader($this->decorated->getTitle());
        $card->setBody($element);
        $card->setFooter($group);
        $card->show();
    }
}