<?php
use Livro\Control\Page;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Dialog\Message;

class UsersList extends Page
{
    private $datagrid;

    public function __construct() {
        parent::__construct();

        $btn = new Element('button');
        $btn->type = "button";
        $btn->class = "btn btn-primary mb-3";
        $btn->add("Novo");
        parent::add($btn);

        //instancia o obj Datagrid
        $this->datagrid = new Datagrid;
        $this->datagrid->border = 1;

        //instancia as colunas da Datagrid - Cabeçalho
        $email = new DatagridColumn('email', 'Email Usuário', 'center', 200);
        $grupo = new DatagridColumn('grupo', 'Grupo Permissões', 'center', 200);
        $situacao = new DatagridColumn('situacao', 'Situação', 'center', 200);

        //adiciona as colunas à Datagrid
        $this->datagrid->addColumn($email);
        $this->datagrid->addColumn($grupo);
        $this->datagrid->addColumn($situacao);

        $situacao->setTransformer(array($this, 'converterParaMaiusculo'));

        //instancia duas ações da Datagrid
        $action1 =  new DatagridAction(array($this, 'onVisualiza'));
        $action1->setLabel('Visualizar');
        $action1->setImage('ico_view.png');
        $action1->setField('email');

        $this->datagrid->addAction($action1);

        //cria o modelo da Datagrid montando sua estrutura (cabeçalho)
        $this->datagrid->createModel();

        //adiciona a Datagrid a página
        parent::add($this->datagrid);
    }

    public function onReload()
    {
        $this->datagrid->clear();

        $u1 = new stdClass;
        $u1->email = 'jkdouglas21@gmail.com';
        $u1->grupo = 'Developers';
        $u1->situacao = 'Ativo';
        $this->datagrid->addItem($u1);

        $u2 = new stdClass;
        $u2->email = 'jkdouglas21@gmail.com';
        $u2->grupo = 'Developers';
        $u2->situacao = 'Ativo';
        $this->datagrid->addItem($u2);

        $u3 = new stdClass;
        $u3->email = 'jkdouglas21@gmail.com';
        $u3->grupo = 'Developers';
        $u3->situacao = 'Ativo';
        $this->datagrid->addItem($u3);

        $u4 = new stdClass;
        $u4->email = 'jkdouglas21@gmail.com';
        $u4->grupo = 'Developers';
        $u4->situacao = 'Ativo';
        $this->datagrid->addItem($u4);

    }

    public function converterParaMaiusculo($value)
    {
        return strtoupper($value);
    }

    public function onVisualiza($param)
    {
        new Message('info', 'Você cliclou sobre o registro: ' . $param['email']);
    }

    public function show()
    {
        $this->onReload();
        parent::show();
    }

}
