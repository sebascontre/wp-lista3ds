<?php
/**
* Plugin Name: Lista 3DS
* Plugin URI: https://www.3dslibre.com/
* Description: Lista Visual para juegos de 3DSLibre
* Version: 0.3
* Author: SebasContre
* Author URI: https://seba.im/
* License: FREE FOR ALL.
*/

function lista3ds() {
	$lista = null; $html = '';
	$lista = new WP_Query(array(
		'post_type' => 'lista3ds',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'caller_get_posts'=> 1,
		'orderby'=> 'title',
		'order' => 'ASC' 
	));
	
	wp_enqueue_style('bulma-columns', plugin_dir_url( __FILE__ ) . 'columns.css');
	
	if($lista->have_posts()) {
		$html .= '<div class="columns is-mobile is-multiline">';
		$cache = ""; $aux = "";
		
		while($lista->have_posts()) {
			$lista->the_post();
			$cache  = '<div class="column is-half-mobile is-one-third-tablet is-one-quarter-desktop">';
			$cache .= get_the_post_thumbnail(get_the_ID(), 'full', array('class' => 'img-fluid'));
			$cache .= get_the_title(get_the_ID());
			$cache .= '<br/>';
			$cache .= get_the_date('Y').' - <strong>'.get_post_meta(get_the_ID(), '3ds_size', true).'</strong>';
			$cache .= '</div>';
			
			if (get_the_title(get_the_ID()) === "Pokémon Moon") {
				$aux = $cache;
				$cache = "";
			} else if (get_the_title(get_the_ID()) === "Pokémon Sun") {
				$cache .= $aux;
			}
			
			$html .= $cache;
		}
		
		$html .= '</div>';
	}
	
	return $html;
}

function register_lista3ds() {
	
	$args = array(
		'label' => "Lista 3DS",
		'hierarchical' => true,
		'description' => 'Redirect system with Open Graph compatibility',
		'supports' => array('title','custom-fields','thumbnail'),
		'taxonomies' => array('post_tag'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 25,
		'menu_icon' => 'dashicons-album',
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'query_var' => false,
		'can_export' => true,
		'capability_type' => 'post',
		'rewrite' => false
	);
 
	register_post_type('lista3ds', $args );
}


function lista3ds_table_head( $defaults ) {
	$defaults['date'] = "Año";
	$defaults['3ds_size'] = 'Tamaño';
	$defaults['3ds_image'] = 'Cover';
	return $defaults;
}

function lista3ds_table_content( $column_name, $post_id ) {
	if ($column_name == 'date') {
		the_date("Y", "", "", true);
	}
	
	if ($column_name == '3ds_size') {
		echo "<strong>", get_post_meta($post_id, '3ds_size', true),"</strong>";
	}

	if ($column_name == '3ds_image') {
		echo "<img style=\"height: 48px\" src=\"", the_post_thumbnail_url(), "\" />";
	}

}

add_shortcode('lista3ds', 'lista3ds');
add_action('init', 'register_lista3ds');
add_filter('manage_lista3ds_posts_columns', 'lista3ds_table_head');
add_action('manage_lista3ds_posts_custom_column', 'lista3ds_table_content', 10, 2 );
add_theme_support('post-thumbnails');

?>
