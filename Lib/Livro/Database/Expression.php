<?php

namespace Livro\Database;

abstract class Expression{
    //operadores logicos
    const AND_OPERATOR = 'AND ';
    const OR_OPERATOR = 'OR ';
    
    abstract public function dump();
    
}