<?php
//Config
require 'config.php';

// index - front controller

//Library loader
require_once 'Lib/Livro/Core/ClassLoader.php';
$al = new Livro\Core\ClassLoader;
$al->addNamespace('Livro', 'Lib/Livro');
$al->register();

//Application loader
require_once 'Lib/Livro/Core/AppLoader.php';
$al = new Livro\Core\AppLoader;
$al->addDirectory('App/Control');
$al->addDirectory('App/Model');
$al->register();

use Livro\Session\Session;

$content = '';
//session start
new Session;

if (Session::getValue('logged')){//se parametro logged == true
    $template = file_get_contents('App/Templates/template.html');// carrega o template principal
    $class = 'HomeControl';
}else{
    $template = file_get_contents('App/Templates/login.html');//retorna para a página de login
    $class = 'LoginForm';//
}

if (isset($_GET['class']) AND Session::getValue('logged')){
    $class = $_GET['class'];//armazena as classes das requisições
}

if(class_exists($class)){
    try{
        $pagina = new $class;         //Instancia a classe
        ob_start();                   //Inicia controle de output / buffer
        $pagina->show();              //Exibe a página
        $content = ob_get_contents(); //Lê o conteúdo gerado
        ob_end_clean();               //Finaliza controle de output // descarta o conteúdo do buffer. 
    }
    catch(Exception $e){
        $content = $e->getMessage() . '<br>' . $e->getTraceAsString();
    }
}

//Injeta conteúdo gerado dentro do template
$output = str_replace('{content}', $content, $template);
$output = str_replace('{class}', $class, $output);
$output = str_replace('{user_email}', Session::getValue('user_email'), $output);

//Exibe a saída gerada
echo $output;