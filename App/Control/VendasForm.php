<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Container\Panel;
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

use Livro\Session\Session;

class VendasForm extends Page
{
    private $form, $datagrid, $loaded;

    public function __construct() {
        parent::__construct();
        new Session; //instancia uma nova sessão

        //instancia um formulário de buscas
        $this->form = new FormWrapper(new Form('form_vendas'));

        //cria os campos do formulário
        $codigo    = new Entry('id_produto');
        $quantidade = new Entry('quantidade');

        $this->form->addField('Código', $codigo, 100);
        $this->form->addField('Quantidade', $quantidade, 200);

        $this->form->addAction('Adicionar', new Action(array($this, 'onAdiciona')));
        $this->form->addAction('Terminar', new Action(array(new ConcluiVendaForm, 'onLoad')));

        //Instancia objeto datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid);

        //instancia as colunas a Datagrid
        $codigo     = new DatagridColumn('id_produto', 'Código', 'right', 50);
        $descricao  = new DatagridColumn('descricao', 'Descrição', 'left', 200);
        $quantidade = new DatagridColumn('quantidade', 'Quantidade', 'right', 40);
        $preco      = new DatagridColumn('preco', 'Estado', 'Preço', 70);

        //define um transformador para a coluna preço
        $preco->setTransformer(array($this, 'formata_money'));

        //adiciona as colunas a datagrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($descricao);
        $this->datagrid->addColumn($quantidade);
        $this->datagrid->addColumn($preco);

        //cria uma ação para a datagrid
        $action = new DatagridAction(array($this, 'onDelete'));
        $action->setLabel('Excluir');
        $action->setImage('ico_delete.png');
        $action->setField('id_produto');
        
        //adiciona a ação no datagrid
        $this->datagrid->addAction($action);

        //cria o modelo de datagrid montando a estrutura
        $this->datagrid->createModel();

        $panel1 = new Panel('Vendas');
        $panel1->add($this->form);

        $panel2 = new Panel();
        $panel2->add($this->datagrid);

        //monta a pagina por meio de uma tabela
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($panel1);
        $box->add($panel2);

        parent::add($box);
    }

    public function onAdiciona()
    {
        try{
            //obtem os dados do formulario
            $item = $this->form->getData();

            Transaction::open('livro');
            $produto = Produto::find($item->id_produto);
            if($produto){
                //busca mais informações do produto
                $item->descricao = $produto->descricao;
                $item->preco     = $produto->preco_venda;

                $list = Session::getValue('list'); //lê a variável list da sessão
                $list[$item->id_produto] = $item;  //acrescenta produto na variavel
                Session::setValue('list', $list);
            }
            Transaction::close();
        }
        catch(Exception $e){
            new Message('error', $e->getMessage());
        }
        $this->onReload();//recarrega a listagem
    }

    public function onDelete($param)
    {
        //le a variavel list da sessão
        $list = Session::getValue('list');

        //exclui a posição que armazena o produto de código
        unset($list[$param['id_produto']]);

        //grava variavel lista de volta na sessão
        Session::setValue('list', $list);

        //recarrega a listagem
        $this->onReload();
    }

    public function onReload()
    {
        //obtem a variavel list da sessão
        $list = Session::getValue('list');
        
        //limpa o datagrid
        $this->datagrid->clear();
        if($list){
            foreach($list as $item){
                $this->datagrid->addItem($item);
            }
        }
        $this->loaded = TRUE;
    }

    public function formata_money($valor)
    {
        return number_format($valor, 2, ',', '.');
    }

    public function show()
    {
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }
}