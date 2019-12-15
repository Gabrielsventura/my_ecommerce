<?php  

namespace Principal\Model;//pasta dessa classe

use \Principal\DB\Sql;//para usar a classe sql
use \Principal\Model;
use \Principal\Mailer;


class Category extends Model {

	

  


   public static function listAll(){//lista os usuarios

   	$sql = new Sql();

   	return $sql->select("SELECT * FROM tb_categories ORDER BY descategory"); // pega os usuarios das duas tabelas
   }

   public function save(){


   	$sql = new Sql();


	//chamando procedures que são consultas masi rapidas no banco
	$result = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(//passa os dados que estao no atributo, chave=>this->atributo
		":idcategory"=>$this->getidcategory(),
		":descategory"=>$this->getdescategory(),
		
		));

	$this->setData($result[0]);


		Category::updateFile();
			
	}

	public function get($idcategory){

		$sql = new Sql();

		//se o id digitado for igual o da tabela ele retorna os dados
		$results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", [
			':idcategory'=>$idcategory//'parametro'=>$valor da variavel

		]);

		$this->setData($results[0]);
	}

	public function delete(){

		$sql = new Sql();

        //deleta a categoria 
		$sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", [
			':idcategory'=>$this->getidcategory()

		]);

		Category::updateFile();

		
	}

	public static function updateFile(){

		$categories = Category::listAll();

		$html = [];
        
        //for para carregar as categorias cadastradas
		foreach ($categories as $row){

			array_push($html, '<li><a href="/categories/'.$row['idcategory'].'"> '.$row['descategory'].'</a></li>');


		}

        //para acessar a pagina que lista as categorias dentro da view
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "categories-menu.html", implode('', $html));
	}

 }


?>




