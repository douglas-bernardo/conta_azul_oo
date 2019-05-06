<?php
namespace Livro\Control;

class Action implements ActionInterface
{
    private $action;
    private $param;
    
    public function __construct(Callable $action)
    {
        $this->action = $action;
    }

    public function setParameter($param, $value)
    {
        $this->param[$param] = $value;
    }

    public function serialize()
    {
        //verifica se a ação é um método:
        if(is_array($this->action)){
            //obtém o nome da classe
            $url['class'] = is_object($this->action[0])? get_class($this->action[0]) : $this->action[0];
            //obtém o nome do método:
            $url['method'] = $this->action[1];
            //verifica se há parametros:
            if($this->param){
                $url = array_merge($url, $this->param);
            }
            //monta a url
            return '?' . http_build_query($url);
        }
    }
}