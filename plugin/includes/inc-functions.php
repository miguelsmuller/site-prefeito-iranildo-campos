<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Function description
 */
function get_actual_url() {
    global $wp;
    $html = home_url(add_query_arg(array(),$wp->request));
    //$html = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

    return ($html);
}


/**
 * Function description
 */
if ( ! function_exists('__log')) {
   function __log ( $log )  {
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
}


/**
 * Function description
 */
function get_plugin_path() {
	return plugin_dir_path( __FILE__ ) . '../';
}


/**
 * Function description
 */
function get_plugin_url() {
	return plugins_url() . '/devim-core-plugin';
}


/**
 * Function description
 */
function get_full_month( $mes ) {

    switch (intval($mes)){
        case 1:
        	$return = "Janeiro"; break;
        case 2:
        	$return = "Fevereiro"; break;
        case 3:
        	$return = "Março"; break;
        case 4:
        	$return = "Abril"; break;
        case 5:
        	$return = "Maio"; break;
        case 6:
        	$return = "Junho"; break;
        case 7:
        	$return = "Julho"; break;
        case 8:
        	$return = "Agosto"; break;
        case 9:
        	$return = "Setembro"; break;
        case 10:
        	$return = "Outubro"; break;
        case 11:
        	$return = "Novembro"; break;
        case 12:
        	$return = "Dezembro"; break;
    }
    return ($return);
}


/**
 * Function description
 */
function copyright() {
    global $wpdb;
    $copyright_dates = $wpdb->get_results("
        SELECT
        YEAR(min(post_date_gmt)) AS firstdate,
        YEAR(max(post_date_gmt)) AS lastdate
        FROM
        $wpdb->posts
        WHERE
        post_status = 'publish'
    ");

    $output = '';
    if($copyright_dates) {
        $copyright = "&copy; " . $copyright_dates[0]->firstdate;
        if($copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate) {
            $copyright .= '-' . $copyright_dates[0]->lastdate;
        }
        $output = $copyright;
    }
    return $output;
}


/**
 * Function description
 */
function is_list_screen( $post_types = '' ) {
    if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        return false;
    }

    global $pagenow;
    if ( !$post_types && $pagenow === 'edit.php' ) {
        return true;
    }
    elseif ( $post_types ) {
        $current_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : 'post';
        foreach ( $post_types as $post_type ) {
            if ( $pagenow === 'edit.php' && $post_type === sanitize_key( $current_type ) ) {
                return true;
            }
        }
    }
    return false;
}


/**
 * Function description
 */
function tags_popular($qt = '10', $intv = '60'){
    global $wpdb;
    $term_ids = $wpdb->get_col("
    SELECT term_id FROM $wpdb->term_taxonomy
    INNER JOIN $wpdb->term_relationships ON $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
    INNER JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
    WHERE DATE_SUB(CURDATE(), INTERVAL $intv DAY) <= $wpdb->posts.post_date");

    if(count($term_ids) > 0){
        $tags = get_tags(array(
            'orderby' => 'count',
            'order'   => 'DESC',
            'number'  => $qt,
            'include' => $term_ids,
        ));
        return $tags;
    }else{
        return false;
    }
}

/**
 * Function description
 */
function get_facebook_share( $post_id ) {
	if ( ! ( $fb_share_count = get_transient( 'facebook_share_count' . $post_id ) ) ) {

		$fql = sprintf(
			'SELECT url, share_count, like_count, comment_count, total_count FROM link_stat WHERE url = "%s"',
			get_permalink( $post_id )
		);

		$fb_url = 'https://api.facebook.com/method/fql.query?format=json&query=' . urlencode( $fql );

		$fb_respose = wp_remote_retrieve_body( wp_remote_get( $fb_url ) );

		if ( is_wp_error( $fb_respose ) )
			return 'error';

		$fb_respose_body = json_decode( $fb_respose, true );

		$fb_share_count = absint( $fb_respose_body[0]['total_count'] );

		set_transient( 'facebook_share_count' . $post_id, absint( $fb_share_count ), 30 * MINUTE_IN_SECONDS );

	}
	return absint( $fb_share_count );
}


/**
 * Function description
 */
function get_twitter_share( $post_id ) {
  	if ( ! ( $twitter_share_count = get_transient( 'twitter_share_count' . $post_id ) ) ) {
		$twitter_url = 'https://cdn.api.twitter.com/1/urls/count.json?url=' . urlencode( get_permalink( $post_id ) );

		$twitter_response = wp_remote_retrieve_body( wp_remote_get( $twitter_url ) );

		if ( is_wp_error( $twitter_response ) )
			return 'error';

		$twitter_respose_body = json_decode( $twitter_response, true );

		$twitter_share_count = absint( $twitter_respose_body['count'] );

		set_transient( 'twitter_share_count' . $post_id, absint( $twitter_share_count ), 30 * MINUTE_IN_SECONDS );

	}

	return absint( $twitter_share_count );

}


/**
 * Function description
 */
function get_shortened_url( $post_id ) {
	if ( ! ( $shortened_url = get_transient( 'shortened_url' . $post_id ) ) ) {

		$key = get_field('api_key_google', 'option');

		$google_url = sprintf(
			'https://www.googleapis.com/urlshortener/v1/url?key=%s',
			apply_filters( 'google_api_shortener', $key )
		);

		$google_args['body'] = json_encode( array( 'longUrl' => get_permalink( $post_id ) ) );
		$google_args['headers'] = array('content-type' => 'application/json');

		$google_response = wp_remote_retrieve_body( wp_remote_post( $google_url, $google_args ) );

		if ( is_wp_error( $google_response ) )
			return 'error';

		$google_respose_body = json_decode( $google_response, true );

		$shortened_url = $google_respose_body['id'];

		set_transient( 'shortened_url' . $post_id, $shortened_url, 30 * MINUTE_IN_SECONDS );

	}

	return $shortened_url;
}



/**
 * Function description
 */
function objectsIntoArray($arrObjData, $arrSkipIndices = array()){
    $arrData = array();

    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }

    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}
