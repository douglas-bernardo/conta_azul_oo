<?php
namespace Livro\Widgets\Base;

class Element{
    protected $tagname;
    protected $properties;
    protected $children;
    protected $required;

    public function __construct($name, $required = false)
    {
        $this->tagname = $name;//define o nome do elemento(tag).
        $this->required = $required;//define o nome do elemento.
    }
    
    public function __set($name, $value)
    {
        //$name = str_replace('_','-',$name);//adaptado para as classes modal bootstrap usam hifen '-'
        // armazena os valores atribuidos no array properties
        $this->properties[$name] = $value;
    }

    public function __get($name)
    {
        return isset($this->properties[$name])? $this->properties[$name] : NULL;
    }

    public function add($child)
    {
        //add o elemento filho recem criado ao array $children
        $this->children[] = $child;//recebe tanto uma string, quanto um objeto como parametro
    }

    public function show()
    {
        $this->open(); //abre a tag

        echo "\n";//quebra de linha somente no código HTML
        if ($this->children){
            foreach ($this->children as $child){
                if (is_object($child)){//se for objeto:
                    $child->show();
                } else if ((is_string($child)) or (is_numeric($child))){
                    echo $child;//se for texto
                }
            }
            $this->close();//fecha a tag
        }
    }

    private function open()
    {
        //exibe a tag de abertuta
        echo "<{$this->tagname}";
        if ($this->properties){
            //percorre as propriedades ex: class, id, type, value etc.
            foreach ($this->properties as $name=>$value) {
                if(is_scalar($value)){
                    echo " {$name}=\"{$value}\"";
                }
            }
        }
        echo ($this->required) ? ' required>' : '>' ;
    }

    private function close()
    {
        echo "</{$this->tagname}>\n";
    }

    public function __toString()
    {
        //ativa o buffer de saída. Enquanto o buffer de saída estiver ativo, não é enviada a saída do script 
        ob_start();
        $this->show();
        $content = ob_get_clean(); // Obtém o conteudo do buffer e exclui o buffer de saída atual 
        return $content;
    }
    
}