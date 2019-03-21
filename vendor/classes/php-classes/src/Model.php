<?php  

namespace Principal;

class Model { //para criar os geters e seters

	private $values = [];//vai ter todos os dados do objeto usuario

	public function __call($name, $args){//chama os metodos get e set
        
        //para ver se o metodo chamado foi get ou set
		$method = substr($name, 0, 3);//a partir da posição 0 retorna as 3 primeiras letras
		$fieldName = substr($name, 3, strlen($name));//conta as letras a parti da 3 poisicao do nome do campo

		switch ($method) {
			case 'get':
			     return $this->values[$fieldname];
				# code...
				break;
			case "set":
			     $this->values[$fieldName] = $args[0];//recebe os atributos do usuario
		     break;
		}
	}

	public function setData($data = array()){//percorre os campos da tabela users e retorna valores

		foreach ($data as $key => $value) {
			
			$this->{"set".$key}($value);//jeito dinamico, concatena set com com as chaves do banco
		}
	}

	public function getValues(){

		return $this->values;//retorna os valores do usuario
	}
}


?>