<?php
/*
if(!is_user_logged_in() && !is_admin()):
	wp_redirect (get_permalink(get_page_by_title( 'Login' )));
	exit;
else:
	wp_redirect (home_url());
	exit;
endif;*/
/* 
__FILE__Funções do tema Dashboard
*/

/*************************************Funções do Menu **********************************************/
include ('inc/funcoes_dashboard.php');//funções de uso comum do sistema Nova Jerusalem
include ('inc/funcao_campo_menu.php');//função que insere campo extra no menu avançado
/*
Cria um campo para a instalação de menus ******************************************************
*/

function meus_menus() {//funçaõ para criar campo de menu para o template

	$locations = array(
		'menu_lateral' => __( 'Dashboard', 'Dashboard' ),
	);
	register_nav_menus( $locations );

}
add_action( 'init', 'meus_menus' );

/*
Cria um menu para ser instalado*************************************************************
*/

$menu_name = 'Dashboard';
$menu_exists = wp_get_nav_menu_object( $menu_name );

// If it doesn't exist, let's create it.
if( !$menu_exists){
    $menu_id = wp_create_nav_menu($menu_name);

}
//********************************************************************************************************//
