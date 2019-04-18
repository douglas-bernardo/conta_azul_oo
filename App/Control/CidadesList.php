<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
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
//traits
use Livro\Traits\DeleteTrait;
use Livro\Traits\ReloadTrait;
use Livro\Traits\EditTrait;
use Livro\Traits\SaveTrait;

class CidadesList extends Page
{
    private $form, $datagrid, $loaded;

    use EditTrait;
    use DeleteTrait;
    use ReloadTrait{
        onReload as onReloadTrait;
    }

    use SaveTrait{
        onSave as onSaveTrait;
    }

    public function __construct() {
        parent::__construct();

        $this->connection = 'livro';
        $this->activeRecord = 'Cidade';

        //instancia um formulário de buscas
        $this->form = new FormWrapper(new Form('form_cidades'));

        //cria os campos do formulário
        $codigo    = new Entry('id');
        $descricao = new Entry('nome');
        $estado    = new Combo('id_estado');

        $codigo->setEditable(FALSE);

        Transaction::open('livro');
        $estados = Estado::all();
        $items = array();
        foreach($estados as $obj_estado){
            $items[$obj_estado->id] = utf8_encode($obj_estado->nome);
        }
        $estado->addItems($items);
        Transaction::close();

        $this->form->addField('Código', $codigo, 40);
        $this->form->addField('Descrição', $descricao, 300);
        $this->form->addField('estado', $estado, 300);

        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        $this->form->addAction('Limpar', new Action(array($this, 'onEdit')));

        //Instancia objeto datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid);

        //instancia as colunas a Datagrid
        $codigo = new DatagridColumn('id', 'Código', 'right', 50);
        $nome   = new DatagridColumn('nome', 'Nome', 'left', 150);
        $estado = new DatagridColumn('nome_estado', 'Estado', 'left', 150);

        //adiciona as colunas a datagrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($estado);

        //instancia duas ações da datagrig
        $action1 = new DatagridAction(array($this, 'onEdit'));
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

        $panel = new Panel('Cidades');
        $panel->add($this->form);

        $panel2 = new Panel();
        $panel2->add($this->datagrid);

        //monta a pagina por meio de uma tabela
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($panel);
        $box->add($panel2);

        parent::add($box);
    }

    public function onSave()
    {
        $this->onSaveTrait();
        $this->onReload();
    }

    public function onReload()
    {
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