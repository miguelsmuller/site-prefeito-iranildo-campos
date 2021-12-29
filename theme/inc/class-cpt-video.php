<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_CPT_Video
{
	/**
	 * Class construct
	 */
	public function __construct() {
		// Actions
		add_action( 'init', array( &$this, 'init_post_type' ), 100 );
		add_action( 'admin_head', array( &$this, 'admin_head' ));

		//Filters
		add_filter( 'post_updated_messages', array( &$this, 'post_updated_messages' ));
		add_filter( 'enter_title_here', array( &$this, 'enter_title_here'));
		add_filter( 'post_row_actions', array( &$this, 'post_row_actions'), 10, 2);

		// Cria campos para o post type
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_box'));
		add_action( 'save_post', array( &$this, 'save_post'));

		// Mudança das colunas do WP-ADMIN
        add_filter( 'manage_edit-video_columns', array( &$this, 'create_custom_column' ));
        add_action( 'manage_video_posts_custom_column', array( &$this, 'manage_custom_column' ), 10, 2);
	}


	/**
	 * Cria o tipo de post video
	 */
	function init_post_type() {

		$resposta = register_post_type( 'video',
			array(
				'labels' => array(
					'name'               => 'Videos',
					'singular_name'      => 'Video',
					'add_new'            => 'Adicionar video',
					'add_new_item'       => 'Adicionar video',
					'edit_item'          => 'Editar video',
					'new_item'           => 'Novo video',
					'view_item'          => 'Ver video',
					'search_items'       => 'Buscar video',
					'not_found'          => 'Nenhuma video encontrado',
					'not_found_in_trash' => 'Nenhuma video encontrado na lixeira',
					'parent'             => 'Videos',
					'menu_name'          => 'Videos'
				),

				'hierarchical'    => false,
				'public'          => false,
				'query_var'       => true,
				'supports'        => array( 'title' ),
				'has_archive'     => false,
				'capability_type' => 'post',
				'menu_icon'       => 'dashicons-format-video',
				'show_ui'         => true,
				'show_in_menu'    => true,
				'rewrite'         => array('slug' => 'videos', 'with_front' => false),
			)
		);
	}


	/**
     * Inclui código CSS no painel administrativo
     */
    function admin_head() {
        global $post;
        if ( isset($post->post_type) && $post->post_type == 'video' ){
        ?>
            <style type="text/css" media="screen">
                .column-video_thumbnail{
                    width: 200px;
                }
            </style>
        <?php
        }
        ?>
    <?php
    }


	/**
	 * Personaliza as mensagens do processo de salvamento
	 */
	function post_updated_messages( $messages ) {
		global $post, $post_ID;
		$link = esc_url( get_permalink($post_ID));
		$link_preview = esc_url( add_query_arg('preview', 'true', get_permalink($post_ID)));
		$date = date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ));

		$messages['video'] = array(
			1  => sprintf('<strong>Video</strong> atualizada com sucesso - <a href="%s">Ver Video</a>', $link),
			6  => sprintf('<strong>Video</strong> publicada com sucesso - <a href="%s">Ver Video</a>', $link),
			9  => sprintf('<strong>Video</strong> agendanda para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver Video</a>',$date ,$link),
			10 => sprintf('Rascunho do <strong>Video</strong> atualizada. <a target="_blank" href="%s">Ver Video</a>', $link_preview),
		);
		return $messages;
	}


	/**
	* Altera o placeholder do titulo
	*/
	function enter_title_here( $title ){
		$screen = get_current_screen();
		if  ( $screen->post_type == 'video' ) {
			$title = 'Entre com o código do video aqui';
		}
		return $title;
	}


	/**
	*
	*/
	function post_row_actions($actions, $post){
	    if ($post->post_type =="video"){
	        $actions['in_google'] = '<strong><a href="http://www.google.com/?q='.get_permalink($post->ID).'">Ver no Youtube</a></strong>';
	    }
	    return $actions;
	}



	/**
	 * Cria um meta box no formulário do post type
	 */
	function add_meta_box() {
		add_meta_box(
			'metaBoxVideo',// $id
			'Informações do Video',// $title
			array( &$this, 'show_box'),
			'video',// $post_type
			'normal',// $context
			'high'// $priority
		);
	}

	function show_box(){
		global $pagenow;
		global $post;

		wp_nonce_field('saveExtraFields','securityField');
		?>
		<?php
			$post_meta   = get_post_meta($post->ID);
			$title       = isset($post_meta['video_title'][0]) ? $post_meta['video_title'][0] : '';
			$description = isset($post_meta['video_description'][0]) ? $post_meta['video_description'][0] : '';
			$thumbnail   = isset($post_meta['video_thumbnail'][0]) ? $post_meta['video_thumbnail'][0] : '';
		?>
		<div id="tabs-info">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label>Titulo:</label></th>
						<td>
							<?php echo $title;  ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label>Descrição:</label></th>
						<td>
							<?php echo $description;  ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label>Imagem:</label></th>
						<td>
							<img src="<?php echo $thumbnail; ?>" alt="<?php echo $title;  ?>" style="width: 45%;">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label>Link:</label></th>
						<td>
							<a href="'. $video_url .'">Ver o vídeo no YouTube</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php
	}


	/**
	 * Procedimento para salvar os dados extras
	 */
	function save_post( $post_id ) {
		// Verifica o tipo do post
		if (get_post_type($post_id) != 'video') return;
		// Verificar se a publicação é salva automaticamente
		if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		// Verificar o valor nonce criado no forma
		if( !isset( $_POST['securityField'] ) || !wp_verify_nonce( $_POST['securityField'], 'saveExtraFields' ) ) return;
		// Verifica se o usuário atual tem permissão para salvar
		if( !current_user_can('edit_post', $post_id) ) return;

		$video_code = get_the_title( $post_id );

    $key = get_field('api_key_google', 'option');

		$youtube_url = sprintf(
			'https://www.googleapis.com/youtube/v3/videos?part=id,snippet,contentDetails,player&id=%s&key=%s',
			$video_code,
			apply_filters( 'google_api_key', $key )
		);

		$youtube_response = wp_remote_retrieve_body( wp_remote_get( $youtube_url ) );

		if ( is_wp_error( $youtube_response ) )
			return 'error';

		$youtube_respose_body = json_decode( $youtube_response, true );

		if (isset($youtube_respose_body['items'])) :
			update_post_meta($post_id, "video_title", $youtube_respose_body['items'][0]['snippet']['title']);
			update_post_meta($post_id, "video_description", $youtube_respose_body['items'][0]['snippet']['description']);

			if (isset($youtube_respose_body['items'][0]['snippet']['thumbnails']['maxres']['url'])) {
				$video_thumbnail = $youtube_respose_body['items'][0]['snippet']['thumbnails']['maxres']['url'];
			}else{
				$video_thumbnail = $youtube_respose_body['items'][0]['snippet']['thumbnails']['standard']['url'];
			}
			update_post_meta($post_id, "video_thumbnail", $video_thumbnail);
		endif;
	}


	/**
     * Cria uma coluna na lista de slides do painel administrativo
     */
    function create_custom_column( $columns ) {
        global $post;

        $new = array();
        foreach($columns as $key => $title) {
            if ( $key=='title' )
                $new['video_thumbnail'] = 'Thumbnail';

            if ( $key=='date' ){
                $new['video_title'] = 'Título do Vídeo';
            }

            $new[$key] = $title;
        }
        $new['title'] = 'Código do Vídeo';
    return $new;
    }


    /**
     * Inseri valor na coluna especifica da listagem do painel administrativo
     */
    function manage_custom_column( $column, $post_id ) {
		$post_meta       = get_post_meta($post_id);
		$video_thumbnail = $post_meta['video_thumbnail'][0];
		$video_title     = $post_meta['video_title'][0];

		$video_url = site_url() .'/wp-admin/post.php?post='.$post_id.'&action=edit';

        switch( $column ) {
            case 'video_thumbnail' :
				echo '<a href="'. $video_url .'"><img width="100%" src="'. $video_thumbnail .'" /></a>';
                break;

            case 'video_title' :
				echo '<a href="'. $video_url .'">'. $video_title .'</a>';
                break;
        }
    }
}
new Class_CPT_Video();



