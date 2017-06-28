<?php 
/*
Insere menu padão do template***************************************************
Este codigo insere um menu padão do template para acesso ao painel inicial e,
caso o usuario crie algum menu, ele mostrara abaixo do padrão
*/
	wp_nav_menu( array( 
		'fallback_cb' => 'menu_padrao_dashboard', 
		'container' => false, 
		'menu_id' => 'menu-sidebar', 
		'menu_class'=>'sidebar-menu', 
		'theme_location'=>'dashboard'
	) );
function get_all_wordpress_menus(){
     return get_terms( 'nav_menu', array( 'hide_empty' => true ) );
}


function menu_padrao_dashboard() {
	  $menu_dashboard = '<ul id ="menu-sidebar" class="sidebar-menu"><li class="header"></li>';
	  $menu_dashboard .=	'<li><a href="'.home_url().'"><i class="fa fa-tachometer"></i><span>Painel</span></a></li>';
	  $menu_dashboard .= '</ul>';
	echo $menu_dashboard;
}
//Menu criado pelo usuário*************************************************************************
function menu_todos(){
		$todos_menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		
			$menu = wp_get_nav_menu_object($locations[$menu_name ]); 
			$menu_items = wp_get_nav_menu_items($menu->term_id);
			
			foreach($todos_menus as $value):
				$menu_list = '<ul id ="menu-sidebar" class="sidebar-menu"><li class="header"></li>';
					$menu_items = wp_get_nav_menu_items($value->term_id);
						if(isset($value->term_id))://**************************verifica se o usuario criou algum menu			 $menu_list .= '<li class="header"></li>';
								foreach ((array) $menu_items as $key => $menu_item ):
									/***insere icone padrão caso não seaja incluido um pelo usuário************/
									$icone = get_post_meta($menu_item->ID, '_menu_item_classe_icone', true);
										if(isset($icone)):
											$menu_icone = get_post_meta($menu_item->ID, '_menu_item_classe_icone', true); 
										else:
											$menu_icone = "fa fa-chevron-circle-right";
										endif;
									$title = $menu_item->title;
									$url = $menu_item->url;
									$menu_item->title;
									$menu_list .= '<li><a href="' . $url . '"><i class="'.$menu_icone.'"></i><span>' . $title . '</span></a></li>';
								endforeach;	//foreach item menu

						else:
							$menu_list .= '<li><a href="#" ><i class="fa fa-exclamation-triangle"></i><span>Menu "' . $menu_name . '" não foi definido pelo usuário.</span></a></li>';
						endif;//if verifica
				$menu_list .= "</ul>";
			endforeach;//foreach todos_menus
			

	echo $menu_list;
		
}//<----fim da função menu_padrao_dashboard()	

//$menu = wp_get_nav_menu_object($locations['menu_lateral']);

/*

echo '<pre>';
var_dump($menu);
var_dump($menu_items);
var_dump($todos_menus);
echo '</pre>';


*/

			
?>  