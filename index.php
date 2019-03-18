<?php 

require_once("vendor/autoload.php"); //require autoload composer, tras as dependencias

use \Slim\Slim;
use \Principal\Page;

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

$app->run(); //executa tudo

 ?>