<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Database\Filter;
use Livro\Widgets\Container\Panel;

use Livro\Traits\DeleteTrait;
use Livro\Traits\ReloadTrait;

class ProdutosList extends Page
{
    private $form;      //formulario de buscas
    private $datagrid;  //listagem
    private $loaded;
    private $activeRecord;
    private $filter;

    use DeleteTrait;
    use ReloadTrait{
        onReload as onReloadTrait;
    }

    public function __construct() {
        parent::__construct();

        $this->connection = 'livro';
        $this->activeRecord = 'Produto';

        //instancia um formulário de buscas
        $this->form = new FormWrapper(new Form('form_busca_produtos'));

        //cria os campos do formulário
        $descricao = new Entry('descricao');
        $this->form->addField('Descrição', $descricao, 300);
        $this->form->addAction('Buscar', new Action(array($this, 'onReload')));
        $this->form->addAction('Cadastrar', new Action(array(new ProdutosForm, 'onEdit')));

        //Instancia objeto datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid);

        //instancia as colunas a Datagrid
        $codigo    = new DatagridColumn('id', 'Código', 'right', 50);
        $descricao = new DatagridColumn('descricao', 'Descrição', 'left', 200);
        $fabrica   = new DatagridColumn('nome_fabricante', 'Fabricante', 'left', 270);
        $estoque   = new DatagridColumn('estoque', 'Estoque', 'right', 40);
        $preco     = new DatagridColumn('preco_venda', 'Preço', 'right', 40);

        //adiciona as colunas a datagrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($descricao);
        $this->datagrid->addColumn($fabrica);
        $this->datagrid->addColumn($estoque);
        $this->datagrid->addColumn($preco);

        //instancia duas ações da datagrig
        $obj = new ProdutosForm;
        $action1 = new DatagridAction(array($obj, 'onEdit'));
        $action1->setLabel('Editar');
        $action1->setImage('ico_edit.png');
        $action1->setField('id');
        
        $action2 = new DatagridAction(array($this, 'onDelete'));
        $action2->setLabel('Excluir');
        $action2->setImage('ico_delete.png');
        $action2->setField('id');

        //adiciona as ações no datagrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);

        //cria o modelo de datagrid montando a estrutura
        $this->datagrid->createModel();

        $panel = new Panel('Produtos');
        $panel->add($this->form);

        $panel2 = new Panel();
        $panel2->add($this->datagrid);

        //monta a pagina por meio de uma caixa
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($panel);
        $box->add($panel2);

        parent::add($box);
    }

    public function onReload()
    {
        //obtém os dados do formulário de buscas
        $dados = $this->form->getData();

        //verifica de o usuário preencheu o formulário;
        if($dados->descricao){
            //filtra pela descrição do produto
            $this->filter = new Filter('descricao', 'like', "%{$dados->descricao}%");
        }
        $this->onReloadTrait();
        $this->loaded = TRUE;
    }

    public function show()
    {
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }
}