<?php
namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

class Tab extends Element
{
    protected $item;
    public function __construct() 
    {
        parent::__construct('ul');
        $this->class = 'nav nav-tabs mt-3';
        $this->role = 'tablist';
    }

    public function __get($name)
    {
        return isset($this->item[$name])? $this->item[$name] : NULL;
    }

    public function addTabItem($label, $id, $activate = false)
    {
        $this->item[$label] = $id;
        $li = new Element('li');
        $li->class = 'nav-item';
        $a = new Element('a');
        $a->class = ($activate)? "nav-link active": "nav-link";
        $a->id = "{$id}-tab";
        $a->{'data-toggle'} = "tab";
        $a->href = "#{$id}";
        $a->role = "tab";
        $a->{'aria-controls'} = "{$id}";
        $a->{'aria-selected'} = "{$activate}";
        $a->add($label);
        $li->add($a);
        parent::add($li);
    }
}
