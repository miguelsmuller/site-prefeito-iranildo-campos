<?php
if ( ! defined( 'ABSPATH' ) ) exit;

include 'menu-bootstrap.php';
include 'menu-bootstrap-restrict.php';

class Class_Menu
{
	public function __construct(){
	}
}
new Class_Menu();


add_action( 'admin_init', array( 'ClassMenuRestrito', 'setup' ));
class ClassMenuRestrito {
    static $options = array(
        'item_tpl' => '
            <p class="additional-menu-field-{name} description">
                <label for="edit-menu-item-{name}-{id}">
                    {label}<br>
                    <input
                        type="checkbox"
                        id="edit-menu-item-{name}-{id}"
                        class="widefat code edit-menu-item-{name}"
                        name="menu-item-{name}[{id}]"
                        {value}>{description}<br/>
                </label>
            </p>
        ',
    );

    //INICIALIZAÇÃO DO CAMPO
    static function setup() {
        self::$options['fields'] = array(
            'restrict' => array(
                'name' => 'restrict',
                'label' => 'Opção de restrição',
                'container_class' => 'link-restrict',
                'description' => 'Restrito apenas a usuário logados',
            ),
        );

        add_filter( 'wp_edit_nav_menu_walker', function () {
            return 'CustomNavMenu';
        });
        add_filter( 'xteam_nav_menu_item_additional_fields', array( __CLASS__, '_add_fields' ), 10, 5 );
        add_action( 'save_post', array( __CLASS__, '_save_post' ) );
    }

    static function get_fields_schema() {
        $schema = array();
        foreach(self::$options['fields'] as $name => $field) {
            if (empty($field['name'])) {
                $field['name'] = $name;
            }
            $schema[] = $field;
        }
        return $schema;
    }

    static function get_menu_item_postmeta_key($name) {
        return '_menu_item_' . $name;
    }

    /**
     * Inject the
     * @hook {action} save_post
     */
    static function _add_fields($new_fields, $item_output, $item, $depth, $args) {
        $schema = self::get_fields_schema($item->ID);
        foreach($schema as $field) {
            $field['value'] = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);
            if ($field['value'] == '1'){
                $field['value'] = "checked";
            }
            $field['id'] = $item->ID;
            $new_fields .= str_replace(
                array_map(function($key){ return '{' . $key . '}'; }, array_keys($field)),
                array_values(array_map('esc_attr', $field)),
                self::$options['item_tpl']
            );
        }
        return $new_fields;
    }

    /**
     * Save the newly submitted fields
     * @hook {action} save_post
     */
    static function _save_post($post_id) {
        if (get_post_type($post_id) !== 'nav_menu_item') {
            return;
        }
        $fields_schema = self::get_fields_schema($post_id);
        foreach($fields_schema as $field_schema) {
            $form_field_name = 'menu-item-' . $field_schema['name'];
            $key = self::get_menu_item_postmeta_key($field_schema['name']);
            if (isset($_POST[$form_field_name][$post_id])) {

                update_post_meta($post_id, $key, true);
            }else{
                update_post_meta($post_id, $key, false);
            }
        }
    }

}

require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
class CustomNavMenu extends Walker_Nav_Menu_Edit {
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $item_output = '';
        parent::start_el($item_output, $item, $depth, $args);
        $new_fields = apply_filters( 'xteam_nav_menu_item_additional_fields', '', $item_output, $item, $depth, $args );
        // Inject $new_fields before: <div class="menu-item-actions description-wide submitbox">
        if ($new_fields) {
            $item_output = preg_replace('/(?=<div[^>]+class="[^"]*submitbox)/', $new_fields, $item_output);
        }
        $output .= $item_output;
    }
}