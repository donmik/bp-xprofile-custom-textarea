<?php
/*
    Plugin Name: Bp Xprofile Custom Textarea
    Plugin URI: http://donmik.com/en/?p=1226&preview=true
    Description: Replace Textarea buddypres field type with my own custom textarea.
    Version: 0.1.1
    Author: donmik
    Author URI: http://donmik.com
*/
if (!class_exists('Bp_Xprofile_Custom_Textarea_Plugin')) {
    class Bp_Xprofile_Custom_Textarea_Plugin {

        private $version;

        public function __construct () {
            $this->version = "0.1.1";

            /** Admin hooks **/
            add_action( 'admin_init', array($this, 'admin_init') );

            /** Buddypress hook **/
            add_action( 'bp_init', array($this, 'init') );

            /** Filters **/
            add_filter( 'bp_xprofile_get_field_types', array($this, 'bpxct_get_field_types'), 10, 1 );
            add_filter( 'bp_xprofile_is_richtext_enabled_for_field', array($this, 'bpxct_disable_richtext'), 10, 2 );
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

        public function bpxct_disable_richtext($enabled, $field_id) {
            // Init $field object with $field_id.
            $field = new BP_XProfile_Field($field_id);
            if (!$field) {
                return $enabled;
            }

            // I'm looking inside the options to check if the checkbox "disabled_richtext
            // for this field is checked.
            $options = $field->get_children();
            foreach ($options as $option) {
                if ($option->name == 'disable_richtext') {
                    // Found it, so $richtext_enabled should be false
                    $enabled = false;
                    // Stop looking for it, just in case.
                    break;
                }
            }

            return $enabled;
        }
    }
}

if (class_exists('Bp_Xprofile_Custom_Textarea_Plugin')) {
    $bxcft_plugin = new Bp_Xprofile_Custom_Textarea_Plugin();
}