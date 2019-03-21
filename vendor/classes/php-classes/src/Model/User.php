<?php  

namespace Principal\Model;//pasta dessa classe

use \Principal\DB\Sql;//para usar a classe sql
use \Principal\Model;

class User extends Model {

	const SESSION = "User";

	public static function login($login, $password){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(":LOGIN"=>$login//:LOGIN e a variavel login

	));

		if (count($results) === 0) {//se o sistema nao encontrar nenhum cadastro

			throw new \Exception ("Usuário não existe ou senha inválida");// o \ é para acessar o namespace principal
			
		}

		$data = $results[0];//retorna o primeiro registro, posiçao 0

		if (password_verify($password, $data["despassword"]) === true){//verifica se a  senha existe, recebe a varivel password da funcao login e o despassword do banco
			$user = new User();//se existir instancia a classe

			$user->setData($data);//pega todos os campos da tebla users

			$_SESSION[User::SESSION] = $user->getValues();//coloca os dados do usuarios dentro da sessao

			return $user;

	} else {

		throw new \Exception ("Usuário não existe ou senha inválida");
	  }
   }

   public static function verifyLogin($inadmin = true){

   	if (!isset($_SESSION[User::SESSION])//se a session usuario nor for definida
   	|| !$_SESSION[User::SESSION] //ou se for falsa
   	|| !(int)$_SESSION[User::SESSION]["iduser"] > 0 //se o id dessa sessao for menor qeu 0
   	|| (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin ) { //se tem o acesso de administrador, o inadmin da sessao deve ser == ao do banco
     
     header("Location: /admin/login");//se uma das condições acima for verdadeira o site volta para o login
   	exit;

      } 
   }

   public static function logout(){//para sair da sessao

   	$_SESSION[User::SESSION] = NULL;
   }
 }



?>