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

class PessoasList extends Page
{
    private $form;      //formulario de buscas
    private $datagrid;  //listagem
    private $loaded;

    public function __construct() {
        parent::__construct();

        //instancia um formulário de buscas
        $this->form = new FormWrapper(new Form('form_busca_pessoas'));
        $nome = new Entry('nome');
        $this->form->addField('Nome', $nome, 300);
        $this->form->addAction('Buscar', new Action(array($this, 'onReload')));
        $this->form->addAction('Novo', new Action(array(new PessoasForm, 'onEdit')));

        //Instancia objeto datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid);

        //instancia as colunas a Datagrid
        $codigo = new DatagridColumn('id', 'Código', 'right', 50);
        $nome = new DatagridColumn('nome', 'Nome', 'right', 200);
        $endereco = new DatagridColumn('endereco', 'Endereço', 'right', 200);
        $cidade = new DatagridColumn('nome_cidade', 'Cidade', 'right', 140);

        //adiciona as colunas a datagrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($endereco);
        $this->datagrid->addColumn($cidade);

        //instancia duas ações da datagrig
        $action1 = new DatagridAction(array(new PessoasForm, 'onEdit'));
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

        //monta a página no meio de uma caixa
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($this->form);
        $box->add($this->datagrid);

        parent::add($box);
    }

    public function onReload()
    {
        Transaction::open('livro');
        $repository = new Repository('Pessoa');//o nome da tabela está na classe pessoa

        //cria un critério de selação de dados
        $criteria = new Criteria;
        $criteria->setProperty('order', 'id');

        //obtém os dados do formulário de buscas
        $dados = $this->form->getData();

        //verifica se o usuário preencheu os dados
        if($dados->nome){
            //filtra pelo nome da pessoa
            $criteria->add(new Filter('nome', 'like', "%{$dados->nome}%"));
        }

        //carrega os objetos* que satisfazem o critério
        $pessoas = $repository->load($criteria);
        $this->datagrid->clear();
        if($pessoas){
            foreach($pessoas as $pessoa){
                $this->datagrid->addItem($pessoa);
            }
        }
        //finaliza a transação
        Transaction::close();
        $this->loaded = TRUE;
    }

    public function onDelete($param)
    {
        $id = $param['id'];//obtêm o parâmetro id
        $action1 = new Action(array($this, 'Delete'));
        $action1->setParameter('id', $id);

        new Question('Deseja realmente excluir o registro?', $action1);
    }

    public function Delete($param)
    {
        try{
            $id = $param['id'];
            Transaction::open('livro');
            $pessoa = Pessoa::find($id);
            $pessoa->delete();
            Transaction::close();
            $this->onReload();//recarrega o datagrid
            new Message('info', "Registro excluído com sucesso!");
        }
        catch(Exception $e){
            new Message('error', $e->getMessage());
        }
    }

    public function show()
    {
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }
}