jQuery(function($) { 
	$('section.profiles').mixItUp({
		selectors: {
			target: '.profile'
		},
		load: {
			sort: 'default:asc'
		}
	});	
    $(document).ready(function() {
    	if ($('.tablesorter').length ) {
    		$('.tablesorter').tablesorter();
    	}
    });
    
    /* when product quantity changes, update quantity attribute on add-to-cart button */
    $("form.cart").on("change", "input.qty", function() {
        if (this.value === "0")
            this.value = "1";
 
        $(this.form).find("button[data-quantity]").attr("data-quantity", this.value);
    });
 
    /* remove old "view cart" text, only need latest one thanks! */
    $(document.body).on("adding_to_cart", function() {
        $("a.added_to_cart").remove();
    });
});

