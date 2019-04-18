<?php

namespace Livro\Widgets\Wrapper;

use Livro\Widgets\Datagrid\Datagrid;

class DatagridWrapper
{
    private $decorated;

    public function __construct(Datagrid $datagrid) {
        $this->decorated = $datagrid;
        $this->decorated->class = 'table table-striped table-hover';
    }

    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->decorated, $method), $parameters);
    }

    public function __set($attribute, $value)
    {
        $this->decorated->$attribute = $value;
    }
}