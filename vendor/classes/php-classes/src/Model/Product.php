<?php  

namespace Principal\Model;//pasta dessa classe

use \Principal\DB\Sql;//para usar a classe sql
use \Principal\Model;
use \Principal\Mailer;


class Product extends Model {

	

  

   //função para listar
   public static function listAll(){

   	$sql = new Sql();


   //retorna os valores da tabela 
   	return $sql->select("SELECT * FROM tb_products ORDER BY desproduct"); 
   }

   public static function checkList($list){

        foreach ($list as &$row) {

        	$p = new Product();
        	
        	$p->setData($row);

        	$row = $p->getValues();
        }

        return $list;
   }

   public function save(){


   	$sql = new Sql();


	//chamando procedures que são consultas mais rapidas no banco
	$result = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array( //passa os dados que estao no atributo, chave=>this->atributo
		":idproduct"=>$this->getidproduct(),
		":desproduct"=>$this->getdesproduct(),
		":vlprice"=>$this->getvlprice(),
		":vlwidth"=>$this->getvlwidth(),
		":vlheight"=>$this->getvlheight(),
		":vllength"=>$this->getvllength(),
		":vlweight"=>$this->getvlweight(),
		":desurl"=>$this->getdesurl()
		
		));

	$this->setData($result[0]);
			
	}

	public function get($idproduct){

		$sql = new Sql();

		//se o id digitado for igual o da tabela ele retorna os dados
		$results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", [
			':idproduct'=>$idproduct//'parametro'=>$valor da variavel

		]);

		$this->setData($results[0]);
	}

	public function delete(){

		$sql = new Sql();

        //deleta a categoria 
		$sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", [
			':idproduct'=>$this->getidproduct()

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


	public function  checkPhoto(){

		if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
			"res" . DIRECTORY_SEPARATOR . 
			"site" . DIRECTORY_SEPARATOR . 
			"img" . DIRECTORY_SEPARATOR . 
			"products" . DIRECTORY_SEPARATOR . 
			$this->getidproduct() . ".jpg")) {

			$url =  "/res/site/img/products/" . $this->getidproduct() . ".jpg";
 		} else {

 			$url =  "/res/site/img/product.jpg";
 		}

 		return $this->setdesphoto($url);

 	}

	public function getValues(){

        $this->checkPhoto();
        
		$values = parent::getValues();

		return $values;
	}

	public function setPhoto($file){

		$extension = explode('.', $file['name']);
		$extension = end($extension);

		switch ($extension) {
			
			case "jpg":
			case "jpeg":
			$image = imagecreatefromjpeg($file["tmp_name"]);
				# code...
			break;

            case "gif":
            $image = imagecreatefromgif($file["tmp_name"]);
            break;

            case "png":
            $image = imagecreatefrompng($file["tmp_name"]);
            break;

			
		}

		$dist = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
			"res" . DIRECTORY_SEPARATOR . 
			"site" . DIRECTORY_SEPARATOR . 
			"img" . DIRECTORY_SEPARATOR . 
			"products" . DIRECTORY_SEPARATOR . 
			$this->getidproduct() . ".jpg";

		imagejpeg($image, $dist);

		imagedestroy($image);

		$this->checkPhoto();
	}

 }


?>




