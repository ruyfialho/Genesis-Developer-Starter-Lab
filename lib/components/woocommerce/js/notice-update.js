jQuery(document).on( 'click', '.developers-woocommerce-notice .notice-dismiss', function() {

	jQuery.ajax({
	    url: ajaxurl,
	    data: {
	        action: 'adds_option_for_dismiss_woocommerce_notice'
	    }
	});

});
