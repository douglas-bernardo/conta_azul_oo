<?php

use Livro\Control\Page;
use Livro\Widgets\Container\Tab;
use Livro\Widgets\Container\TabContent;

class PermissionControl extends Page
{
    public function __construct() {
        parent::__construct();

        //cria um objeto tab
        $tab = new Tab;

        //adiciona a 1ª tab como 'ativa' por padrão
        $tab->addTabItem('Home', 'home', True);

        //adiciona as tabs secundárias inativas:
        $tab->addTabItem('Profile', 'profile');
        $tab->addTabItem('Contact', 'contact');

        //define os objetos de conteúdo
        $tabContent = new TabContent($tab);

        //adiciona os conteudos de acordo com cada tab sendo o 1º como ativo por padrão
        $tabContent->addContent('Home', 'Página Home', True);
        $tabContent->addContent('Profile', 'Página Profile');
        $tabContent->addContent('Contact', 'Página Contact');

        //adiciona as tabs e seus respectivos conteúdos a página principal:
        parent::add($tab);
        parent::add($tabContent);
    }
}
