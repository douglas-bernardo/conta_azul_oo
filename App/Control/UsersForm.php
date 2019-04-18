<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Container\Row;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Dialog\Modal;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Base\Element;

class UsersForm extends Page
{
    private $form;
    public function __construct() {
        parent::__construct();

        //page title
        $row = new Row;
        $row->class .= ' pl-3 pt-3 mb-3';
        $label = new Element('h3');
        $label->add('Usuários');
        $col = $row->addCol($label);
        $col->class = 'col-3';
        parent::add($row);

        $this->form = new Form('form_users');

        $user_name = new Entry('nome', TRUE);
        $user_name->id = 'name';
        $user_email = new Entry('email');
        $user_email->id = 'email';
        $user_password = new Entry('password');
        $user_password->id = 'pass';

        $this->form->addField('Nome', $user_name);
        $this->form->addField('Email', $user_email);
        $this->form->addField('Senha', $user_password);

        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));

        $wrapper_form = new Element('div');
        $wrapper_form->class = "wrapper_form";
        $wrapper_form->add($this->form);
        parent::add($wrapper_form);


        // $btn = new Element('button');
        // $btn->type = "button";
        // $btn->class = "btn btn-danger";
        // $btn->data_toggle = "modal";
        // $btn->data_target = "#exampleModal";
        // $btn->add("Excluir");
        // parent::add($btn);
        // // modal
        // $action_yes = new Action(array($this, 'onDelete'));
        // $modal = new Modal("Excluir Registro", "exampleModal", $action_yes);
        // $modal->add('Tem certeza que deseja escluir o registro?');
        // parent::add($modal);

    }

    public function onSave()
    {
        new Message('info', "Registro salvo com sucesso!");
    }
}
