<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Container\Row;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Hidden;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Dialog\Modal;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Database\Transaction;

use Livro\Traits\SaveTrait;
use Livro\Traits\EditTrait;

class UsersForm extends Page
{
    private $form;
    private $connection;
    private $activeRecord;
    private $url_save_return;

    use SaveTrait;
    use EditTrait;

    public function __construct() {
        parent::__construct();

        $this->connection   = 'contaazul';
        $this->activeRecord = 'Users';
        $this->url_save_return = 'index.php?class=UsersList&method=confirm&type=salvo&activeRecord=UsersList';
        
        //instancia de um formulário
        $this->form = new FormWrapper(new Form('form_users'));
        $this->form->setFormTitle('Cadastro de Usuários');

        //cria os campos do formulário
        $id               = new Hidden('id');
        $user_email       = new Entry('email');
        $user_email->id   = 'email';
        $user_pass        = new Entry('password');
        $user_pass->id    = 'password';
        $permission_group = new Combo('id_group');
        $company          = new Combo('id_company');

        //carrega as permissões do banco de dados
        Transaction::open('contaazul');
        $groups = PermissionGroup::all();
        $items = array();
        foreach ($groups as $obj_group) {
            $items[$obj_group->id] = $obj_group->name;
        }
        $permission_group->addItems($items);

        //carrega a compania
        $companies = Company::all();
        $items = array();
        foreach ($companies as $obj_company) {
            $items[$obj_company->id] = $obj_company->name;
        }
        $company->addItems($items);
        Transaction::close();

        $this->form->addField('Id', $id, '10%');
        $this->form->addField('Email', $user_email);
        $this->form->addField('Senha', $user_pass );
        $this->form->addField('Grupo de Permissões', $permission_group);
        $this->form->addField('Compania', $company);

        $id->setEditable(FALSE);

        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        $this->form->addAction('Limpar', new Action(array($this, 'onClear')));

        parent::add($this->form);
        
    }

    public function onClear()
    {
        
    }
}
