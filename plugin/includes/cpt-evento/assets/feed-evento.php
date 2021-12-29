<?php
// - standalone json feed -

header('Content-Type:application/json');

require_once(ABSPATH . 'wp-load.php');
// - grab wp load, wherever it's hiding -
/*if(file_exists('../../../../../wp-load.php')) :
    include '../../../../../wp-load.php';
else:
    include '../../../../../wp-load.php';
endif;*/

$jsonevents = array();

$events = new WP_Query( array(
    'post_type' => 'evento',
    'posts_per_page' => -1,
    'no_found_rows' => true,
    'meta_key'=> 'data_inicio',
    'orderby'=> 'meta_value_num',
    'order'=> 'ASC'
) );
global $post;

while ( $events->have_posts() ) : $events->the_post();

	$category = get_the_terms($post->ID, 'evento-categoria');
	if($category != false){
		foreach ( $category as $term ) {
			$t_id = $term->term_id;
		}
		$taxonomy_meta = get_option( "taxonomy_$t_id" );
	}else{
		$taxonomy_meta['cor_categoria_evento'] = '#000';
	}

	$sd = date("d/m/Y", get_post_meta($post->ID, "data_inicio", true));
	$ed = date("d/m/Y", get_post_meta($post->ID, "data_termino", true));

	// - grab gmt for start -
	$sd = explode("/", $sd);
	$gmts = $sd[2] . '-' . $sd[1] . '-' . $sd[0] . ' 00:00:00';
	$gmts = get_gmt_from_date($gmts); // this function requires Y-m-d H:i:s
	$gmts = strtotime($gmts);

	// - grab gmt for end -
	$ed = explode("/", $ed);
	$gmte = $ed[2] . '-' . $ed[1] . '-' . $ed[0] . ' 00:00:00';
	$gmte = get_gmt_from_date($gmte); // this function requires Y-m-d H:i:s
	$gmte = strtotime($gmte);

	// - set to ISO 8601 date format -
	$stime = date('c', $gmts);
	$etime = date('c', $gmte);

	// - json items -
	$jsonevents[]= array(
		'color' => $taxonomy_meta['cor_categoria_evento'],
		'borderColor' => "#494949",
		'textColor' => "white",
		'title' => get_the_title(),
		'allDay' => true, // <- true by default with FullCalendar
		'start' => $stime,
		'end' => $etime,
		'url' => get_permalink($post->ID)
		);

endwhile;

// - fire away -
echo json_encode($jsonevents);