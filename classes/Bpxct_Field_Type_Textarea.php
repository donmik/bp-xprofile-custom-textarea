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

    /**
     * Remove validation_whitelist to check if is_valid.
     *
     * This method doesn't support chaining.
     *
     * @since 0.2.0
     *
     * @param string|array $values Value to check against the registered formats.
     * @return bool True if the value validates
     */
    public function is_valid( $values ) {
        $this->validation_whitelist = null;
        return parent::is_valid( $values );
    }
}
