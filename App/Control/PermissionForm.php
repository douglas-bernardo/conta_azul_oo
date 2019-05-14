<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Hidden;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Database\Transaction;

class PermissionForm extends Page
{
    private $form;
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new FormWrapper(new Form('permissions_form'));
        $this->form->setFormTitle('Cadastro de Permissões do Sistema');

        $id = new Hidden('id');
        $name = new Entry('name');

        $this->form->addField('Id', $id, '10%');
        $this->form->addField('Nome da Permissão', $name);

        $id->setEditable(FALSE);

        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        $this->form->addAction('Limpar', new Action(array($this, 'onClear')));

        parent::add($this->form);

    }

    public function onSave()
    {
        
    }

    public function onClear(Type $var = null)
    {
        
    }

    public function onEdit()
    {
        
    }
}
