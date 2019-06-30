<?php
namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;

class DatagridAjax
{
    private $function;
    private $url;
    private $activeRecord;
    private $image;
    private $label;
    private $field;

    public function __construct($function, $url, $activeRecord)
    {
        $this->function = $function;
        $this->url = $url;
        $this->activeRecord = $activeRecord;
    }

    public function getFunction()
    {
        return $this->function;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getActiveRecord()
    {
        return $this->activeRecord;
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
