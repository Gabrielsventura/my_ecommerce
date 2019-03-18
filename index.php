<?php 

require_once("vendor/autoload.php"); //require autoload composer

$app = new \Slim\Slim();//instancia do slim framework

$app->config('debug', true); //detalha os erros que acontecem

$app->get('/', function() { //rota principal
    
	$sql = new Principal\DB\Sql();

	$result = $sql->select("SELECT * FROM tb_users");

	echo json_encode($result);

});

$app->run(); //executa tudo

 ?>