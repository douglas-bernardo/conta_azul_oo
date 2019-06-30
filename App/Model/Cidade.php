<?php

use Livro\Database\Record;

class Cidade extends Record
{
    const TABLENAME = 'cidade';

    public function get_estado()
    {   //lazy initialization - inicialização tardia
        return new Estado($this->id_estado);//passando o Id no construtor o obj e carregado automaticamente
    }

    public function get_nome_estado()
    {   //ao passo que instancia o objeto já acessa o atributo nome *Boa!
        return (new Estado($this->id_estado))->nome;
    }
}