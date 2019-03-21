<?php 

session_start();//startar a sessao
require_once("vendor/autoload.php"); //require autoload composer, tras as dependencias

use \Slim\Slim;
use \Principal\Page;
use \Principal\PageAdmin;
use \Principal\Model\User;

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

$app->get('/admin', function(){//rota do amin via get

	User::verifyLogin();
	$page = new PageAdmin(); //instancia do pageadmin

	$page->setTpl("index");//direcionando para o index do admin
});

$app->get('/admin/login', function(){//rota login do admin via get

	$page = new PageAdmin([
      
      //desabilita o header e o footer porque napgina do login e diferente
      "header"=>false,
      "footer"=>false

	]);

	$page->setTpl("login");
});

$app->post('/admin/login', function(){//rota login via post

	User::login($_POST["login"], $_POST["password"]);//recebe login e senha da classe user e envia pelo post

    header("Location: /admin");//se login e senha validos, ele volta para a rota admin onde fica o index
    
    exit;//para a execução do login

});


$app->get('/admin/logout', function(){//rota do logout

	User::logout();//funcao da classe user

	header("Location: /admin/login");//volta para o login

	exit;//para a execução do logout


});

$app->run(); //executa tudo

 ?>