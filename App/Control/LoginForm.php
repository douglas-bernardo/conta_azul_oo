<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Password;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;

use Livro\Session\Session;
/**
 * Formulário de Login
 */
class LoginForm extends Page
{
    private $form; // formulário
    
    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();

        // instancia um formulário
        //$this->form = new FormWrapper(new Form('form_login'));
        //$this->form->setTitle('Login');
        
        //$login      = new Entry('login');
        //$password   = new Password('password');
        
        //$login->placeholder    = 'admin';
        //$password->placeholder = 'admin';
        
        //$this->form->addField('Login',    $login,    200);
        //$this->form->addField('Senha',    $password, 200);
        //$this->form->addAction('Login', new Action(array($this, 'onLogin')));

        //$panel = new Panel('Login');
        //$panel->add($this->form);

        // adiciona o formulário na página
        //parent::add($panel);


    }
    
    /**
     * Login
     */
    public function onLogin($param)
    {
        //$data = $this->form->getData();
        //if ($data->login == 'admin' AND $data->password == 'admin')//form data
        if(isset($_POST['username']) AND !empty($_POST['password'])){

            $username = $_POST['username'];
            $pass = $_POST['password'];

            if($username == 'jkdouglas21@gmail.com' AND $pass == '123'){

                Session::setValue('logged', TRUE);
                header("Location: index.php");
                exit;
                //echo "<script language='JavaScript'> window.location = 'index.php'; </script>";
            }
        }
    }
    
    /**
     * Logout
     */
    public function onLogout($param)
    {
        Session::setValue('logged', FALSE);
        echo "<script language='JavaScript'> window.location = 'index.php'; </script>";
    }
}
