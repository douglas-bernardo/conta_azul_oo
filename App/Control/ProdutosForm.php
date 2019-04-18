<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Database\Transaction;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\CheckGroup;
use Livro\Widgets\Form\RadioGroup;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;

use Livro\Traits\SaveTrait;
use Livro\Traits\EditTrait;

class ProdutosForm extends Page
{
    private $form;//formulário

    use SaveTrait;
    use EditTrait;

    public function __construct() {
        parent::__construct();

        $this->connection = 'livro';     //nome da conexão
        $this->activeRecord = 'Produto'; //nome do Active Record

        //instancia um formulario   
        $this->form = new FormWrapper(new Form('form_produtos'));

        //cria os campos do formulário
        $codigo      = new Entry('id');
        $descricao   = new Entry('descricao');
        $estoque     = new Entry('estoque');
        $preco_custo = new Entry('preco_custo');
        $preco_venda = new Entry('preco_venda');
        $fabricante  = new Combo('id_fabricante');
        $tipo        = new RadioGroup('id_tipo');
        $unidade     = new Combo('id_unidade');
        
        
        //carrega as cidades do banco de dados
        Transaction::open('livro');
        $fabricantes = Fabricante::all();
        $items = array();
        foreach($fabricantes as $obj_fabricante){
            $items[$obj_fabricante->id] = $obj_fabricante->nome;
        }
        $fabricante->addItems($items);

        $tipos = Tipo::all();
        $items = array();
        foreach($tipos as $obj_tipo){
            $items[$obj_tipo->id] = $obj_tipo->nome;
        }
        $tipo->addItems($items);

        $unidades = Unidade::all();
        $items = array();
        foreach($unidades as $obj_unidade){
            $items[$obj_unidade->id] = $obj_unidade->nome;
        }
        $unidade->addItems($items);

        Transaction::close();

        //define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);

        $this->form->addField('Código', $codigo, 100);
        $this->form->addField('Descrição', $descricao, 300);
        $this->form->addField('Estoque', $estoque, 300);
        $this->form->addField('Preço de Custo', $preco_custo, 200);
        $this->form->addField('Preço de Venda', $preco_venda, 200);
        $this->form->addField('Fabricante', $fabricante, 300);
        $this->form->addField('Tipo', $tipo, 200);
        $this->form->addField('Unidade', $unidade, 200);
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));

        //Cria um painel para conter o formulário
        $panel = new Panel('Produtos');
        $panel->add($this->form);
        
        //adiciona o painel a pg
        parent::add($panel);
    }
}