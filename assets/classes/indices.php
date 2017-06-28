<?php

//**********************************************************************************************************************************************************//
/**
 * Faz consulta ao webservice do banco central e retorna o IGP-M atual
 * @author Julio Cezar - <julio@soltein.com.br>
 */
class SOAP extends SOAPClient {
    private static $instance;
    private function SOAP($url) {
        return parent::__construct($url);
    }
    public static function getInstance($dados) {
        if (empty(self::$instance))
            self::$instance = new SOAP($dados);
        return self::$instance;
    }
    public function call($configuracoes) {
        return parent::__soapCall($configuracoes[0], $configuracoes[1]);
    }
}
class INDICES {
    //private $url = "https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl";
    
    /**
     *Função para acessar soap
     * @access public
     * @param array contendo os itens necessários para o retorno do webservice
     * @return objeto XML 
     */
    public function soap($conf){
        $cliente = SOAP::getInstance('https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl');
        $resultado = $cliente->call($conf);
        return simplexml_load_string($resultado);        
    }
	
	public function getUltimos3Meses() {
        $mes = date('m');
        $ano = date('Y');
        $dataInicio = date("d/m/Y", strtotime("-3 month", mktime(0, 0, 0, $mes, 01, $ano)));
        $dataFim = date("d/m/Y", mktime(0, 0, 0, $mes + 1, 0, $ano));
        
        $conf[0] = 'getValoresSeriesXML';
        $conf[1] = array('codigoSeries' => array(188), 'dataInicio' => $dataInicio, 'dataFim' => $dataFim);
        return self::soap($conf);
    }
	
	
    public function indicador(){
        foreach (self::getUltimos3Meses()->SERIE->ITEM as $item) {
			 $result[] = (float)$item->VALOR;
        }
        return $result;        
    }

/*retorna o salario minimo vigente*********/
    public function getUltimoSalarioXML() {
        $conf[0] = 'getUltimoValorXML';
        $conf[1] = array('codigoSerie' => 1619);       
        $sal =  self::soap($conf);
		list($x) = $sal->SERIE->VALOR;
		return number_format((float)$x,2,".","");//retorna o salario minimo com . e duas casas decimais
    }
	
//*****************************************************************************//	 
	public function valor_atualizado($percentual,$rendimento){
		$pensao = strlen($rendimento)>1?$rendimento/100*$percentual:self::getUltimoSalarioXML()/100*$percentual;
		list($tx1, $tx2, $tx3) = self::indicador();
		$t1 = number_format(($pensao/100*$tx3)+$pensao,2,'.','');
		$t2 = number_format(($pensao/100*(array_sum(array($tx3, $tx2))))+$pensao,2,'.','');
		$t3 = number_format(($pensao/100*(array_sum(array($tx3, $tx2, $tx1))))+$pensao,2,'.','');
		return array('m1' => $t1,'m2' => $t2,'m3' => $t3,'tx1' => number_format(($tx3/100+1),5,'.',''), 'tx2' => number_format((array_sum(array($tx3, $tx2))/100+1),5,'.',''),'tx3' => number_format((array_sum(array($tx3, $tx2, $tx1))/100+1),5,'.',''),'pensao' => number_format($pensao,2,'.',''));			
	}
	
	public function atras_mes($mes,$prazo){
	list($y,$m,$d) = explode("-",$mes);
	$proxima = mktime(date('H'), date('i'), date('s'), $m-$prazo, $d, $y);
	  $data_server = date("Y-m-d", $proxima);
	  return $data_server;
}
}

//verificar a possibilidade de inserir calculo por data sugerida, ou seja, a partir da data sugerida como 3 últimos meses, e considerar o salário mínimo do mês em questão, pois a casos em que os meses são durante a passagem de ano, portanto, 2 tipos de salário mínimo.


?>