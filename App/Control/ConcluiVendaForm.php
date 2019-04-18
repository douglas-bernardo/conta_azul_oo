<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Text;
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
use Livro\Session\Session;
use Livro\Model\Venda;

class ConcluiVendaForm extends Page
{
    private $form;

    public function __construct() {
        parent::__construct();
        new Session; //instancia uma nova sessão

        $this->form = new FormWrapper(new Form('form_conclui_venda'));

        //cria os campos do formulário
        $cliente     = new Entry('id_cliente');
        $valor_venda = new Entry('valor_venda');
        $desconto    = new Entry('desconto');
        $acrescimos  = new Entry('acrescimos');
        $valor_final = new Entry('valor_final');
        $parcelas    = new Combo('parcelas');
        $obs         = new Text('obs');

        $parcelas->addItems(array(1=>'Uma', 2=>'Duas', 3=>'Três'));
        $parcelas->setValue(1);

        //define uma ação de cálculo JavaScript
        $desconto->onBlur = "$('[name=valor_final]').val(Number($('[name=valor_venda]').
                            val()) + Number($('[name=acrescimos]').val()) - Number($('[name_desconto]').
                            val()));";
        
        $acrescimos->onBlur = $desconto->onBlur;

        $valor_venda->setEditable(FALSE);
        $valor_final->setEditable(FALSE);

        $this->form->addField('Cliente', $cliente, 200);
        $this->form->addField('Valor', $valor_venda, 200);
        $this->form->addField('Desconto', $desconto, 200);
        $this->form->addField('Acréscimos', $acrescimos, 200);
        $this->form->addField('Final', $valor_final, 200);
        $this->form->addField('Parcelas', $parcelas, 200);
        $this->form->addField('Obs', $obs, 200);

        $this->form->addAction('Salvar', new Action(array($this, 'onGravaVenda')));

        $panel = new Panel('Conclui Venda');
        $panel->add($this->form);

        parent::add($panel);
    }

    public function onLoad($param)
    {
        $total = 0;
        $itens = Session::getValue('list');
        if($itens){
            //percorre os itens
            foreach($itens as $item){
                $total += $item->preco * $item->quantidade;
            }
        }
        $data = new StdClass;
        $data->valor_venda = $total;
        $data->valor_final = $total;
        $this->form->setData($data);
    }

    public function onGravaVenda()
    {
        try{
            Transaction::open('livro');
            $dados = $this->form->getData();

            $cliente = Pessoa::find($dados->id_cliente);
            if(!$cliente){
                throw new Exception('Cliente não encontrado');                
            }

            //verifica debitos
            if($cliente->totalDebitos()>0){
                throw new Exception('Débitos impedem essa operação');
            }

            //inicia a gravção da venda
            $venda              = new Venda;
            $venda->cliente     = $cliente;
            $venda->data_venda  = date('Y-m-d');
            $venda->valor_venda = $dados->valor_venda;
            $venda->desconto    = $dados->desconto;
            $venda->acrescimos  = $dados->acrescimos;
            $venda->valor_final = $dados->valor_final;
            $venda->obs         = $dados->obs;

            //lê a variável list da sessão
            $itens = Session::getValue('list');
            if($itens){
                //percorre os itens
                foreach($itens as $item){
                    //adiciona o item a venda
                    $venda->addItem(new Produto($item->id_produto), $item->quantidade);
                }
            }
            //armazena a venda no banco de dados
            $venda->store();

            //gera o financeiro
            Conta::geraParcelas($dados->id_cliente, 2, $dados->valor_final, $dados->parcelas);

            Transaction::close();
            Session::setValue('list', array());

            //exibe mensagem de sucesso!
            new Message('info', 'Venda registrada com sucesso!');
        }
        catch(Exception $e){
            new Message('error', $e->getMessage());
        }
    }

}