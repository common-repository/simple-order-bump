jQuery(document).ready(function($) {
    console.log( '  before click' );
    var sob= $('.sob_input_product');
    sob.on('click', function () {
        console.log( 'Click' );

        var product = $(this).attr('product-id');
        var ajaxurl = $(this).data('ajax');
        var security = $(this).data('nonce');
        var chkAction = $(this).is(":checked");
        console.log( 'Click' );

        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'add_product_to_cart',
                product:product,
                security:security,
                chkAction: chkAction
            },
            success: function( response ) {
                console.log( response );
                if(response.success){
                    $('body').trigger('update_checkout');
                }
            }
        });
    });
});