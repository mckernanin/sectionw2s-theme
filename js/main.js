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

    $('.product').each( function() {
        if ( $(this).hasClass('outofstock') ) {
            $('.onsale', this).text('Sold Out!')
        }
    });

    if ( $('body').hasClass('page-id-376') ) {
        $('.main_title').after('<a href="/trading-post/" class="button continue-shopping">Continue Shopping</a>');
    };

    if ( $('body').hasClass('single-product') ) {
        $('.woocommerce-breadcrumb').before('<a href="/trading-post/" class="button continue-shopping">< Return to Trading Post</a>');
    };

    if ( $('body').hasClass('post-type-archive-product') ) {
        window.location.href = 'https://sectionw2s.org/trading-post/';
    };

    function exportTableToCSV($table, filename) {

        var $rows = $table.find('tr:has(td)'),

            // Temporary delimiter characters unlikely to be typed by keyboard
            // This is to avoid accidentally splitting the actual contents
            tmpColDelim = String.fromCharCode(11), // vertical tab character
            tmpRowDelim = String.fromCharCode(0), // null character

            // actual delimiter characters for CSV format
            colDelim = '","',
            rowDelim = '"\r\n"',

            // Grab text from table into CSV formatted string
            csv = '"' + $rows.map(function (i, row) {
                var $row = $(row),
                    $cols = $row.find('td');

                return $cols.map(function (j, col) {
                    var $col = $(col),
                        text = $col.text();

                    return text.replace('"', '""'); // escape double quotes

                }).get().join(tmpColDelim);

            }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '"',

            // Data URI
            csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

        $(this)
            .attr({
            'download': filename,
                'href': csvData,
                'target': '_blank'
        });
    }

    $('#conclaveCSV').click(
    function() { 
        exportTableToCSV.apply(this, [$('#conclaveRegistrationTable'), 'w2sconclave.csv']);
    });
});

