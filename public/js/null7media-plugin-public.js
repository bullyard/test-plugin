(function( $ ) {
	'use strict';

	$(function() {

		$("#serverBtn").off('click').on( 'click', function(event) {
			event.preventDefault();

			jQuery.post(ajax_object.ajax_url, {'action': 'currency_ajax', 'val' : $('#convertionInput').val() }, function(response) {
				$('#formOutput').html(response);
			});

		});

	});

})( jQuery );
