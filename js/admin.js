(function( $ ) {
    'use strict';

    $(function() {
        $('#bp-xprofile-add-field').on('submit', function(e) {
            if ( $('select#fieldtype').val() == 'textarea' && $('#textarea_option1').is(':checked') ) {
                $('#textarea_option0').remove();
            }
        });
    });

})( jQuery );
