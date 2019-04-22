<?php
use Livro\Control\Page;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;

class UsersList extends Page
{
    private $datagrid;

    public function __construct()
    {
        parent::__construct();

        $btn = new Element('a');
        $btn->class = "btn btn-primary mb-3";
        $btn->href = 'index.php?class=UsersForm';
        $btn->add("Novo");
        parent::add($btn);

        //instancia o obj Datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid);

        //instancia as colunas da Datagrid - Cabeçalho
        $id = new DatagridColumn('id', 'Id', 'center', 100);
        $email = new DatagridColumn('email', 'Email Usuário', 'center', 200);
        $grupo = new DatagridColumn('id_group', 'Grupo Permissões', 'center', 200);

        //adiciona as colunas à Datagrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($email);
        $this->datagrid->addColumn($grupo);

        //instancia duas ações da datagrid
        $action1 = new DatagridAction(array($this, 'onEdit'));
        $action1->setLabel('Editar');
        $action1->setImage('ico_edit.png');
        $action1->setField('id');

        $action2 = new DatagridAction(array($this, 'onDelete'));
        $action2->setLabel('Excluir');
        $action2->setImage('ico_delete.png');
        $action2->setField('id');

        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);

        //cria o modelo da Datagrid montando sua estrutura (cabeçalho)
        $this->datagrid->createModel();

        //adiciona a Datagrid a página
        parent::add($this->datagrid);
    }

    public function onReload()
    {
        Transaction::open('contaazul');
        $repository = new Repository('Users');

        //cria um critério de seleção de dados
        $criteria = new Criteria;
        $criteria->setProperty('order', 'id');

        //carrega os produtos que satisfazem o critério
        $users = $repository->load($criteria);
        $this->datagrid->clear();
        if($users){
            foreach ($users as $user) {
                //adiciona cada objeto a datagrid
                $this->datagrid->addItem($user);
            }
        }
        Transaction::close();
        $this->loaded = true;
    }

    public function converterParaMaiusculo($value)
    {
        return strtoupper($value);
    }

    public function onEdit($param)
    {
        new Message('info', 'Você clicou sobre o registro: ' . $param['email']);
    }

    public function onDelete($param)
    {
        new Message('info', 'Você clicou sobre o registro: ' . $param['email']);
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
