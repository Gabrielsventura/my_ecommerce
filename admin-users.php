<?php

use \Principal\PageAdmin;
use \Principal\Model\User;

//-----------CRUD------------

//---READ----

$app->get("/admin/users", function(){//rota ara a lista de usuarios

	User::verifyLogin();

	$users = User::listAll();//metodo da classe user que lista os usuarios

	$page = new PageAdmin();

	$page->setTpl("users", array("users"=>$users));//arrays da lista da lista da tabela, "chave"=>valor
});

//----CREATE------

$app->get("/admin/users/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");
});


//----DELETE-----

//deve ficar em cima do codigo do update para ele ler a barra depois do id, se nao, nao le 

//rota para deletar usuario
$app->get("/admin/users/:iduser/delete", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

});

//-----UPDATE-----

$app->get("/admin/users/:iduser", function($iduser){//rota para update, recebe o id do usuario que será alterado, $iduser recebe o valor de :iduser

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);//carrega id do usuario

	$page = new PageAdmin();

	$page->setTpl("users-update", array(//pega a chave user e a variavel user
		"user"=>$user->getValues()
	));

});


//---SAVLAR O CREATE----

$app->post("/admin/users/create", function(){//rota para salvar usuario criado no banco

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;//verifica se o inadmin foi definido

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;



});


//----SALVAR OS UPDATES----

$app->post("/admin/users/:iduser", function($iduser){//rota para salvar as edições

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;//verifica se o inadmin foi definido

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;

});



?>