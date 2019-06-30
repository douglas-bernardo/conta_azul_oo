<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Datagrid\DatagridAjax;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Livro\Widgets\Dialog\Modal;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;

use Livro\Traits\DeleteTrait;
use Livro\Traits\ReloadTraitTeste;
use Livro\Traits\ConfirmTrait;

class UsersList extends Page
{
    private $loaded;
    private $filter;
    private $connection;
    private $activeRecord;

    use DeleteTrait;
    use ConfirmTrait;
    use ReloadTraitTeste{
        onReload as onReloadTrait;
    }

    public function __construct()
    {
        parent::__construct();

        $this->connection = 'contaazul';

        //ação para um novo usuário
        $newUser = new Action(array(new UsersForm, 'onEdit'));
        $btn = new Element('a');
        $btn->class = "btn btn-primary mb-3";
        $btn->href = $newUser->serialize();
        $btn->add('Novo Usuário');
        parent::add($btn);

        //instancia o obj Datagrid
        $datagrid = new DatagridWrapper(new Datagrid);

        //instancia as colunas da Datagrid - Cabeçalho
        $id = new DatagridColumn('id', 'Id', 'center', 40);
        $email = new DatagridColumn('email', 'Email Usuário', 'center', 200);
        $grupo = new DatagridColumn('nome_grupo', 'Grupo Permissões', 'center', 200);

        //adiciona as colunas à Datagrid
        $datagrid->addColumn($id);
        $datagrid->addColumn($email);
        $datagrid->addColumn($grupo);

        //instancia duas ações da datagrid
        $action1 = new DatagridAction(array(new UsersForm, 'onEdit'));
        $action1->setLabel('Editar');
        $action1->setImage('ico_edit.png');
        $action1->setField('id');

        $actDelUser = new Action(array($this, 'Delete'));
        $linkDelUser = $actDelUser->serialize();
        $action2 = new DatagridAjax('confirm', $linkDelUser, 'Users');
        $action2->setLabel('Excluir');
        $action2->setImage('ico_delete.png');
        $action2->setField('id');

        $datagrid->addAction($action1);
        $datagrid->addAction($action2);

        //cria o modelo da Datagrid montando sua estrutura (cabeçalho)
        $datagrid->createModel();

        //adiciona a Datagrid a página
        parent::add($datagrid);

        //armazena o activeRecord como chave no array activeRecord 
        //e indexa ao mesmo, o datagrid criado
        $this->activeRecord['Users'] = $datagrid;

        // modal
        $modal = new Modal("Excluir Registro", "ModalConfirm");
        $modal->add('Tem certeza que deseja excluir o registro?');
        parent::add($modal);
    }

    public function onReload()
    {
        $this->onReloadTrait();
        $this->loaded = true;
    }

    public function converterParaMaiusculo($value)
    {
        return strtoupper($value);
    }

    function show()
    {
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }
}
