<?php
use Livro\Control\Page;
use Livro\Widgets\Base\Element;
use Livro\Control\Action;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Widgets\Wrapper\DatagridWrapper;

class ClientesList extends Page
{
    private $datagrid;
    private $loaded;

    public function __construct() {
        parent::__construct();

        //cria botão de novo registro
        $btn = new Element('a');
        $btn->class = "btn btn-primary mb-3";
        $btn->href = 'index.php?class=ClientesForm';
        $btn->add("Novo Cliente");
        parent::add($btn);

        //instancia o objeto data grid
        $this->datagrid = new DatagridWrapper(new Datagrid);
        $this->datagrid->style = 'max-width: 750px';

        //instancia as colunas da data grid
        $nome     = new DatagridColumn('name', 'Nome', 'center', 300);
        $telefone = new DatagridColumn('phone', 'Telefone', 'center', 125);
        $cidade   = new DatagridColumn('address_city', 'Cidade', 'center', 125);
        $estrelas = new DatagridColumn('stars', 'Estrelas', 'center', 100);

        //adiciona as colunas na datagrid
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($telefone);
        $this->datagrid->addColumn($cidade);
        $this->datagrid->addColumn($estrelas);

        $nome->setTransformer(array($this, 'encode'));

        //instancia duas ações da datagrid
        $action1 = new DatagridAction(array($this, 'onEdit'));
        $action1->setLabel('Editar');
        $action1->setImage('ico_edit.png');
        $action1->setField('id');

        $action2 = new DatagridAction(array($this, 'onDelete'));
        $action2->setLabel('Excluir');
        $action2->setImage('ico_delete.png');
        $action2->setField('id');

        //adiciona as ações a datagrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);

        //cria o modelo da datagrid montando sua estrutura
        $this->datagrid->createModel();

        //adiciona a pagina
        parent::add($this->datagrid);
    }

    public function onReload()
    {
        Transaction::open('contaazul');
        $repository = new Repository('Cliente');

        //cria um critério de seleção de dados
        $criteria = new Criteria;
        $criteria->setProperty('order', 'id');

        //carrega os produtos que satisfazem o critério
        $clientes = $repository->load($criteria);
        $this->datagrid->clear();
        if($clientes){
            foreach ($clientes as $cliente) {
                //adiciona cada objeto a datagrid
                $this->datagrid->addItem($cliente);
            }
        }
        Transaction::close();
        $this->loaded = true;
    }

    public function onEdit()
    {
        # code...
    }

    public function onDelete($param)
    {
        $id = $param['id'];
        $action1 = new Action(array($this, 'Delete'));
        $action1->setParameter('id', $id);

        $action2 = new Action(array($this, 'onReload'));

        new Question('Deseja relamente excluir o registro?', $action1, $action2);

    }

    function Delete($param)
    {
        try {
            $id = $param['id'];
            Transaction::open('contaazul');
            $cliente = Cliente::find($id);
            if($cliente){
                $cliente->delete();
            }
            Transaction::close();
            $this->onReload();
            new Message('info', "Registro excluído com sucesso!");
            header("location: index.php?class=ClientesList");
        } catch (\Exeption $e) {
            new Message('error', $e->getMessage());
        }
    }

    function encode($value)
    {
        return utf8_encode($value);
    }

    function show(Type $var = null)
    {
        //se a listagem ainda não foi carregada
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }
}