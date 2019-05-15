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

use Livro\Traits\SaveTrait;
use Livro\Traits\EditTrait;

class PermissionForm extends Page
{
    private $form;
    private $connection;
    private $activeRecord;
    private $url_save_return;

    use SaveTrait;
    use EditTrait;

    public function __construct()
    {
        parent::__construct();

        $this->connection = 'contaazul';
        $this->activeRecord = 'Permissions';
        $this->url_save_return = 'index.php?class=PermissionsList&method=confirm&type=salvo';
        
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
        try {            
            Transaction::open('contaazul');
            //obtem os dados
            $dados = $this->form->getData();
            //validação
            if(empty($dados->name)){
                throw new Exception("Nome vazio");
            }
            $permission = new Permissions;
            $permission->fromArray( (array) $dados);
            $permission->id_company = 1;
            $permission->store();
            Transaction::close();            
            header("Location: index.php?class=PermissionsList&method=confirm&type=salvo");
        } catch (Exception $e) {
            new Message('warning', "<b>Erro:</b> " . $e->getMessage());
        }
    }

    public function onEdit($param)
    {
        try {
            if(isset($param['id'])){
                $permission_id = $param['id'];
                Transaction::open('contaazul');                
                $permission = Permissions::find($permission_id);
                $this->form->setData($permission);
                Transaction::close();
            }
        } catch (Exception $e) {
            new Message('warning', "<b>Error: </b>" . $e->getMessage());
        }
    }

    public function onClear()
    {
        
    }
}
