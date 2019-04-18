<?php
namespace Livro\Widgets\Dialog;

use Livro\Widgets\Base\Element;

class Message 
{
    public function __construct($type, $message){
        $div = new Element('div');
        if($type == 'info'){
            $div->class = 'alert alert-info alert-dismissible fade show w-50';
        } else if ($type == 'error') {
            $div->class = 'alert alert-danger alert-dismissible fade show w-50';
        }
        $div->role = 'alert';
        $button = new Element('button');
        $button->type = 'button';
        $button->class = 'close';
        $button->data_dismiss = "alert";
        $button->area_label = "Close";
        $span = new Element('span');
        $span->aria_hidden = "true";
        $span->add('&times;');
        $button->add($span);
        $div->add($button);
        $div->add($message);
        $div->show();
    }
}