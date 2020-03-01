<?php


//funcao para formatação numeros
function formatPrice(float $vlprice){

    //retorna um valor com com os 2 separadores "." para casa de milhar e "," para casa decimal
	return number_format($vlprice, 2, ",", ".");
}

?>