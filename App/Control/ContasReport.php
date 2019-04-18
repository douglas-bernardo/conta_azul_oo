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

class ContasReport extends Page
{
    private $form;//formulário de entrada

    public function __construct() {
        parent::__construct();
        new Session; //instancia uma nova sessão

        //instancia um formulário de buscas
        $this->form = new FormWrapper(new Form('form_relat_contas'));
        //$this->form->setTitle('Relatório de contas');

        //cria os campos do formulário
        $data_ini = new Entry('data_ini');
        $data_fim = new Entry('data_fim');

        $this->form->addField('Vencimento Inicial', $data_ini, 200);
        $this->form->addField('Vencimento Final', $data_fim, 200);

        $this->form->addAction('Gerar', new Action(array($this, 'onGera')));

        $panel = new Panel('Relatório de Contas');
        $panel->add($this->form);

        parent::add($panel);
    }

    public function onGera()
    {
        require_once 'Lib/Twig/vendor/Autoload.php';
        //Twig_Autoloader::register();

        $loader = new Twig_Loader_Filesystem('App/Resources');
        $twig = new Twig_Environment($loader);
        $template = $twig->loadTemplate('contas_report.html');

        //obtem os dados do formulário
        $dados = $this->form->getData();

        //joga os dados do formulário de volta ao formulário
        $this->form->setData($dados);

        //converte a data pesquisada para o formato internacional
        $conv_data_to_us = function($data){
            $dia = substr($data,0,2);
            $mes = substr($data,3,2);
            $ano = substr($data,6,4);
            return "{$ano}-{$mes}-{$dia}";
        };

        //lês os campos e converte para o padrão
        $data_ini = $conv_data_to_us($dados->data_ini);
        $data_fim = $conv_data_to_us($dados->data_fim);

        //Vetor de parametros para o template
        $replaces = array();
        $replaces['data_ini'] = $dados->data_ini;
        $replaces['data_fim'] = $dados->data_fim;

        try{
            Transaction::open('livro');

            $repository = new Repository('Conta');

            $criterio = new Criteria;
            $criterio->setProperty('order', 'dt_vencimento');

            if($dados->data_ini)
                $criterio->add(new Filter('dt_vencimento', '>=', $data_ini));
            if($dados->data_fim)
                $criterio->add(new Filter('dt_vencimento', '<=', $data_fim));
            
            //le as contas que satisfazem o criterio
            $contas = $repository->load($criterio);

            if($contas){
                foreach($contas as $conta)
                {
                    $conta_array = $conta->toArray();
                    $conta_array['nome_cliente'] = $conta->cliente->nome;
                    $replaces['contas'][] = $conta_array;
                }
            }
            //finaliza a transação
            Transaction::close();
        }
        catch(Exception $e){
            new Message('error', $e->getMessage());
            Transaction::rollback();
        }
        $content = $template->render($replaces);
        parent::add($content);
    }
}