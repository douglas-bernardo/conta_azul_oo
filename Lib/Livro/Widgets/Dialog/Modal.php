<?php
namespace Livro\Widgets\Dialog;

use Livro\Control\Action;
use Livro\Widgets\Base\Element;

class Modal extends Element
{
    private $dialog;
    private $content;
    private $header;
    private $body;
    private $footer;
    // Action $action_yes, Action $action_no = NULL
    public function __construct($title, $target_id, Action $action_yes = NULL, Action $action_no = NULL)
    {
        parent::__construct('div');

        // principal
        $this->class = "modal fade";
        $this->id = $target_id;
        $this->tabindex = '-1';
        $this->role = 'dialog';
        $this->{'aria-labelledby'} = 'exampleModalLabel';
        $this->{'aria-hidden'} = "true";

        //dialog
        $this->dialog = new Element('div');
        $this->dialog->class = 'modal-dialog';
        $this->dialog->role = 'document';

        //content
        $this->content = new Element('div');
        $this->content->class = 'modal-content';

        //header
        $this->header = new Element('div');
        $this->header->class = 'modal-header';

        //title
        $label = new Element('h5');
        $label->class = 'modal-title';
        $label->id = 'exampleModalLabel';
        $label->add($title);

        //MODAL close button 
        $button = new Element('button');
        $button->type = 'button';
        $button->class = 'close';
        $button->{'data-dismiss'} = 'modal';
        $button->{'aria-label'} = 'Close';
        $span = new Element('span');
        $span->{'aria-hidden'} = 'true';
        $span->add('&times;');
        $button->add($span);

        // add title and button on header
        $this->header->add($label);
        $this->header->add($button);

        //body
        $this->body = new Element('div');
        $this->body->class = 'modal-body';

        //footer
        $this->footer = new Element('div');
        $this->footer->class = 'modal-footer';

        //buttons
        if ($action_yes) {
            $url_yes = $action_yes->serialize();
            $link_yes = new Element('button');
            $link_yes->{'data-url'} = "'index.php'" . $url_yes;
            $link_yes->type = 'button';
            $link_yes->id = "btn_yes";
            $link_yes->class = 'btn btn-primary';
            $link_yes->{'data-dismiss'} = "modal";
            $link_yes->add('Sim');
        }

        //melhorar aqui
        $link_no = new Element('button');
        $link_no->type = 'button';
        $link_no->class = 'btn btn-secondary';
        $link_no->{'data-dismiss'} = "modal";
        $link_no->add('Não');

        // add buttons on footer
        $this->footer->add($link_no);

        //monta o conteudo
        $this->content->add($this->header);
        $this->content->add($this->body);
        $this->content->add($this->footer);

        //adiciona o conteudo ao dialog
        $this->dialog->add($this->content);

        //adiciona o conteudo ao principal
        parent::add($this->dialog);

    }
    
    public function add($message)
    {
        $this->body->add($message);
    }
}