<?php
namespace Livro\Widgets\Dialog;

use Livro\Widgets\Base\Element;

class Message 
{
    public function __construct($type, $message, $location = null, $width = '100', $cssClass = ''){
        $div = new Element('div');
        $div->class = "{$cssClass} alert alert-{$type} alert-dismissible fade show w-{$width}";

        $div->role = 'alert';
        $button = new Element('button');
        $button->type = 'button';
        $button->class = 'close';
        $button->data_dismiss = "alert";
        $button->area_label = "Close";
        $span = new Element('span');
        $span->id = 'message-button';
        if ($location){
            $span->data_url = $location;
        }
        $span->aria_hidden = "true";
        $span->add('&times;');
        $button->add($span);
        $div->add($button);
        $div->add($message);
        $div->show();
    }
}