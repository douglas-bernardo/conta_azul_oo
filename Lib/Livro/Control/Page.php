<?php
namespace Livro\Control;

use Livro\Widgets\Base\Element;

class Page extends Element
{

    public function __construct()
    {
        parent::__construct('div');
    }

    //todas as classes filhas de page (classes control) herdarão e podem executar o metodo show()    
    public function show()
    {
        //nas classes onde existirem uma reuisição GET segue o processo abaixo:
        if ($_GET)//existe uma requisição GET?
        {
            $class  = isset($_GET['class'])  ? $_GET['class'] : NULL;//existe uma classe definida?
            $method = isset($_GET['method']) ? $_GET['method'] : NULL;//existe um método?

            if($class)
            {
                //a classe da URL é mesma da classe atual? (filha de PAGE)
                $object = $class == get_class($this) ? $this : new $class;// se sim, realiza a instancia da mesma
                if (method_exists($object, $method))// na classe atual existe o mmétodo?
                {
                    call_user_func(array($object, $method), $_GET);
                }
            }
        }
        //nas classes onde não existe a requisição GET:
        parent::show();//executa o metodo show da classe pai Element
    }
}