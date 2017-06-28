  <?php
//funcao que exibe atributos do menu ativo
function my_get_menu_item_name( $loc ) {
    global $post;
    $locs = get_nav_menu_locations();
    $menu = wp_get_nav_menu_object( $locs[$loc] );
    if($menu) {
        $items = wp_get_nav_menu_items($menu->term_id);
        foreach ($items as $k => $v) {
            // Check if this menu item links to the current page
            if ($items[$k]->object_id == $post->ID) {
				$icone = get_post_meta($items[$k]->ID, '_menu_item_classe_icone', true);
                $name = $items[$k]->ID;
                break;
            }
        }
    }
    return $icone;//neste caso, mostra o icone personalizado
}



?><!-- Content Header (Page header) -->
    <section class="content-header">
        <?php  the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark"><i class="'.my_get_menu_item_name('menu_lateral').'"></i>', esc_url( get_permalink())), '</a></h1><small></small>' ); ?>
    </section>