<?php  

namespace Principal;

class PageAdmin extends Page {

	public function __construct($opts = array(), $tpl_dir = "/views/admin/"){

		parent::__construct($opts, $tpl_dir);//pegando os atributos da class pai
	}
}


?>