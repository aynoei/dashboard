<?php 
/*accessible outside the object..var keyword also public*/
	//public $height;
	/*accessible by inherited classes*/
	//protected $sin;
	/*accessible only to the object*/
	//private $pin;

class P__Biblioteca {


//*****************Classes de transformação de caracteres********************************************************************************//	
		//maiuscula e minuscula; 1 = MAIUSCULA, 2 = minuscula
 public static  function Convertem($term, $tp) {
			if ($tp == "1") $palavra = strtr(strtoupper($term),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
			elseif ($tp == "0") $palavra = strtr(strtolower($term),"ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß","àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
			return $palavra;
} 
	
//todas maiusculas	
 public  function maius($a){
	$b = $this->convertem($a,1);
	return $b;
}
//todas minusculas
 public  function minus($a){
	$b = $this->convertem($a,0);
	return $b;
}
//*****************Classes para datas********************************************************************************//	
//data_user_para_mysql
 public  function banco($y){
        $data_inverter = explode("/",$y);
        $x = $data_inverter[2].'-'. $data_inverter[1].'-'. $data_inverter[0];
        return $x;
}
//data_mysql_para_user
 public  function cliente($y){
        $data_inverter = explode("-",$y);
        $x = $data_inverter[2].'/'. $data_inverter[1].'/'. $data_inverter[0];
        return $x;
}	
	
	
}//fim da classe biblioteca






