<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Container\Tab;
use Livro\Widgets\Container\TabContent;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;

class PermissionsList extends Page
{
    private $datagrid_permissions;
    private $datagrid_permissions_group;
    private $loaded;

    public function __construct() {
        parent::__construct();

        //cria um objeto tab
        $tab = new Tab;

        //adiciona a 1ª tab como 'ativa' por padrão
        $tab->addTabItem('Permissões', 'permissions', True);

        //adiciona as tabs secundárias inativas:
        $tab->addTabItem('Grupos de Permissões', 'permissions_group');

        //define os objetos de conteúdo
        $tabContent = new TabContent($tab);

        //datagrid de permissões
        $this->datagrid_permissions = new DatagridWrapper(new Datagrid);
        $nome = new DatagridColumn('name', 'Nome', 'left', 100);

        //adiciona as colunas à Datagrid
        $this->datagrid_permissions->addColumn($nome);

        //ações das permissões:
        $datagrid_permission_act1 = new DatagridAction(array($this, 'onEdit'));
        $datagrid_permission_act1->setLabel('Editar');
        $datagrid_permission_act1->setImage('ico_edit.png');
        $datagrid_permission_act1->setField('id');

        $datagrid_permission_act2 = new DatagridAction(array($this, 'onDelete'));
        $datagrid_permission_act2->setLabel('Excluir');
        $datagrid_permission_act2->setImage('ico_delete.png');
        $datagrid_permission_act2->setField('id');

        $this->datagrid_permissions->addAction($datagrid_permission_act1);
        $this->datagrid_permissions->addAction($datagrid_permission_act2);

        //cria o modelo da Datagrid montando sua estrutura (cabeçalho)
        $this->datagrid_permissions->createModel();

        //ação nova permissão
        $newPerm = new Action(array(new PermissionForm, 'onEdit'));
        $btn = new Element('a');
        $btn->class = "btn btn-primary mt-3 mb-4";
        $btn->href = $newPerm->serialize();
        $btn->add("Nova Permissão");

        //container do conteudo da tab
        $container = new Element('div');
        $container->class = 'container';
        $container->add($btn);
        $container->add($this->datagrid_permissions);

        //adiciona os conteudos de acordo com cada tab sendo o 1º como ativo por padrão
        $tabContent->addContent('Permissões', $container, True);
        $tabContent->addContent('Grupos de Permissões', 'Página Grupos de Permissões');
        
        //adiciona as tabs e seus respectivos conteúdos a página principal:
        parent::add($tab);
        parent::add($tabContent);

    }

    public function onReload()
    {
        try {
            Transaction::open('contaazul');
            $repository = new Repository('Permissions');

            //cria um critério de selecção
            $criteria = new Criteria;
            $criteria->setProperty('order', 'id');

            $permissions = $repository->load($criteria);
            $this->datagrid_permissions->clear();

            if($permissions){
                foreach ($permissions as $permission) {
                    $this->datagrid_permissions->addItem($permission);
                }
            }
            Transaction::close();
            $this->loaded = true;
        } catch (\Exception $e) {
            new Message('warning', $e->getMessage());
        }
    }

    function onEdit()
    {

    }

    function onDelete()
    {

    }

    function show()
    {
        //se a listagem ainda não foi carregada
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }
}
