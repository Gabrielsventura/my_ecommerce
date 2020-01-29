<?php

use \Principal\PageAdmin;
use \Principal\Model\User;

$app->get('/admin', function(){//rota do amin via get

	User::verifyLogin();//verifica se o usuarios está logado

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

//------ESQUECEU A SENHA-------

$app->get("/admin/forgot", function(){//rota para recuperar a senha

    $page = new PageAdmin([
      
      //desabilita o header e o footer porque na pgina de recuperação de senha e diferente, não tem a interface padrão, a pessoas ainda nao estara autenticada
      "header"=>false,
      "footer"=>false

	]);

	$page->setTpl("forgot");//

});


$app->post("/admin/forgot", function(){//rota para envio de email

	
    $user = User::getForgot($_POST["email"]);//email que o usuario enviou via post

    header("Location: /admin/forgot/sent");//pagina de confirmação de envio de email

    exit;

});

$app->get("/admin/forgot/sent", function(){

	 $page = new PageAdmin([
      
     
      "header"=>false,
      "footer"=>false

     ]);

      $page->setTpl("forgot-sent");

	
});

$app->get("/admin/forgot/reset", function(){//rota para receber o código
     
    $user = User::validForgotDecrypt($_GET["code"]);

     $page = new PageAdmin([
      
     
      "header"=>false,
      "footer"=>false

     ]);

      $page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]

	));


});

$app->post("/admin/forgot/reset", function(){

	$forgot = User::validForgotDecrypt($_POST["code"]);//valida o codigo

	User::setForgotUsed($forgot["idrecovery"]);//ve se o codigo ja foi usado

	$user = new User();

	$user->get((int)$forgot["iduser"]);

    //para criptografar a senha
	$password = password_hash($_POST["password"],PASSWORD_DEFAULT, [
     "cost"=>12

	]);

	$user->setPassword($password);

	 $page = new PageAdmin([
      
     
      "header"=>false,
      "footer"=>false

     ]);

      $page->setTpl("forgot-reset-success");
});


//------FIM FORGOT--------//

?>