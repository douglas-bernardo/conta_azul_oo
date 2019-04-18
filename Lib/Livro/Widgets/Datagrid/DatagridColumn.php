<?php 
namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;

class DatagridColumn
{
    private $name;
    private $label;
    private $align;
    private $width;
    private $action;
    private $transformer;

    public function __construct($name, $label, $align, $width)
    {
        //atribui os parametros as propriedades do objeto
        $this->name = $name;//campo do banco de dados (qualquer campo coinscidindo com as colunas da tabela)
        $this->label = $label;//exibido no cabeçalho
        $this->align = $align;
        $this->width = $width;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getLabel()
    {
        return $this->label;
    }
    public function getAlign()
    {
        return $this->align;
    }
    public function getWidth()
    {
        return $this->width;
    }

    public function setAction(Action $action)//não confundir com DatagridAction!
    {   //opcional, se for setado, sera utilizado quando o usuário clicar no cabeçalho da coluna
        //e executará uma ação, como ordenação por exemplo
        $this->action = $action;
    }
    
    public function getAction()
    {   //verifica se a coluna possui ação
        if($this->action){
            return $this->action->serialize();
        }
    }

    public function setTransformer($callback)
    {   
        $this->transformer = $callback;
    }
    public function getTransformer()
    {
        return $this->transformer;
    }
}