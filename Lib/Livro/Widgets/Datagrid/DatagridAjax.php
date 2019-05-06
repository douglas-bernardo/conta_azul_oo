<?php
namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;

class DatagridAjax
{
    private $function;
    private $image;
    private $label;
    private $field;

    public function __construct($function) 
    {
        $this->function = $function;
    }

    public function getFunction()
    {
        return $this->function;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }
    public function getImage()
    {
        return $this->image;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }
    public function getLabel()
    {
        return $this->label;
    }
    
    public function setField($field)
    {
        $this->field = $field;
    }
    public function getField()
    {
        return $this->field;
    }
}
