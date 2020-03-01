<?php

use \Principal\PageAdmin;
use \Principal\Model\User;
use \Principal\Model\Category;
use \Principal\Page;
use \Principal\Model\Product;

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



//----ROTA PARA A TELA DE CADASTRO----

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



//ROTA PARA PEGAR OS PRODUTOS RELACIONADOS A UMA DETERMINADA CATEGORIA E OS QUE NÃO ESTÃO

$app->get("/admin/categories/:idcategory/products", function($idcategory){

	User::verifyLogin();

    $category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-products", [

       'category'=>$category->getValues(),
       'productsRelated'=>$category->getProducts(),
       'productsNotRelated'=>$category->getProducts(false)


	]);

});

//ROTA PARA ADICIONAR OS PRODUTOS NA CATEGORIA RELACIONADA

$app->get("/admin/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct){

	User::verifyLogin();

    $category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->addProduct($product);

	header("Location: /admin/categories/".$idcategory."/products");
	exit;


});


//ROTA PARA REMOVER OS PRODUTOS DA CATEGORIA
$app->get("/admin/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct){

	User::verifyLogin();

    $category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->removeProduct($product);

	header("Location: /admin/categories/".$idcategory."/products");
	exit;


});


?>