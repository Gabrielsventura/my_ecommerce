<?php 

require_once("vendor/autoload.php"); //require autoload composer, tras as dependencias

use \Slim\Slim;
use \Principal\Page;
use \Principal\PageAdmin;

$app = new Slim();//instancia do slim framework

$app->config('debug', true); //detalha os erros que acontecem


//a unica coisa que muda, são as configuração dentro rota
$app->get('/', function() { //rota principal
    
    $page = new Page();//instancia da classe Page

    $page->setTpl("index");//direcionando para o index
	
    

	/*$sql = new Principal\DB\Sql();

	$result = $sql->select("SELECT * FROM tb_users");

	echo json_encode($result);
    */
});

$app->get('/admin', function(){//rota do amin

	$page = new PageAdmin(); //instancia do pageadmin

	$page->setTpl("index");//direcionando para o index do admin
});

$app->run(); //executa tudo

 ?>