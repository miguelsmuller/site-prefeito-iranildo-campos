<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Shortcode
{
    /**
     * Construtor da Classe
     */
    public function __construct() {
        // Actions
        add_action('init', array( &$this, 'init'));

        // Shortcodes
        add_shortcode('nota-restrita', array( &$this, 'nota_restrita'));
        add_shortcode('label', array( &$this, 'label'));
        add_shortcode('label-success', array( &$this, 'label_success'));
        add_shortcode('label-warning', array( &$this, 'label_warning'));
        add_shortcode('label-danger', array( &$this, 'label_danger'));
        add_shortcode('label-info', array( &$this, 'label_info'));

        for ($i = 1; $i <= 11; $i++) {
            add_shortcode('col'.$i.'-open', array( &$this, 'col_'.$i.'_open'));
            add_shortcode('col'.$i,  array( &$this, 'col_'.$i));
            add_shortcode('col'.$i.'-close',  array( &$this, 'col_'.$i.'_close'));
        }
    }


    /**
     * Permite alterar os butões do editor de conteudo
     */
    function init() {
        if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )  {
            add_filter('mce_external_plugins', array( &$this, 'add_plugin'));
            add_filter('mce_buttons', array( &$this, 'register_button'), 0);
        }
    }
    function add_plugin($plugin_array) {
        $plugin_array['speed'] = get_plugin_url() . '/includes/shortcode/assets/customcodes.js';
        return $plugin_array;
    }
    function register_button($buttons) {
        array_push($buttons, "nota-restrita", "embed", "label-default", "label-success", "label-warning", "label-danger", "label-info");
        return $buttons;
    }



    /**
     * Shortcode para conteúdo restrito
     */
    function nota_restrita( $atts, $content = null ) {
        if (is_user_logged_in())
            return '<div class="well well-sm well-protect"><h4>Nota Restrita</h4>'.$content.'</div>';
        return '';
    }


    /**
     * Shortcode de labels
     */
    function label( $atts, $content="" ) {
         return "<span class='label label-default'>$content</span>";
    }
    function label_success( $atts, $content="" ) {
         return "<span class='label label-success'>$content</span>";
    }
    function label_warning( $atts, $content="" ) {
         return "<span class='label label-warning'>$content</span>";
    }
    function label_danger( $atts, $content="" ) {
         return "<span class='label label-danger'>$content</span>";
    }
    function label_info( $atts, $content="" ) {
         return "<span class='label label-info'>$content</span>";
    }


    /**
     * Shortcode para sistema de grid
     */
    function col_1_open( $atts, $content = null ) {
        return '<div class="row"><div class="col-md-1">' . do_shortcode($content) . '</div>';
    }
    function col_1( $atts, $content = null ) {
        return '<div class="col-md-1">' . do_shortcode($content) . '</div>';
    }
    function col_1_close( $atts, $content = null ) {
        return '<div class="col-md-1">' . do_shortcode($content) . '</div></div>';
    }


    function col_2_open( $atts, $content = null ) {
       return '<div class="row"><div class="col-md-2">' . do_shortcode($content) . '</div>';
    }
    function col_2( $atts, $content = null ) {
       return '<div class="col-md-2">' . do_shortcode($content) . '</div>';
    }
    function col_2_close( $atts, $content = null ) {
       return '<div class="col-md-2">' . do_shortcode($content) . '</div></div>';
    }


    function col_3_open( $atts, $content = null ) {
       return '<div class="row"><div class="col-md-3">' . do_shortcode($content) . '</div>';
    }
    function col_3( $atts, $content = null ) {
       return '<div class="col-md-3">' . do_shortcode($content) . '</div>';
    }
    function col_3_close( $atts, $content = null ) {
       return '<div class="col-md-3">' . do_shortcode($content) . '</div></div>';
    }


    function col_4_open( $atts, $content = null ) {
       return '<div class="row"><div class="col-md-4">' . do_shortcode($content) . '</div>';
    }
    function col_4( $atts, $content = null ) {
       return '<div class="col-md-4">' . do_shortcode($content) . '</div>';
    }
    function col_4_close( $atts, $content = null ) {
       return '<div class="col-md-4">' . do_shortcode($content) . '</div></div>';
    }


    function col_5_open( $atts, $content = null ) {
       return '<div class="row"><div class="col-md-5">' . do_shortcode($content) . '</div>';
    }
    function col_5( $atts, $content = null ) {
       return '<div class="col-md-5">' . do_shortcode($content) . '</div>';
    }
    function col_5_close( $atts, $content = null ) {
       return '<div class="col-md-5">' . do_shortcode($content) . '</div></div>';
    }


    function col_6_open( $atts, $content = null ) {
       return '<div class="row"><div class="col-md-6">' . do_shortcode($content) . '</div>';
    }
    function col_6( $atts, $content = null ) {
       return '<div class="col-md-6">' . do_shortcode($content) . '</div>';
    }
    function col_6_close( $atts, $content = null ) {
       return '<div class="col-md-6">' . do_shortcode($content) . '</div></div>';
    }


    function col_7_open( $atts, $content = null ) {
       return '<div class="row"><div class="col-md-7">' . do_shortcode($content) . '</div>';
    }
    function col_7( $atts, $content = null ) {
       return '<div class="col-md-7">' . do_shortcode($content) . '</div>';
    }
    function col_7_close( $atts, $content = null ) {
       return '<div class="col-md-7">' . do_shortcode($content) . '</div></div>';
    }


    function col_8_open( $atts, $content = null ) {
       return '<div class="row"><div class="col-md-8">' . do_shortcode($content) . '</div>';
    }
    function col_8( $atts, $content = null ) {
       return '<div class="col-md-8">' . do_shortcode($content) . '</div>';
    }
    function col_8_close( $atts, $content = null ) {
       return '<div class="col-md-8">' . do_shortcode($content) . '</div></div>';
    }


    function col_9_open( $atts, $content = null ) {
       return '<div class="row"><div class="col-md-9">' . do_shortcode($content) . '</div>';
    }
    function col9( $atts, $content = null ) {
       return '<div class="col-md-9">' . do_shortcode($content) . '</div>';
    }
    function col_9_close( $atts, $content = null ) {
       return '<div class="col-md-9">' . do_shortcode($content) . '</div></div>';
    }


    function col_10_open( $atts, $content = null ) {
       return '<div class="row"><div class="col-md-10">' . do_shortcode($content) . '</div>';
    }
    function col_10( $atts, $content = null ) {
       return '<div class="col-md-10">' . do_shortcode($content) . '</div>';
    }
    function col_10_close( $atts, $content = null ) {
       return '<div class="col-md-10">' . do_shortcode($content) . '</div></div>';
    }


    function col_11_open( $atts, $content = null ) {
       return '<div class="row"><div class="col-md-11">' . do_shortcode($content) . '</div>';
    }
    function col_11( $atts, $content = null ) {
       return '<div class="col-md-11">' . do_shortcode($content) . '</div>';
    }
    function col_11_close( $atts, $content = null ) {
       return '<div class="col-md-11">' . do_shortcode($content) . '</div></div>';
    }
}
new Class_Shortcode();
