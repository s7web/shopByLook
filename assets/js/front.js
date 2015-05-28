jQuery( function ( $ ) {
    $( '[data-shop]' ).click( function () {

        add_to_cart( this );

        return false;
    } );

    $( '[data-shop-all]' ).click( function () {

        $( '[data-shop]' ).each( function () {

            add_to_cart( this );
        } );

        return false;
    } );

    function add_to_cart( el ) {

        if ( ! $( el ).is( ':visible' ) ) return;
        $( el ).parent().removeClass( 'added' );
        $( el ).parent().addClass( 'loading' );
        var product_id = $( el ).data( 'shop' );

        $.ajax( {
            type:    'POST',
            url:     woocommerce_params.ajax_url,
            data:    {
                action:      'woocommerce_add_to_cart',
                product_id:  product_id,
                product_sku: '',
                quantity:    1
            },
            success: function() {

                $( el ).parent().removeClass( 'loading' );
                $( el ).parent().addClass( 'added' );
                $( el ).hide();
            },
            async: false
        } );
    }
} );