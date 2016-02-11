<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * BP xprofile custom textarea xprofile field type.
 *
 * @since 0.1.0
 */
class Bpxct_Field_Type_Textarea extends BP_XProfile_Field_Type_Textarea {

    public function __construct() {
        // Call the parent constructor.
        parent::__construct();

        // Now I'm doing my custom work.

        // Change the name of field type to be sure I'm using MY custom textarea.
        $this->name = _x( 'Custom multi-line Text Area', 'xprofile field type', 'bpxct' );

        // Enables options for this field type. This is required to display the
        // checkbox options at the bottom of the form.
        $this->supports_options = true;
    }

    public function edit_field_html( array $raw_properties = array() ) {
        // I need access to field object.
        global $field;

        // The original $richtext_enabled flag.
        $richtext_enabled = bp_xprofile_is_richtext_enabled_for_field();
        // I'm looking inside the options to check if the checkbox "disabled_richtext
        // for this field is checked.
        $options = $field->get_children();
        foreach ($options as $option) {
            if ($option->name == 'disable_richtext') {
                // Found it, so $richtext_enabled should be false
                $richtext_enabled = false;
                // Stop looking for it, just in case.
                break;
            }
        }

        // Original code of buddypress class.

        // User_id is a special optional parameter that certain other fields
        // types pass to {@link bp_the_profile_field_options()}.
        if ( isset( $raw_properties['user_id'] ) ) {
            unset( $raw_properties['user_id'] );
        }

        ?>

        <label for="<?php bp_the_profile_field_input_name(); ?>">
            <?php bp_the_profile_field_name(); ?>
            <?php bp_the_profile_field_required_label(); ?>
        </label>

        <?php

        /** This action is documented in bp-xprofile/bp-xprofile-classes */
        do_action( bp_get_the_profile_field_errors_action() );

        if ( ! $richtext_enabled ) {
            $r = wp_parse_args( $raw_properties, array(
                'cols' => 40,
                'rows' => 5,
            ) );

            ?>

            <textarea <?php echo $this->get_edit_field_html_elements( $r ); ?>><?php bp_the_profile_field_edit_value(); ?></textarea>

            <?php

        } else {

            /**
             * Filters the arguments passed to `wp_editor()` in richtext xprofile fields.
             *
             * @since 2.4.0
             *
             * @param array $args {
             *     Array of optional arguments. See `wp_editor()`.
             *     @type bool $teeny         Whether to use the teeny version of TinyMCE. Default true.
             *     @type bool $media_buttons Whether to show media buttons. Default false.
             *     @type bool $quicktags     Whether to show the quicktags buttons. Default true.
             *     @type int  $textarea_rows Number of rows to display in the editor. Defaults to 1 in the
             *                               'admin' context, and 10 in the 'edit' context.
             * }
             * @param string $context The display context. 'edit' when the markup is intended for the
             *                        profile edit screen, 'admin' when intended for the Profile Fields
             *                        Dashboard panel.
             */
            $editor_args = apply_filters( 'bp_xprofile_field_type_textarea_editor_args', array(
                'teeny'         => true,
                'media_buttons' => false,
                'quicktags'     => true,
                'textarea_rows' => 10,
            ), 'edit' );

            wp_editor(
                bp_get_the_profile_field_edit_value(),
                bp_get_the_profile_field_input_name(),
                $editor_args
            );
        }
    }

    public function admin_field_html( array $raw_properties = array() ) {
        // I need access to field object.
        global $field;

        // The original $richtext_enabled flag.
        $richtext_enabled = bp_xprofile_is_richtext_enabled_for_field();
        // I'm looking inside the options to check if the checkbox "disabled_richtext
        // for this field is checked.
        $options = $field->get_children();
        foreach ($options as $option) {
            if ($option->name == 'disable_richtext') {
                // Found it, so $richtext_enabled should be false
                $richtext_enabled = false;
                // Stop looking for it, just in case.
                break;
            }
        }

        // Original code of buddypress class.

        if ( ! $richtext_enabled ) {

            $r = bp_parse_args( $raw_properties, array(
                'cols' => 40,
                'rows' => 5,
            ) ); ?>

            <textarea <?php echo $this->get_edit_field_html_elements( $r ); ?>></textarea>

            <?php
        } else {

            /** This filter is documented in bp-xprofile/classes/class-bp-xprofile-field-type-textarea.php */
            $editor_args = apply_filters( 'bp_xprofile_field_type_textarea_editor_args', array(
                'teeny'         => true,
                'media_buttons' => false,
                'quicktags'     => true,
                'textarea_rows' => 1,
            ), 'admin' );

            wp_editor(
                '',
                'xprofile_textarea_' . bp_get_the_profile_field_id(),
                $editor_args
            );
        }
    }

    public function admin_new_field_html (\BP_XProfile_Field $current_field, $control_type = '') {
        // Check if this type is a valid type.
        $type = array_search( get_class( $this ), bp_xprofile_get_field_types() );
        if ( false === $type ) {
            return;
        }

        // If it's not my type of field, hide this.
        $class = $current_field->type != $type ? 'display: none;' : '';
        $current_type_obj = bp_xprofile_create_field_type( $type );

        // Get current options. See if my checkbox is already checked.
        $options = $current_field->get_children( true );
        if ( ! $options ) {
            $options = array();
            $i       = 1;
            while ( isset( $_POST[$type . '_option'][$i] ) ) {
                $is_default_option = true;

                $options[] = (object) array(
                    'id'                => -1,
                    'is_default_option' => $is_default_option,
                    'name'              => sanitize_text_field( stripslashes( $_POST[$type . '_option'][$i] ) ),
                );

                ++$i;
            }

            if ( ! $options ) {
                $options[] = (object) array(
                    'id'                => -1,
                    'is_default_option' => false,
                    'name'              => '',
                );
            }
        }

        // Html to display the checkbox.
    ?>
        <div id="<?php echo esc_attr( $type ); ?>" class="postbox bp-options-box" style="<?php echo esc_attr( $class ); ?> margin-top: 15px;">
            <h3><?php esc_html_e( 'Disable richtext:', 'bpxct' ); ?></h3>
            <div class="inside">
                <p>
                    <?php _e('Check this if you want to disable richtext for this field:', 'bpxct'); ?>
                    <input type="hidden" name="<?php echo esc_attr( "{$type}_option[0]" ); ?>" id="<?php echo esc_attr( "{$type}_option0" ); ?>" value="enable_richtext" />
                    <input type="checkbox" name="<?php echo esc_attr( "{$type}_option[1]" ); ?>" id="<?php echo esc_attr( "{$type}_option1" ); ?>" value="disable_richtext"
                           <?php if ($options[0]->name == 'disable_richtext') : ?>checked="checked"<?php endif; ?>/>
                </p>
            </div>
        </div>
    <?php
    }
}
