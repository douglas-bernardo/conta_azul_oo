<?php
require 'environment.php';

$config = array();

if (ENVIRONMENT == 'development') {
    define("BASE_URL", "http://localhost/conta_azul_oo/");
    // $config['dbname'] = 'contaazul';
    // $config['host'] = 'localhost';
    // $config['dbuser'] = 'root';
    // $config['dbpass'] = '';
} else {
    define("BASE_URL", "http://localhost/conta_azul_oo/");
    // $config['dbname'] = 'contaazul';
    // $config['host'] = 'localhost';
    // $config['dbuser'] = 'root';
    // $config['dbpass'] = '';
}