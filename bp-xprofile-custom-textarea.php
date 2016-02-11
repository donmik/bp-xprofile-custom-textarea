<?php
/*
    Plugin Name: Bp Xprofile Custom Textarea
    Plugin URI: http://donmik.com/en/?p=1226&preview=true
    Description: Replace Textarea buddypres field type with my own custom textarea.
    Version: 0.1.0
    Author: donmik
    Author URI: http://donmik.com
*/
if (!class_exists('Bp_Xprofile_Custom_Textarea_Plugin')) {
    class Bp_Xprofile_Custom_Textarea_Plugin {

        private $version;

        public function __construct () {
            $this->version = "0.1.0";

            /** Admin hooks **/
            add_action( 'admin_init', array($this, 'admin_init') );

            /** Buddypress hook **/
            add_action( 'bp_init', array($this, 'init') );

            /** Filters **/
            add_filter( 'bp_xprofile_get_field_types', array($this, 'bpxct_get_field_types'), 10, 1 );
        }

        public function init() {
            require_once( 'classes/Bpxct_Field_Type_Textarea.php' );
        }

        public function admin_init() {
            if (is_admin()) {
                // Enqueue javascript.
                wp_enqueue_script('bpxct-js', plugin_dir_url(__FILE__) . 'js/admin.js', array(), $this->version, true);
            }
        }

        public function bpxct_get_field_types($fields) {
            $new_fields = array(
                'textarea' => 'Bpxct_Field_Type_Textarea',
            );
            $fields = array_merge($fields, $new_fields);

            return $fields;
        }
    }
}

if (class_exists('Bp_Xprofile_Custom_Textarea_Plugin')) {
    $bxcft_plugin = new Bp_Xprofile_Custom_Textarea_Plugin();
}