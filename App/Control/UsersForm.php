<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Container\Row;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Dialog\Modal;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Database\Transaction;

class UsersForm extends Page
{
    private $form;

    public function __construct() {
        parent::__construct();

        //instancia de um formulário
        $this->form = new FormWrapper(new Form('form_users'));
        $this->form->setFormTitle('Cadastro de Usuários');

        //cria os campos do formulário
        $user_email = new Entry('email');
        $user_email->id = 'email';
        $user_pass = new Entry('password');
        $user_pass->id = 'password';
        $permission = new Combo('id_group');


        $this->form->addField('Email', $user_email);
        $this->form->addField('Senha', $user_pass );
        $this->form->addField('Grupo de Permissões', $permission);

        $permission->addItems(array('1'=>'Desenvolvedores', 
                                    '2'=>'Novo Grupo Teste'));

        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        $this->form->addAction('Limpar', new Action(array($this, 'onClear')));

        parent::add($this->form);

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
        try {

            
            Transaction::open('contaazul');

            //obtem os dados
            //$dados = $this->form->getData();

            //validação
            // if(empty($dados->email)){
            //     throw new Exception("Email vazio");
            // }

            $user = new Users;//classe Users carregada no index pelo autoload
            $user->email = addslashes($_POST['email']);
            $user->password = md5($_POST['password']);
            $user->id_group = addslashes($_POST['id_group']);
            $user->store();

            Transaction::close();
            new Message('info', "Registro salvo com sucesso!");

        } catch (Exception $e) {
            new Message('error', "<b>Erro:</b> " . $e->getMessage());
        }
    }

    public function onClear()
    {
    }
}
