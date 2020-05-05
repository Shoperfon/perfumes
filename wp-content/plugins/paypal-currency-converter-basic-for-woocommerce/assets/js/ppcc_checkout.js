/*
Place this, or a subset of it, inside PayPal Payment description:

Cart Total: <span class="ppcc_cart_total" /><br>
Shipping Total: <span class="ppcc_shipping_total" /><br>
Order Total Tax: <span class="ppcc_tax_total" /><br>
Order Total inclusive Tax: <span class="ppcc_total_order_inc_tax" /><br>
Conversion Rate: <span class="ppcc_cr" />

*/


jQuery(document).ready(function(){
	jQuery(document.body).on('change', 'input[name="payment_method"]', function() {
	   jQuery('body').trigger('update_checkout');
	});
});




jQuery( document ).ajaxComplete(function(){  

	var myRegexp = /\ (\D\D\D)\//g;
	var match = myRegexp.exec(php_data.cr);


	// This Values come initially by the session
	jQuery('.ppcc_cart_total').html(php_data.cart_total);
	jQuery('.ppcc_tax_total').html(php_data.tax_total);
	jQuery('.ppcc_shipping_total').html(php_data.shipping_total);
	jQuery('.ppcc_total_order_inc_tax').html(php_data.total_order_inc_tax);
	jQuery('.ppcc_cr').html(php_data.cr);


	// This three values are not given on the checkout page, so can't be recalculated.
	// jQuery('.ppcc_cart_tax').html(php_data.cart_tax);
	// jQuery('.ppcc_shipping_tax_total').html(php_data.shipping_tax_total);
	// jQuery('.ppcc_total_order_exc_tax').html(php_data.total_order_exc_tax);
	
	// The following code retrieves and recalculates the changes made in the checkout preview:

	if(jQuery("tr.shipping td span").length){
		var ppcc_shipping_total = jQuery("tr.shipping td span").contents()[1].data.replace(".", "").replace(",", ".") * php_data.crval;
		jQuery('.ppcc_shipping_total').html(ppcc_shipping_total.toFixed(2) + ' ' + match[1]);
	}
	if(jQuery("tr.tax-rate td span").length){
		var tax_rate = jQuery("tr.tax-rate td span").contents()[1].data.replace(".", "").replace(",", ".") * php_data.crval;
		jQuery('.ppcc_tax_total').html(tax_rate.toFixed(2) + ' ' + match[1]);
	}
	if(jQuery("tr.order-total td span").length){
		var order_total = jQuery("tr.order-total td span").contents()[1].data.replace(".", "").replace(",", ".") * php_data.crval;
		jQuery('.ppcc_total_order_inc_tax').html(order_total.toFixed(2) + ' ' + match[1]);
	}

	// unfortunately WooCommerc does not provide a filter to retrieve the preview values only. It uses its own wc-ajax mode to fetch the complete html code for the checkout preview.

});


