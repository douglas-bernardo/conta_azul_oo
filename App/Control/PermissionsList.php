<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Container\Tab;
use Livro\Widgets\Container\TabContent;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Datagrid\DatagridAjax;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Modal;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;

use Livro\Traits\DeleteTrait;
use Livro\Traits\ReloadTraitTeste;
use Livro\Traits\ConfirmTrait;

class PermissionsList extends Page
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

    public function __construct() {
        parent::__construct();

        //define as propriedades usadas nos traits
        $this->connection   = 'contaazul';

        //datagrid de permissões
        $datagrid_permissions = new DatagridWrapper(new Datagrid);
        $nome = new DatagridColumn('name', 'Nome', 'left', 100);

        //adiciona as colunas à Datagrid
        $datagrid_permissions->addColumn($nome);

        //ações das permissões:
        $datagrid_permission_act1 = new DatagridAction(array(new PermissionForm, 'onEdit'));
        $datagrid_permission_act1->setLabel('Editar');
        $datagrid_permission_act1->setImage('ico_edit.png');
        $datagrid_permission_act1->setField('id');

        $actDelPermission = new Action(array($this, 'Delete'));
        $linkDelPermission = 'index.php' . $actDelPermission->serialize();
        $datagrid_permission_act2 = new DatagridAjax('confirm', $linkDelPermission, 'Permissions');
        $datagrid_permission_act2->setLabel('Excluir');
        $datagrid_permission_act2->setImage('ico_delete.png');
        $datagrid_permission_act2->setField('id');

        $datagrid_permissions->addAction($datagrid_permission_act1);
        $datagrid_permissions->addAction($datagrid_permission_act2);

        //cria o modelo da Datagrid1 montando sua estrutura (cabeçalho)
        $datagrid_permissions->createModel();

        //datagrid de grupos de permissões
        $datagrid_permissions_group = new DatagridWrapper(new Datagrid);
        $nome_grupo = new DatagridColumn('name', 'Nome', 'left', 100);

        //adiciona as colunas
        $datagrid_permissions_group->addColumn($nome_grupo);
        
        //ações dos grupos
        $datagrid_permissions_group_act1 = new DatagridAction(array(new PermissionGroupsForm, 'onEdit'));
        $datagrid_permissions_group_act1->setLabel('Editar');
        $datagrid_permissions_group_act1->setImage('ico_edit.png');
        $datagrid_permissions_group_act1->setField('id');

        $actDelPermissionGroup = new Action(array($this, 'Delete'));
        $linkDelPermissionGroup = 'index.php' . $actDelPermissionGroup->serialize();
        $datagrid_permissions_group_act2 = new DatagridAjax('confirm', $linkDelPermissionGroup, 'PermissionGroup');
        $datagrid_permissions_group_act2->setLabel('Excluir');
        $datagrid_permissions_group_act2->setImage('ico_delete.png');
        $datagrid_permissions_group_act2->setField('id');

        $datagrid_permissions_group->addAction($datagrid_permissions_group_act1);
        $datagrid_permissions_group->addAction($datagrid_permissions_group_act2);

        //cria o modelo da Datagrid2 montando sua estrutura (cabeçalho)
        $datagrid_permissions_group->createModel();

        //cria um objeto tab
        $tab = new Tab;

        //adiciona a 1ª tab (Permissões) como 'ativa' por padrão
        $tab->addTabItem('Permissões', 'permissions', True);
        //adiciona a 2ª tab (Grupos de permissões)
        $tab->addTabItem('Grupos de Permissões', 'permissions_group');

        //define os objetos de conteúdo
        $tabContent = new TabContent($tab);

        //ação para uma nova permissão
        $newPerm = new Action(array(new PermissionForm, 'onEdit'));
        $btn = new Element('a');
        $btn->class = "btn btn-primary mt-3 mb-4";
        $btn->href = $newPerm->serialize();
        $btn->add("Nova Permissão");

        //container do conteudo da tab1 (Permissões)
        $container1 = new Element('div');
        $container1->class = 'container';
        $container1->add($btn);
        $container1->add($datagrid_permissions);

        //ação para um novo grupo de permissões
        $newPermGroup = new Action(array(new PermissionGroupsForm, 'onEdit'));
        $btn = new Element('a');
        $btn->class = "btn btn-primary mt-3 mb-4";
        $btn->href = $newPermGroup->serialize();
        $btn->add("Novo Grupo");

        //container do conteudo da tab2 (Grupos de Permissões)
        $container2 = new Element('div');
        $container2->class = 'container';
        $container2->add($btn);
        $container2->add($datagrid_permissions_group);

        //adiciona os conteudos de acordo com cada tab sendo o 1º como ativo por padrão
        $tabContent->addContent('Permissões', $container1, True);
        $tabContent->addContent('Grupos de Permissões', $container2);

        //armazena o datagrid no array de objetos
        $this->activeRecord['Permissions'] = $datagrid_permissions;
        $this->activeRecord['PermissionGroup'] = $datagrid_permissions_group;    
        
        //adiciona as tabs e seus respectivos conteúdos a página principal:
        parent::add($tab);
        parent::add($tabContent);

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

    function show()
    {
        //se a listagem ainda não foi carregada
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }
}
