<?php 
/*
Insere menu padão do template***************************************************
Este codigo insere um menu padão do template para acesso ao painel inicial e,
caso o usuario crie algum menu, ele mostrara abaixo do padrão
*/
$user_menus = array();


//*******************************************************************************************************/
	wp_nav_menu( array( 
		'fallback_cb' => 'menu_padrao_dashboard', 
		'container' => false, 
		'menu_id' => 'menu-sidebar', 
		'menu_class'=>'sidebar-menu', 
		'theme_location'=>'dashboard'
	) );

function multi_uma($array){//transforma uma array multi em array single
        $return = array();

        array_walk_recursive($array, function($x) use (&$return) { $return[] = $x; });

        return $return;
    }	

function opcao($opcao,$separador=''){//converte array em objeto
	$campos = "";
	$adiciona = $separador;
	$mais = 0;
		if(isset($opcao)):
			foreach($opcao AS $valor):
				$mais++;
					if($mais < count($opcao)):
						$campos.=$valor.$adiciona;
					else:
						$campos.=$valor;
					endif;
			endforeach;
		else:
			$campos = " ";
		endif;
return $campos;
}


function menu_padrao_dashboard() {
	  $menu_dashboard = '<ul id ="menu-sidebar" class="sidebar-menu"><li class="header">Início</li>';
	  $menu_dashboard .=	'<li><a href="'.home_url().'"><i class="fa fa-tachometer"></i><span>Painel</span></a></li>';
	  $menu_dashboard .= '</ul>';
	echo $menu_dashboard;
}
/**
***Menu criado pelo usuário*- lista todos os meus existentes
***Para selecionar um menu específico, inclua como array o nome do menu criado, ex: echo menu_todos(array('meu_menu'));
***Suporta na array quantos menus quiser.
***Caso deixe em branco, carregará todos os menus.
**/
function menu_todos($menu=''){
		$todos_menus = get_terms( 'nav_menu', array( 'hide_empty' => true, 'name' => $menu ) );
			$value = '';
			foreach($todos_menus as $value):				
				$menu_list[] = '<ul id ="menu-sidebar-'.$value->name.'" class="sidebar-menu"><li class="header">'.$value->name.'</li>';
					$menu_items = multi_uma(wp_get_nav_menu_items($value->term_id));
						if(isset($value->term_id))://**************************verifica se o usuario criou algum menu			 $menu_list .= '<li class="header"></li>';
							$li = '';
								foreach ($menu_items as $key=>$menu_item ):
									/***insere icone padrão caso não seaja incluido um pelo usuário************/
									$icone = get_post_meta($menu_item->ID, '_menu_item_classe_icone', true);
										if(isset($icone)):
											$menu_icone = get_post_meta($menu_item->ID, '_menu_item_classe_icone', true); 
										else:
											$menu_icone = "fa fa-chevron-circle-right";
										endif;
									$title = $menu_item->title;
									$url = $menu_item->url;
									$li[] = '<li><a href="' . $url . '"><i class="'.$menu_icone.'"></i><span>' . $title . '</span></a></li>';
								endforeach;	//foreach item menu

						else:
							$menu_list .= '<li><a href="#" ><i class="fa fa-exclamation-triangle"></i><span>Menu "' . $menu_name . '" não foi definido pelo usuário.</span></a></li>';
						endif;//if verifica
				$menu_list[] .= opcao($li)."</ul>";
			endforeach;//foreach todos_menus
	return opcao($menu_list);	
	
		
}//<----fim da função menu_padrao_dashboard()	

echo menu_todos($user_menus);


			
?>  