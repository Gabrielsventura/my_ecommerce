<?php  

namespace Principal\Model;//pasta dessa classe

use \Principal\DB\Sql;//para usar a classe sql
use \Principal\Model;
use \Principal\Mailer;


class User extends Model {

	const SESSION = "User";
	const SECRET = "StoreVentura_secret";//chave para criptografar e descriptografar

	public static function login($login, $password){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(":LOGIN"=>$login//:LOGIN e a variavel login

	));

		if (count($results) === 0) {//se o sistema nao encontrar nenhum cadastro

			throw new \Exception ("Usuário não existe ou senha inválida");// o \ é para acessar o namespace principal
			
		}

		$data = $results[0];//retorna o primeiro registro, posiçao 0

		if (password_verify($password, $data["despassword"]) === true){//verifica se a  senha existe, recebe a varivel password da funcao  e o despassword do banco
			$user = new User();//se existir, instancia a classe

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

  


   public static function listAll(){//lista os usuarios

   	$sql = new Sql();

   	return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson"); // pega os usuarios das duas tabelas
   }


   public function save(){


   	$sql = new Sql();


	//chamando procedures que são consultas masi rapidas no banco
	$result = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(//passa os dados que estao no atributo, chave=>this->atributo
		":desperson"=>$this->getdesperson(),
		":deslogin"=>$this->getdeslogin(),
		//código para criptograar senha password_hash();
		":despassword"=> password_hash($this->getdespassword(), PASSWORD_DEFAULT, ["cost" => 12]),
		":desemail"=>$this->getdesemail(),
		":nrphone"=>$this->getnrphone(),
		":inadmin"=>$this->getinadmin()
 	
		));

	$this->setData($result[0]);
			
	}

	public function get($iduser){//pega o id que relaciona as duas tabelas

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(":iduser"=>$iduser//parametros chave e valor
	));

		$this->setData($results[0]);//pega o primeiro usuario
	
	}

	public function update(){

	$sql = new Sql();

	//chamando procedures que são consultas masi rapidas no banco
	$result = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(//passa os dados que estao no atributo, chave=>this->atributo
		":iduser"=>$this->getiduser(),
		":desperson"=>$this->getdesperson(),
		":deslogin"=>$this->getdeslogin(),
		":despassword"=>$this->getdespassword(),
		":desemail"=>$this->getdesemail(),
		":nrphone"=>$this->getnrphone(),
		":inadmin"=>$this->getinadmin()
 	
		));

	$this->setData($result[0]);


	}

	public function delete(){

		$sql = new Sql();

		$sql->query("CALL sp_users_delete(:iduser)", array(
		   ":iduser"=>$this->getiduser()
		));
	}

	public static function getForgot($email, $inadmin = true){//metodo de recuperação de senha

		$sql = new Sql();

		$results = $sql->select ("

		SELECT * 
		FROM tb_persons a INNER JOIN tb_users b USING(idperson)
		WHERE a.desemail = :email;

			", array(

				":email"=>$email

			));

		if (count($results) === 0) {

			throw new \Exception("Não foi possível recuperar a senha, email inválido");
			
			# code...
		}else{

			$data = $results[0];//pega o resultado no index 0

			$results2 = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(
				":iduser"=>$data["iduser"],//pega os dados na posição do results 
				":desip"=>$_SERVER["REMOTE_ADDR"] ));//pega o ip do usuario

			if (count($results2) === 0) {//se o results nao encontrou nada

				throw new \Exception("Não foi possível recuperar senha");
				
				
			}else{//se encontrou algo

				$dataRecovery = $results2[0];


         		//para criptografar o codigo
				$iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
				$code = openssl_encrypt($dataRecovery['idrecovery'], 'aes-256-cbc', User::SECRET, 0 , $iv);
				$result = base64_encode($iv.$code);
				if ($inadmin === true) {


                    //rota que vai para pagina de recuperação se for validado o código que será passado via get
					$link = "http://www.gabriels.ventura-ecommerce.com.br/admin/forgot/reset?code=$code";
				}else{

					$link = "http://www.gabriels.ventura-ecommerce.com.br/forgot/reset?code=$code";
				}

                //endereco, nome do remetente, assunto e nome da pagina
				$mailer = new Mailer($data['desemail'], $data['desperson'], "Redefinir senha na Ventura Store", "forgot", array(
					"name"=>$data['desperson'], 
					"link"=>$link  ));

				$mailer->send();
				return $data;
			}
		}

	}

	/*public static function validForgotDecrypt($result){//para descriptografar a o código de recuperacao de senha

		$result = base64_decode($result);
		$code = mb_substr($result, openssl_cipher_iv_length('aes-256-cbc'), null, '8bit');
		$iv = mb_substr($result, 0, openssl_cipher_iv_length('aes-256-cbc'), '8bit');;
		$idrecovery = openssl_decrypt($code, 'aes-256-cbc', User::SECRET, 0, $iv);
		
		$sql = new Sql();
		
		$results = $sql->select("

			

			SELECT * 
			FROM tb_userspasswordsrecoveries a
			INNER JOIN tb_users b USING(iduser)
			INNER JOIN tb_persons c USING(idperson)
			WHERE 
			a.idrecovery = :idrecovery
			AND
			a.dtrecovery IS NULL
			AND
			DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();

			

			", array(":idrecovery"=>$idrecovery

		));

		var_dump($results);

		if (count($results) === 0) {//se nao encontrou nada

			throw new \Exception("Não foi possível recuperar senha");
			
			# code...
		}else
		{
			return $results[0];
		}
	}*/

 }


?>




