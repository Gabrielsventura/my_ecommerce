<?php 

session_start();//startar a sessao
require_once("vendor/autoload.php"); //require autoload composer, tras as dependencias


use \Slim\Slim;



$app = new Slim();//instancia do slim framework

$app->config('debug', true); //detalha os erros que acontecem

require_once("functions.php");

require_once("site.php");

require_once("admin.php");

require_once("admin-users.php");

require_once("admin-categories.php");

require_once("admin-products.php");




$app->run(); //executa tudo

 ?>