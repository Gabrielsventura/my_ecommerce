<?php 

namespace Principal;

use Rain\Tpl; 

class Page{
   
   //os atributos estão privados para que só essa classe tenha acesso
	private $tpl;
	private $options = [];
	private $defaults =  [
        //header e footer "true" por padrao
		"header"=>true,
		"footer"=>true,

		"data"=>[]];

	public function __construct($opts = array(), $tpl_dir = "/views/"){

		$this->options = array_merge($this->defaults, $opts); //para o opts sobrescrever o default

		$config = array(
					"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,//caminho do template
					"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
					"debug"         => false
				   );

	Tpl::configure( $config );

	$this->tpl = new Tpl;

	$this->setData($this->options["data"]);

	 if ($this->options["header"] === true) {//se a opçao for header, carregar headre
	 	$this->tpl->draw("header"); //desenha o template na tela
	
	 }

	}

	public function setData($data = array()){//função para nao precisar fazer o foreach nos metodos e so chamar

		foreach($data as $key => $value){//precorre os dados do template
		$this->tpl->assign($key, $value);

     }
	
	}

	public function setTpl($name, $data = array(), $returnHtml = false){

		$this->setData($data);

		return $this->tpl->draw($name, $returnHtml);//retorna o draw para o tpl
     }


	


	public function __destruct(){

        if ($this->options["footer"] === true) {//se a opçao for footer, carrega footer

         	$this->tpl->draw("footer");//desneha o footer
         } 


	}
}


?>