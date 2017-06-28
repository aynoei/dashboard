<?php
/*********Bibliotecas de funoes***************************************************************************/
define( 'JPATH_BASE', realpath(dirname(__FILE__).'' ));
JLoader::register('Biblioteca',JPATH_BASE.'/components/com_promotor/assets/classes/biblioteca.php');


/********************************************************************************************************/

class Modulos{


public function objeto($tabela, $campo, $termo){
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo.' = "'.$termo.'"');  
				$db->setQuery($query);  
				return $db->loadObject();	
				}

public  function quem($x){	
		$group_pai =  self::objeto('#__users','id',$x);//grupo pai				
		return $group_pai->name;
				}	
								
public function orgao($x){
		$group_pai = self::objeto('#__usergroups','id',$x);//grupo pai				
		return $group_pai->title;
	}				
				
public  function busca_basica_array($tabela, $campo, $termo){
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo.' = "'.$termo.'"');
				$db->setQuery($query);  
				return $db->loadAssocList();
				}

public  function busca_basica_array_opcao($sql){
				$db = JFactory::getDBO();  				
				$query  = $sql;
				$db->setQuery($query);  
				return $db->loadAssocList();
				}				
			 	
public  function busca_basica_linha($tabela, $campo, $termo){
		$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo.' = "'.$termo.'"');
				$db->setQuery($query);  
				return $db->loadAssoc();//tras a linha do resultado buscado e salva em array
				
	}

public  function recupera_audiencia($tabela, $campo, $termo){//recupera dados da semana especifica da tabela audiencia
		$se = explode("_", Biblioteca::diasemana(date('Y-m-d')));
		$d1 = $se[0];
		$d2 =$se[1]; 
		
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo." = '".$termo."' AND evento_data BETWEEN '".$d1."' AND '".$d2."'")
				->order("evento_data asc");     
				$db->setQuery($query);  
				return $db->loadAssocList();
				
			 }
			 
public  function recupera_oficio($tabela, $campo, $termo){//recupera dados da semana especifica da tabela oficio
		$se = explode("_", Biblioteca::diasemana(date('Y-m-d')));
		$d1 = $se[0];
		$d2 =$se[1]; 
		
		//***************lista os oficios deste usuario
				$lista = self::busca_basica_array($tabela,$campo,$termo);
				
				for($x=0;$x<count($lista);$x++){
		
							if($lista[$x]['tipo']=='dias'){
										$vencimento = @Biblioteca::expira_dia($lista[$x]['recebido'],$lista[$x]['prazo']);//vencimento para dias
												if($vencimento>=$d1 && $vencimento<=$d2){
												$oficios[] = self::busca_basica_linha($tabela,'Id',$lista[$x]['Id']);					
												}
                                }else{
										$vencimento = @Biblioteca::expira_hora($lista[$x]['recebido'],$lista[$x]['prazo']);//vencimento para horas
												if($vencimento>=$d1 && $vencimento<=$d2){
												$oficios[]= self::busca_basica_linha($tabela,'Id',$lista[$x]['Id']);
												}
								}
					
				}

 
				
		return @$oficios;
				
					
	}
	
public  function recupera_oficio_vencido($tabela, $campo, $termo){//recupera dados da semana especifica da tabela oficio
		$se = explode("_", Biblioteca::diasemana(date('Y-m-d')));
		$d1 = $se[0];
		//$d2 =$se[1]; 
		$d2 =date("Y-m-d");// data de hoje
		

		
		//***************lista os oficios deste usuario
				$lista = self::busca_basica_array($tabela,$campo,$termo);
				
				for($x=0;$x<count($lista);$x++){
					
							//**********verifica se há datas válidas**********
		
							if($lista[$x]['tipo']=='dias'){
										$vencimento = @Biblioteca::expira_dia($lista[$x]['recebido'],$lista[$x]['prazo']);//vencimento para dias
												if($vencimento<$d2 && $lista[$x]['status'] != '1' ){//menor que a data de hoje
												$oficios[] = self::busca_basica_linha($tabela,'Id',$lista[$x]['Id']);					
												}
                                }else{
										$vencimento = @Biblioteca::expira_hora($lista[$x]['recebido'],$lista[$x]['prazo']);//vencimento para horas
												if($vencimento<$d2 && $lista[$x]['status'] != '1'){//menor que a data de hoje
												$oficios[]= self::busca_basica_linha($tabela,'Id',$lista[$x]['Id']);
												}
								}
					
				}

 
				
		return @$oficios;
				
					
	}
	
public  function recupera_todos_depois_expediente($tabela, $campo, $termo, $tipo){
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo.' = "'.$termo.'" AND tipo = "'.$tipo.'" AND evento_data >= "'.date('Y-m-d').'"')//busca os dados maiores ou iguais à data
				->order('id desc');     
				$db->setQuery($query);  
				return $db->loadAssocList();	
			 }
//**********************funcao do modulo de lista do expediente**************************//				 
public  function recupera_todos_antes_expediente($tabela, $campo, $termo, $tipo){
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo.' = "'.$termo.'" AND tipo = "'.$tipo.'" AND evento_data < "'.date('Y-m-d').'" ')//busca os dados maiores ou iguais à data
				->order('id desc');     
				$db->setQuery($query);  
				return $db->loadAssocList();	
			 }
			 
public	function recupera_todos_depois($tabela, $campo, $termo){
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo.' = "'.$termo.'" AND evento_data >= "'.date('Y-m-d').'"')//busca os dados maiores ou iguais à data
				->order('id desc');     
				$db->setQuery($query);  
				return $db->loadAssocList();	
			 }
			 
public	function recupera_todos_antes($tabela, $campo, $termo){
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo.' = "'.$termo.'" AND evento_data < "'.date('Y-m-d').'"')//busca os dados menores ou iguais à data
				->order('id desc');     
				$db->setQuery($query);  
				return $db->loadAssocList();	
			 }
//**********************funcao ultimo() do modulo de novo numero do oficio**************************//	
public function ultimo($tabela, $campo, $termo){
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo.' = "'.$termo.'"')
				->order('id'.' desc');     
				$db->setQuery($query,0,1);  
				return $db->loadObject();	
			 }
//**********************funcao ultimo_expediente() do modulo de novo numero do expediente**************************//	
public function ultimo_expediente($tabela, $campo, $termo, $tipo){
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo.' = "'.$termo.'" AND tipo = "'.$tipo.'"')
				->order('id desc');     
				$db->setQuery($query,0,1);  
				return $db->loadObject();	
			 }
		
			 
public function recupera_um($tabela, $campo, $termo){//conexão padrão para pegar u munico dado
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($tabela);
			$query->where($campo.' = "'.$termo.'"');
			$db->setQuery($query);
			$ultimo = $db->insertid();
			$db->loadObject();// salva todos os dados retornados para recuperar pelo campo ex: $row->campo;
			return $ultimo;
			}
		
public function recupera_todos_ordem($tabela, $campo, $termo, $ordem){	
			$db = JFactory::getDBO();  							
			$query  = $db->getQuery(true);			
			$query->select('*')			
			->from($tabela)			
			->where($campo.' = "'.$termo.'"')			
			->order($ordem.' asc');  			
			$db->setQuery($query);  			
			return $db->loadAssocList();				
			}
public function recupera_todos_desc($tabela, $campo, $termo, $status, $ano){
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo.' = '.$termo.' AND status = "'.$status.'" AND YEAR(criado) = "'.$ano.'"')//para limitar o oficio de data mais antiga
				->order('id'.' desc');     
				$db->setQuery($query);  
				return $db->loadAssocList();	
			 }	
			 
public function recupera_todos_comarca($tabela, $campo, $termo, $campo_ordem, $ordem){	
			$user =& JFactory::getUser();
			$id = $user->id;
			
			$group_id = self::objeto('#__user_usergroup_map','user_id',$id);//a qual promotoria o usuario é		
			$parent_id=  self::objeto('#__usergroups','id',$group_id->group_id);//define comarca

		
			$db = JFactory::getDBO();  							
			$query  = $db->getQuery(true);			
			$query->select('*')			
			->from($tabela)
			->where($campo.' = "'.$termo.'" AND comarca = "'.$parent_id->parent_id.'"')						
			->order($campo_ordem." ".$ordem);  			
			$db->setQuery($query);  			
			return $db->loadAssocList();				
			}
			
public function recupera_todos_comarca_geral($tabela, $campo_ordem, $ordem){	
			$user =& JFactory::getUser();
			$id = $user->id;
			
			$group_id = self::objeto('#__user_usergroup_map','user_id',$id);//a qual promotoria o usuario é		
			$parent_id=  self::objeto('#__usergroups','id',$group_id->group_id);//define comarca

		
			$db = JFactory::getDBO();  							
			$query  = $db->getQuery(true);			
			$query->select('*')			
			->from($tabela)
			->where('comarca = '.$parent_id->parent_id.' AND promotoria != '.$group_id->group_id.' AND evento_data >= "'.date('Y-m-d').'"')						
			->order($campo_ordem." ".$ordem);  			
			$db->setQuery($query);  			
			return $db->loadAssocList();				
			}		
//*******************************Função para gerar formulario apartir do XML nos modulos***********************************//

public function gera_formulario($array_form){
$fieldsets = $array_form->getFieldsets();
    foreach ($fieldsets as $fieldset) {
		$fields = $array_form->getFieldset($fieldset->name);
        foreach ($fields as $field) {
				 $campos[] = $field;
        }

    }
	return $campos;
}
//******************************Lista as promotorias que fazem parte da comarca**********************************************//
public function getPromotorias(){// gera uma lista das promotorias da comarca
	
		$user =& JFactory::getUser();
		$id = $user->id;
		
		$group_id = self::objeto('#__user_usergroup_map','user_id',$id);//grupo pertencente
		$group_pai = self::objeto('#__usergroups','id',$group_id->group_id);//grupo pai
		
		//*************************sua promotoria************************//
		$group_id = self::objeto('#__user_usergroup_map','user_id',$id);//grupo pertencente
		$retorno = self::recupera_todos_ordem('#__usergroups','id',$group_id->group_id,'title','asc');//
		
		//***************todas as promotorias*****************************//
		
		$todos =  self::recupera_todos_ordem('#__usergroups','parent_id',$group_pai->parent_id, 'title', 'asc');//lista dos grupos	
		
		$lista = array_merge($retorno,$todos);//une as arrays para que o primeiro option seja da sua promotoria
			
		//Gera automaticamente a lista das promotorias da comarca
		foreach($lista as $prom):
		
		$options[] = "<option value=\"".$prom['id']."\">".$prom['title']."</option>";
		
		endforeach;
		
		$select = "<select name=\"promotoria\">".Biblioteca::opcao($options,'')."</select>";
		
		return $select;
		
		
		}
		
public function getPromotoriasEspecificas($id){// gera o title da promotoria especifica
		
		$group_id = Modulos::objeto('#__user_usergroup_map','user_id',$id);//grupo pertencente
		$group_pai = Modulos::objeto('#__usergroups','id',$group_id->group_id);//grupo pai
		
		return $group_pai->title;	 

	
		
	}
//************************************************Gera a lista de autocomplete*********************************************************//
public function lista_autocomplete($tabela, $campo, $user_id){
$lista_ac = self::busca_basica_array_opcao("SELECT DISTINCT ".$campo." FROM #__".$tabela." WHERE user_id = '".$user_id."'");

foreach ($lista_ac as $key => $val):
	foreach($val as $key => $val):
       $valor = $val;
	   endforeach;
   $listas_ac[] = "'".$valor."'";
endforeach; 

return Biblioteca::opcao($listas_ac,',');
}
//********************************************DELETA CAMPO***********************************************//
public function apagar_linha($tabela, $id){
				$db = JFactory::getDbo(); 
				$query = $db->getQuery(true); 
				$conditions = $co; 
				$query->delete($tabela);
				$query->where('Id = "'.$id.'"');				 
				$db->setQuery($query);				 
				$result = $db->execute();
				return $result;
				}
				
public function apagar_registro($tabela, $condicoes){
					$c = explode(",",$condicoes);
					foreach($c as $co):
							$con = explode(":",$co);
							$cond[] = $con[0] . ' = '.'"'.$con[1].'"';
					endforeach;
				$db = JFactory::getDbo(); 
				$query = $db->getQuery(true); 
				$conditions = $co; 
				$query->delete($tabela);
				$query->where(Biblioteca::opcao($cond," AND "));				 
				$db->setQuery($query);				 
				$result = $db->execute();
				return $result;
				}
				
//********************************************FILTRA A ARRAY POR CAMPO ESPECIFICO***********************************************//			
/*
$array = a string que contem a array com os dados
$campo = nome da chave da array
$valor = dado que se deseja buscar
*/	
public function filtrar_array($array, $chave, $campo, $tipo){
	
	switch($tipo){
		case 'ano':	//apenas para anos com /, ex: /2015
			function filtrar($var)
				{
					 return (preg_grep('/^\+?\d+\/'.$campo.'$/', $var) && $var[$chave]);
				}
		
				return array_filter($array, "filtrar");
		break;
	}
	




	
}
//*******************************************RECUPERA POR USUARIO*************************************************//
/*
public  function recupera_periodo($tabela, $campo, $termo, $data, $inicial, $final){//recupera dados de um periodo especifico	
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo." = '".$termo."' AND '".$data."' BETWEEN '".$inicial."' AND '".$final."'")
				->order($data." asc");     
				$db->setQuery($query);  
				return $db->loadAssocList();				
			 }

public  function recupera_mes($tabela, $campo, $termo, $data){//recupera dados do mes atual		

				$mes = date('Y-m');
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo." = '".$termo."' WHERE '".$data."' LIKE '".$mes."-%'")
				->order($data." asc");     
				$db->setQuery($query);  
				return $db->loadAssocList();				
			 }
public  function recupera_hoje($tabela, $campo, $termo, $data){//recupera dados de hoje		
			
				$mes = date('Y-m');
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo." = '".$termo."' WHERE '".$data."' LIKE '".$hoje."'")
				->order($data." asc");     
				$db->setQuery($query);  
				return $db->loadAssocList();				
			 }
public  function recupera_passado($tabela, $campo, $termo, $data){//recupera dados do mes passado		
					$hoj = date('m');
					$il = $hoj-01;
					$i = date('Y') . "-" . $il;
					$ik = date('Y') . "-0" . $il;
				if($il<10){
			$mensal = "AND '".$data."' LIKE '%".$ik."-%'";
					}else{
			$mensal = "AND '".$data."' LIKE '%".$i."-%'";
					}

				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo." = '".$termo."' ".$mensal)
				->order($data." asc");     
				$db->setQuery($query);  
				return $db->loadAssocList();				
			 }			 
public  function recupera_ultimos($tabela, $campo, $termo, $data){//recupera os ultimos 5 dados 		
				$db = JFactory::getDBO();  				
				$query  = $db->getQuery(true);
				$query->select('*')
				->from($tabela)
				->where($campo.' = "'.$termo.'"')
				->order($data.' desc');     
				$db->setQuery($query,0,5);  
				return $db->loadObject();
			}
	 
			 
			 
			 


public function FormularioFiltro($ft){


$form = '
<table width="800" border="0" align="center" cellpadding="4" cellspacing="2">
  <thead>
  </thead>
  <tr>
    <th align="center" class="alter"> <form action="" method="post">
      <select id="filtros" name="ft" style="width:150px;"
			onchange="if(options[selectedIndex].value == \'definirPeriodo\'){ 
			document.getElementById(\'data1\').style.display = \'\'; } else { submit(); }">
        <option value="" selected="selected">Selecione um filtro</option>
        <option value="ultimosLancamentos">&Uacute;ltimos lan&ccedil;amentos</option>
        <option value="hoje" >Hoje</option>
        <option value="mes" >Este m&ecirc;s</option>
        <option value="mesPassado">M&ecirc;s passado</option>
        <option value="todoPeriodo">Todo o per&iacute;odo</option>
        <option value="definirPeriodo">Definir per&iacute;odo</option>
      </select>
    </form>
      <div name="data1" id="data1" style="display:none; width:50px;">         <form action="" method="post" name="form2" id="form2" style="margin:0;padding:0 0 0;" onsubmit="return validar2(this)" >
          <input type="hidden" name="ft" value="definirPeriodo" />
          <table width="173" border="0" cellspacing="0" cellpadding="0" style="margin:5px">
            <tr>
              <td width="60" valign="middle"><input  value="" type="text" name="data_inicial" size="8" class="calendario" onkeyup="mascaraData(this);" style="font-size:10px;width:60px;margin:0;padding:0 0 0;"/></td>
              <td width="20" valign="middle" align="center">&agrave;</td>
              <td width="60" valign="middle"><input value="" type="text" name="data_final" size="8" class="calendario" onkeyup="mascaraData(this);" style="font-size:10px;width:60px;margin:0;padding:0 0 0;"/></td>
              <td width="33" valign="middle" align="right"><input type="submit" name="definirPeriodo" class="ok" value="Ok" style="font-size:10px;margin:0;padding:0 0 0;" onclick="return eMaior()" /></td>
            </tr>
          </table>
          <input type="text" name="data_atual" value="'.date('d/m/Y').'" style="display:none" />
        </form>
      </div>
      <span><?php echo especial($display); ?></span></th>
  </tr>
</table>';

return $form;
}
//----------------------------------------------------------------------------

public function FuncaoFiltro($ft){

//---------------------------------------------------------
switch($ft){
		case "definirPeriodo":
					$t = explode('/',$_POST["data_inicial"]);
					$d1 = $t[2]."-".$t[1]."-".$t[0];
					$a = explode('/',$_POST["data_final"]);
					$d2 = $a[2]."-".$a[1]."-".$a[0];
					
			$retorno = self::recupera_periodo("#__orgao_oficio","user_id",$user->id,"data",$d1,$d2)."|Período entre " . Biblioteca::cliente($d1) . " e " . Biblioteca::cliente($d2);
		break;
		case "mes":
			$retorno = self::recupera_mes("#__orgao_oficio","user_id",$user->id,"data")."|Este mês";
		break;
		case "hoje":
			$retorno = self::recupera_hoje("#__orgao_oficio","user_id",$user->id,"data")."|Hoje";
		break;
			case "mesPassado":
			$retorno = self::recupera_passado("#__orgao_oficio","user_id",$user->id,"data")."|M&ecirc;s Passado";
		break;
		case "ultimosLancamentos":
			$retorno = self::recupera_ultimos("#__orgao_oficio","user_id",$user->id,"data")."|&Uacute;ltimos Lan&ccedil;amentos";
		break;
		case "todoPeriodo":
			$display = "Todos os registros feitos";
			$link = "todoPeriodo";
		break;
		default:
			$hoj = date('Y-m');
			$filtrar = "AND data LIKE '$hoj-%'";
			$fil = "WHERE data LIKE '$hoj-%'";
			$display = "Este mês";
		break;
		
}

}*/

//***************************************Operando com Tabelas*****************************************//
public function minutas($dados,$tipo){//gera a lista dos dados das tabelas da minuta de acao

	$users  = JFactory::getUser();
	$users_groups = $users->groups;
	
	$termo = $dados;

	

for($x=0;$x<count($termo);$x++){


$tabela[] = "<tr id='id.orgao_minuta_".$tipo.".".$termo[$x]['Id'].".apagar'>
			<td style='display:none;'>".$termo[$x]['data']."</td>
			<td><span>".Biblioteca::cliente($termo[$x]['data'])."</span></td>
			<td><span id='reclamante_nome.orgao_minuta_termo.".$termo[$x]['Id'].".texto'>".$termo[$x]['reclamante_nome']."</span></td>
			<td><span id='reclamante_cpf.orgao_minuta_termo.".$termo[$x]['Id'].".texto' class='cpf'>".$termo[$x]['reclamante_cpf']."</span></td>
			<td><span id='reclamado_nome.orgao_minuta_termo.".$termo[$x]['Id'].".texto' class='texto'>".$termo[$x]['reclamado_nome']."</span></td>
			<td><span id='filhos.orgao_minuta_termo.".$termo[$x]['Id'].".texto' class='texto'>".$termo[$x]['filhos']."</span></td>
			<td>
			<?php if(reset($users_groups)==20){ ?><a href=\"".JURI::root()."ver-minuta?lay=minuta_lista&ver=minuta_".$tipo."&id=".$termo[$x]['Id']."\" target=\"_blank\" class=\"modal\" rel=\"{handler:'iframe', size: {x:650, y:200}, iframeOptions: {scrolling: 'no'}}\"><span class=\"lista_2\" title=\"Gerar Minuta de Ação\"></span></a>&nbsp;<?php } ?>
			<a href=\"#det_3_".$x."\" class=\"modal\" title=\"Ver detalhes\" rel=\"{size: {x: 750}}\"/><span class=\"icone-search\"></span></a>&nbsp;<span class=\"icone-apagar delete_linha\" title='Apagar'></span></td>
			<td style='display:none;'><div style=\"display:none;\" style='width:700px;'>
			<div id=\"det_3_".$x."\" style='width:100%;'>
			<div align=\"center\"><h3>QUALIFICAÇÃO</h3></div><br/>
			<span style=\"float:left;clear: both;\"><p>
			<span class=\"negrito\">Reclamante:</span><span>".$termo[$x]['reclamante_nome']."</span><br/>
			<span class=\"negrito\">Qualificação:</span><span>".$termo[$x]['reclamante_nacionalidade'].", ".$termo[$x]['reclamante_civil'].", ".$termo[$x]['reclamante_atividade']."</span><br/>
			<span class=\"negrito\">RG:</span><span>".$termo[$x]['reclamante_rg']."</span><br/>
			<span class=\"negrito\">CPF:</span><span>".$termo[$x]['reclamante_cpf']."</span><br/>
			<span class=\"negrito\">Endereço:</span><span>".$termo[$x]['reclamante_end'].", ".$termo[$x]['reclamante_bairro'].", ".$termo[$x]['reclamante_cidade']."/".$termo[$x]['reclamante_estado']."</span><br/>
			<span class=\"negrito\">Telefone:</span><span>".$termo[$x]['reclamante_telefone']."</span><br/>
			</p><p>
			<span class=\"negrito\">Reclamado:</span><span>".$termo[$x]['reclamado_nome']."</span><br/>
			<span class=\"negrito\">Atividade:</span><span>".$termo[$x]['reclamado_atividade']."</span><br/>
			<span class=\"negrito\">CPF:</span><span>".$termo[$x]['reclamado_cpf']."</span><br/>
			<span class=\"negrito\">Endereço:</span><span>".$termo[$x]['reclamado_end'].", ".$termo[$x]['reclamado_bairro'].", ".$termo[$x]['reclamado_cidade']."/".$termo[$x]['reclamado_estado']."</span><br/>
			<span class=\"negrito\">Telefone:</span><span>".$termo[$x]['reclamado_telefone']."</span><br/>
			</p><br />
			<p>
			<span class=\"negrito\">DOS FATOS</span>
			</p>
			<p><span>A Reclamante alega que o Reclamado não paga a pensão alimentícia e nem tem contribuído no sustendo do(s) menor(res).</span></p>


			<br/><br/>
			<p><span style=\"margin-top: 15px;\">Assinatura:_________________________________________________________</span></p>
			</span>
			<span style=\"position: relative; float: right; clear: both; margin-top: 5px; width: 98%; position:absolute; bottom:0; left:0; text-align: right;\">Registrado por ".self::quem($termo[$x]['user_id'])."</span>
			</div>
			</div></td>
			</tr>";

	}

	return Biblioteca::opcao($tabela,"");
}

public function expediente($dados){//gera a lista dos dados das tabelas da minuta de acao 
	for($x=0;$x<count($dados);$x++){
	
	
	                            
	                            echo "<tr id='id.orgao_expediente.".$dados[$x]['Id'].".apagar'>
	                                <td style='display:none;'>".$dados[$x]['numero']."</td>
	                                <td><span>".$dados[$x]['numero']."</span></td>
	                                <td><span id='procedimento.orgao_expediente.".$dados[$x]['Id'].".texto' class='texto'>".$dados[$x]['procedimento']."</span></td>
	                                <td><span id='destinatario.orgao_expediente.".$dados[$x]['Id'].".texto' class='texto'>".$dados[$x]['destinatario']."</span></td>
	                                <td><span id='responsavel.orgao_expediente.".$dados[$x]['Id'].".texto' class='texto'>".$dados[$x]['responsavel']."</span></td>
	                                <td><span id='criado.orgao_expediente.".$dados[$x]['Id'].".texto' class='data'>".Biblioteca::cliente($dados[$x]['criado'])."</span></td>
	                                <td><span id='assunto.orgao_expediente.".$dados[$x]['Id'].".texto' class='texto'>".Biblioteca::limita_char(ucwords($dados[$x]['assunto']))."</span></td>
	                                <td><span id='obs.orgao_expediente.".$dados[$x]['Id'].".texto' class='area'>".Biblioteca::limita_char(ucwords($dados[$x]['obs']))."</span></td>
	                                <td><a href=\"#det_5_".$x."\" class=\"modal\" title=\"Detalhes\" alt='Ver detalhes'/>
											<span class=\"icone-search\"></span></a>&nbsp;<span class=\"icone-apagar delete_linha\" title='Apagar'></span>
									</td>
					<td style='display:none;'>
					<div style=\"display:none;\">
												<div id=\"det_5_".$x."\">
													<div align=\"center\"><h3>DETALHES</h3></div>
													<span style=\"float:left; font-weight: bold;\">Procedimento Administrativo</span>
													<span style=\"position: relative; float: left; clear: both; margin-top: 20px;\">Criado em: ".Biblioteca::cliente($dados[$x]['criado'])."</span>
													<span style=\"position: relative; float: left; clear: both; margin-top: 20px;\">Procedimento nº: ".$dados[$x]['procedimento']."</span>
													<span style=\"position: relative; float: left; clear: both; margin-top: 5px;\">Envolvido: ".$dados[$x]['destinatario']."</span>
													<span style=\"position: relative; float: left; clear: both; margin-top: 5px;\">Criado por: ".$dados[$x]['responsavel']."</span>
													<span style=\"position: relative; float: left; clear: both; margin-top: 5px;\">Assunto: ".Biblioteca::limita_char(ucwords($dados[$x]['assunto']))."</span>
													<div style=\"position:relative; clear:both; padding: 20px 0px 0px;\"><hr  /></div>
													<span style=\"position: relative; float: left; clear: both; margin-top: 5px; width: 100%;\"><div align=\"center\" style=\"margin-bottom: 20px;\"><h4>Observações</h4></div>".$dados[$x]['obs']."</span>
													<span style=\"position: relative; float: right; clear: both; margin-top: 5px; width: 98%; position:absolute; bottom:0; left:0; text-align: right;\">Registrado por ".self::quem($dados[$x]['user_id'])."</span>
												</div>
											</div>
											</td>
									
	                              </tr>";
	                              
	                            }  
	return Biblioteca::opcao($tabela,"");
	}

//*******************************FILTRO POR DATA*******************************************//
public function ano($ano){
		
		return Biblioteca::caso_vazio($ano,date('Y'));
		
	}

public function formano($tabela,$campo,$ano,$url){
	//**************************Gera a planilha de anos*******************//
		$doc = JFactory::getDocument();
		$user =& JFactory::getUser();
		$ano_ = Modulos::busca_basica_array_opcao("SELECT YEAR($campo) as 'ano' FROM $tabela GROUP BY YEAR($campo) DESC");//carrega a lista de anos disponiveis
		$ano_opcoes = Biblioteca::apenas(array(self::ano($ano)),Biblioteca::multi_uma($ano_));//retira o ano selecionado da array
		foreach($ano_opcoes as $ar):	$ano_ref[] = "<option value='".$ar."'>".$ar."</option>"; endforeach;	//gera a lista dos anos não selecionados
		$selecionado = "<option value='".self::ano($ano)."' selected='selected'>".self::ano($ano)."</option>";	
		$form = '<form action="'.$url.'" method="post" id="seleciona_ano" target=""><select onchange="this.form.submit();" name="ano">'.$selecionado."".Biblioteca::opcao($ano_ref,'').'</select></form>';
        return $form;
        }
}

?>