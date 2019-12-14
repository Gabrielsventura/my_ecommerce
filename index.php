<?php 

session_start();//startar a sessao
require_once("vendor/autoload.php"); //require autoload composer, tras as dependencias


use \Slim\Slim;
use \Principal\Page;
use \Principal\PageAdmin;
use \Principal\Model\User;
use \Principal\Model\Category;

$app = new Slim();//instancia do slim framework

$app->config('debug', true); //detalha os erros que acontecem


//a unica coisa que muda, são as configuração dentro rota
$app->get('/', function() { //rota principal
    
    $page = new Page();//instancia da classe Page

    $page->setTpl("index");//direcionando para o index
	
    
});

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

$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);//pega o id

	$category->delete();//deleta

	header('Location: /admin/categories');//volta para a lista
	exit;
});


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







$app->run(); //executa tudo

 ?>