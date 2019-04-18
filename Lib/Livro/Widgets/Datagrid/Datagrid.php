<?php
namespace Livro\Widgets\Datagrid;

use Livro\Widgets\Container\Table;
use Livro\Widgets\Container\TableRow;
use Livro\Widgets\Base\Element;

class Datagrid extends Table
{
    private $columns;
    private $actions;
    private $rowcount;

    public function addColumn(DatagridColumn $object)
    {
        $this->columns[] = $object;
    }
    public function addAction(DatagridAction $object)
    {
        $this->actions[] = $object;
    }

    function clear()
    {
        //faz uma cópia do cabeçalho
        $copy = $this->children[0];

        //inicializa o vetor de linhas
        $this->children = array();

        //acrescenta novamente o cabeçalho
        $this->children[] = $copy;

        //zera a contagem de linhas
        $this->rowcount = 0;
    }

    public function createModel()
    {
        $thead = new Element('thead');
        parent::add($thead);
        //adiciona uma linha a tabela
        $row = new Element('tr');
        $thead->add($row);

        //adiciona celulas <th> vazias para cada ação (no cabeçalho ficam vazias)
        if($this->actions){
            foreach ($this->actions as $action){
                $celula = new Element('th');
                $celula->width = '40px';
                $row->add($celula);
            }
        }

        //adiciona as células <th> para os titulos das colunas do cabeçalho
        if($this->columns){
            //percorre as colunas do cabeçalho
            foreach($this->columns as $column){
                //obtém as propriedades da coluna (passados no construtor de DatagridColumn)
                $name = $column->getName();
                $label = $column->getLabel();
                $align = $column->getAlign();
                $width = $column->getWidth();

                $celula = new Element('th');
                $celula->add($label);
                //adiciona a célula com a coluna
                $row->add($celula);
                $celula->style = "text-align:$align";
                $celula->width = $width;

                //verifica se a coluna do cabeçalho tem uma ação
                if($column->getAction()){
                    $url = $column->getAction();
                    $celula->onclick = "document.location='$url'";
                }
            }
        }
    }

    public function addItem($object)
    {
        //adiciona uma linha a Datagrid
        $row = parent::addRow();

        //verifica se a listagem contém ações
        if($this->actions){
            //percorre as ações
            foreach($this->actions as $action){
                //obtem as propriedades da ação
                $url   = $action->serialize();
                $label = $action->getLabel();
                $image = $action->getImage();
                $field = $action->getField();

                //obtem o valor do campo do objeto que será passado adiante
                $key = $object->$field;

                //cria um link
                $link = new Element('a');
                $link->href = "{$url}&key={$key}&{$field}={$key}";

                //verifica se o link será com imagem ou com texto
                if($image){
                    //se for imagem adiciona a imagem ao link usando o path padrão
                    $img = new Element('img');
                    $img->src = "App/Images/$image";
                    $img->title = $label;
                    $link->add($img);
                }
                else {
                    //se for texto adiciona o rótulo de texto ao link
                    $link->add($label);
                }
                //adiciona a celula a linha
                $row->addCell($link);
            }
        }

        if($this->columns){
            //percorre as colunas da Datagrid
            foreach($this->columns as $column){
                //obtém as propriedades da coluna (passadas no construtor de DatagridColumn)
                $name     = $column->getName();
                $align    = $column->getAlign();
                $width    = $column->getWidth();
                $function = $column->getTransformer();
                $data     = $object->$name;

                //verifica se há função para transformar os dados
                if($function){
                    //aplica a função sobre os dados
                    $data = call_user_func($function, $data);
                }

                //adiciona a celula a linha
                $celula = $row->addCell($data);
                $celula->align = $align;
                $celula->width = $width;
            }
        }
        //incrementa o contador de linhas
        $this->rowcount++;//?
    }
}