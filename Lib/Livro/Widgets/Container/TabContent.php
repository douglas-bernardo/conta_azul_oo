<?php
namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

class TabContent extends Element
{
    private $tabItem;
    public function __construct(Tab $tabItem)
    {
        parent::__construct('div');
        $this->class = "tab-content mt-3";
        $this->tabItem = $tabItem;
    }

    public function addContent($labelItem, $content, $activate = False)
    {
        $div = new Element('div');
        $div->class = ($activate)? 'tab-pane fade show active': 'tab-pane fade';
        $div->id = "{$this->tabItem->$labelItem}";
        $div->role = 'tabpanel';
        $div->{'aria-labelledby'} = "{$this->tabItem->$labelItem}-tab";
        $div->add($content);
        parent::add($div);
    }
}
