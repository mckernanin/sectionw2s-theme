<?php

add_filter( 'wc_add_to_cart_message', 'custom_add_to_cart_message' );
 
function custom_add_to_cart_message() {
 
global $woocommerce;
$return_to  = get_permalink(woocommerce_get_page_id('shop'));
$message    = '<a href="/trading-post/" class="button wc-forwards">Continue Shopping</a> <a class="button wc-forwards or"> or </a> <a href="/cart/" class="button wc-forwards">View Cart</a> Product successfully added to your cart';
return $message;
}