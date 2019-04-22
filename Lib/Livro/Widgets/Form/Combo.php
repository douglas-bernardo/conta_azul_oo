<?php

namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

class Combo extends Field implements FormElementInterface
{
    private $items;//array contendo os itens do combo

    public function __construct($name)
    {
        //executa o método construtor da classe pai
        parent::__construct($name);

        //cria uma tag html do tipo selec
        $this->tag = new Element('select');
        $this->tag->class = 'combo';    //classe CSS
        
    }

    public function addItems($items)
    {
        $this->items = $items;
    }
    public function show()
    {
        //atribui as propriedades da tag
        $this->tag->name = $this->name; //nome da tag
        $this->tag->style = "width:{$this->size}px";//tamanho em pixels

        //cria uma tag option com um valor padrão
        $option = new Element('option');
        $option->add('');
        $option->value = '0'; //valor da tag

        //adiciona a opção a combo
        $this->tag->add($option);

        if($this->items)
        {
            //percorre os itens adicionados
            foreach ($this->items as $chave => $item){
                //cria uma tag <option> para o item
                $option = new Element('option');
                $option->value = $chave;//define o indice da opção
                $option->add($item);//adiciona o texto da opção

                //caso seja a opção selecionada
                if ($chave == $this->value){
                    $option->selected = 1;
                }
                //adiciona a opção a combo
                $this->tag->add($option);
            }
        }
        //verifica se o campo é editável
        if(!parent::getEditable())
        {
            $this->tag->readonly = "1";
        }
        $this->tag->show();
    }
}
