<?php
/**
* Plugin Name: Lista 3DS
* Plugin URI: https://www.3dslibre.com/
* Description: Lista Visual para juegos de 3DSLibre
* Version: 0.2
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
	
	if($lista->have_posts()) {
		$html .= '<div class="container"><div class="row">';
		$i = 0;
		
		while($lista->have_posts()) {
			 $lista->the_post(); $i++;
			 $html .= '<div class="col-xs-6 col-md-3">';
			 $html .= get_the_post_thumbnail(get_the_ID(), 'full', array('class' => 'img-fluid'));
			 $html .= get_the_title(get_the_ID());
			 $html .= '<br/>';
			 $html .= get_the_date('Y').' - <strong>'.get_post_meta(get_the_ID(), '3ds_size', true).'</strong>';
			 $html .= '</div>';
			 
			 if (($i % 2 == 0) && ($i % 4 != 0)) $html .= '<div class="clearfix visible-xs"></div>';
			 if (($i % 2 == 0) && ($i % 4 == 0)) $html .= '<div class="clearfix visible"></div>';
		}
		
		$html .= '</div></div>';
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

?>