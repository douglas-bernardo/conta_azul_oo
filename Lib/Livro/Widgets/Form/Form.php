<?php

namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Container\Row;
use Livro\Control\ActionInterface;
use Livro\Widgets\Container\HBox;
use Livro\Widgets\Container\Card;

class Form extends Element
{
    protected $fields;
    protected $actions;
    protected $title;
    private $has_action;
    private $actions_container;

    public function __construct($name = 'my_form')
    {
        parent::__construct('form');
        $this->enctype = "multipart/form-data";
        $this->method = 'post';
        $this->setName($name);
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setFormTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function addField($label, FormElementInterface $object, $size = '100%')
    {
        $object->setSize($size);
        if ($object instanceof Hidden){
            $object->setLabel(NULL);    
        }else{
            $object->setLabel($label);
        }
        $this->fields[$object->getName()] = $object;
    }

    public function addAction($label, ActionInterface $action)
    {
        $this->actions[$label] = $action;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function setData($object)
    {
        foreach ($this->fields as $name => $field){
            if ($name AND isset($object->$name)){
                if ($name != 'password'){ 
                $field->setValue($object->$name);
                }
            }
        }
    }

    public function getData($class = 'stdClass')
    {
        $object = new $class;

        foreach($this->fields as $key => $fieldObject){
            $val = isset($_POST[$key])? $_POST[$key] : '';
            if(!$fieldObject instanceof Button){
                $object->$key = $val;
            }
        }
        foreach ($_FILES as $key => $content) {
            $object->$key = $content['tmp_name'];
        }
        return $object;
    }
}