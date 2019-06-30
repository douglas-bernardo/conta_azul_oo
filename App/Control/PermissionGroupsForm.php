<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Hidden;
use Livro\Widgets\Form\CheckButton;
use Livro\Widgets\Form\CheckGroup;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Database\Transaction;

use Livro\Traits\SaveTrait;
use Livro\Traits\EditTrait;

class PermissionGroupsForm extends Page
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
        $this->activeRecord = 'PermissionsGroup';
        $this->url_save_return = 'index.php?class=PermissionsList&method=confirm&type=salvo';
        
        $this->form = new FormWrapper(new Form('permissions_form_group'));
        $this->form->setFormTitle('Cadastro de Grupos de Permissões');

        $id    = new Hidden('id');
        $name  = new Entry('name');
        $check_group_permissions = new CheckGroup('ids_permissions');

        //carrega as permissões do banco de dados:
        Transaction::open($this->connection);
        $permissions = Permissions::all();
        $items = array();
        foreach ($permissions as $permissions_obj) {
            $items[$permissions_obj->id] = $permissions_obj->name;
        }
        $check_group_permissions->addItems($items);
        Transaction::close();

        $this->form->addField('Id', $id, '10%');
        $this->form->addField('Nome:', $name);
        $this->form->addField('Permissões:', $check_group_permissions);

        $id->setEditable(FALSE);

        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        $this->form->addAction('Limpar', new Action(array($this, 'onClear')));

        parent::add($this->form);

    }

    public function onSave()
    {
        try {            
            Transaction::open('contaazul');
            $dados = $this->form->getData();
            if(empty($dados->name)){
                throw new Exception("Nome vazio");
            }
            $dados->params = implode(',', $dados->ids_permissions);
            $permission_group = new PermissionGroup;
            $permission_group->fromArray( (array) $dados);
            $permission_group->id_company = 1;
            $permission_group->store();
            Transaction::close();            
            header("Location: index.php?class=PermissionsList&method=confirm&type=salvo");
        } catch (Exception $e) {
            new Message('warning', "<b>Erro:</b> " . $e->getMessage());
        }
    }

    public function onEdit($param)
    {
        try {
            if(isset($param['key'])){
                $id = $param['id'];
                Transaction::open('contaazul');                
                $permission_group = PermissionGroup::find($id);
                $permission_group->ids_permissions = explode(',', $permission_group->params);
                $this->form->setData($permission_group);
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
