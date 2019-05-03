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

class UsersForm extends Page
{
    private $form;

    public function __construct() {
        parent::__construct();

        //instancia de um formulário
        $this->form = new FormWrapper(new Form('form_users'));
        $this->form->setFormTitle('Cadastro de Usuários');

        //cria os campos do formulário
        $id             = new Hidden('id');
        $user_email     = new Entry('email');
        $user_email->id = 'email';
        $user_pass      = new Entry('password');
        $user_pass->id  = 'password';
        $permission     = new Combo('id_group');

        $this->form->addField('Id', $id, '10%');
        $this->form->addField('Email', $user_email);
        $this->form->addField('Senha', $user_pass );
        $this->form->addField('Grupo de Permissões', $permission);

        $id->setEditable(FALSE);

        $permission->addItems(array('1'=>'Desenvolvedores', 
                                    '2'=>'Novo Grupo Teste'));

        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        $this->form->addAction('Limpar', new Action(array($this, 'onClear')));

        parent::add($this->form);
    }

    public function onSave()
    {
        try {            
            Transaction::open('contaazul');
            //obtem os dados
            $dados = $this->form->getData();
            //validação
            if(empty($dados->email)){
                throw new Exception("Email vazio");
            }
            $user = new Users;//classe Users carregada no index pelo autoload
            $user->fromArray( (array) $dados);
            $user->store();
            Transaction::close();            
            header("Location: index.php?class=UsersList&method=confirm");

        } catch (Exception $e) {
            new Message('error', "<b>Erro:</b> " . $e->getMessage());
        }
    }

    public function onEdit($param)
    {
        try {
            Transaction::open('contaazul');
            $id = $param['id'];
            $user = Users::find($id);
            if ($user) {
                $this->form->setData($user);
            }
            Transaction::close();

        } catch (Exception $e) {
            new Message('error', "<b>Erro:</b> " . $e->getMessage());
        }
    }

    public function onClear()
    {
    }
}
