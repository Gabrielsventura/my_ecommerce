<?php

use \Principal\PageAdmin;
use \Principal\Model\User;
use \Principal\Model\Category;
use \Principal\Page;

//----CATEGORIAS-----//


//-----READ----

$app->get("/admin/categories", function(){

	User::verifyLogin();

	$categories = Category::listAll();//metodo lista categorias

	$page = new PageAdmin();

	$page->setTpl("categories", [//pega a chaves do banco
      'categories'=>$categories

	]);
});



//----CREATE-----

$app->get("/admin/categories/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");//pagina para criar categorias
});


$app->post("/admin/categories/create", function(){

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header('Location: /admin/categories');//pagina que lista categorias
	exit;
});



//-------DELETE--------//

$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);//pega o id

	$category->delete();//deleta

	header('Location: /admin/categories');//volta para a lista
	exit;
});


//-------EDITAR----------//

$app->get("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);//casting converte tudo para numerico

	$page = new PageAdmin();

	$page->setTpl("categories-update", [
		'category'=>$category->getValues()

	]);
});

$app->post("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);//casting converte tudo para numerico

	$category->setData($_POST);

	$category->save();

	header('Location: /admin/categories');
	exit;

	});


$app->get("/categories/:idcategory", function($idcategory){

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", [

       'category'=>$category->getValues(),
       'products'=>[]


	]);
	
});


?>