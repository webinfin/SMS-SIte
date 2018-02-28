(function($){
	"use strict";

	$('input[name="content"]').change( function(e) {
		var $this = $(this);

		if ( $this.is(':checked') ) {
			$this.parent().parent().find('input[name="media"]').prop('disabled', false);
			$this.parent().parent().find('input[name="essential_grid"]').prop('disabled', false);
		}
		else {
			$this.parent().parent().find('input[name="media"]').prop('checked', false).prop('disabled', true);
			$this.parent().parent().find('input[name="essential_grid"]').prop('checked', false).prop('disabled', true);
		}

	});

	$('.hb-import-template').click(function(e){
		e.preventDefault();
		var $this = $(this);

		if ( $this.hasClass('hb-inactive') ){
			return;
		}

		var $demo_name = $this.attr('data-demo-name');
		var $demo_id = $this.attr('data-demo-id');

		var $import_content = $this.parent().parent().find('input[name="content"]').is(':checked');
		var $import_sliders = $this.parent().parent().find('input[name="sliders"]').is(':checked');
		var $import_widgets = $this.parent().parent().find('input[name="widgets"]').is(':checked');
		var $import_media = $this.parent().parent().find('input[name="media"]').is(':checked');
		var $import_highend_options = $this.parent().parent().find('input[name="highend_options"]').is(':checked');
		var $import_essential_grid = $this.parent().parent().find('input[name="essential_grid"]').is(':checked');

		var $response = confirm("Are you sure you want to import \"" + $demo_name + "\" demo template?\r\n\r\nWe recommend doing this only on fresh WordPress installations. The import will reset all widgets set in Appearance > Widgets.");
		var $import_url = $this.attr('data-content-url');
		var $nonce = $("#hb_nonce").val();
		var $to_json = "";

		if ($response){
			$('html,body').animate({ scrollTop: 0 }, 350);
			var $serilized = "id=" + $demo_id + "&name=" + $demo_name + "&content=" + $import_content + "&sliders=" + $import_sliders + "&widgets=" + $import_widgets + "&media=" + $import_media + "&highend_options=" + $import_highend_options + "&essential_grid=" + $import_essential_grid;

			$('.import-message').html('<div class="updated hb-msg"><span class="spinner demo-spinner"></span><p>Please be patient while <strong>' + $demo_name + '</strong> demo is being imported. This may take a few minutes.</p></div>');
			$('.hb-import-template').addClass('hb-inactive');
			$('.hb-templates').css('opacity', 0.4);
			$('.import-message .spinner').css("display", "inline-block");
			
			// Alert on page exit
			window.onbeforeunload = function (e) {
			  var e = e || window.event;
			  if (e) { // For IE and Firefox
			    e.returnValue = 'The demo import process is still in progress.';
			  }
			  return 'The demo import process is still in progress.'; // For Safari
			};

			// Import options -> importer.php
			var data = {action: 'hb_ajax_import_options', options: $serilized};
	        $.post(ajaxurl, data, function(response, status) {
	        	if (status == 'success'){
					$('.import-message').html('<div class="updated hb-msg"><p>'+ response +'</p></div>');
				} else {
					$('.import-message').html('<div class="error hb-msg hb-error"><p>There was an error with the import. Please reload the page and try again.</p></div>');
				}

				window.onbeforeunload = null;
	        	
				// Get Theme Options JSON -> functions > hb_ajax_get_to_json
	        	var data2 = {action: 'hb_ajax_get_to_json',options: $serilized};
	        	$.post(ajaxurl, data2, function(response) {
	           	$to_json = response;

					// Import Theme Options via ajax
					var data = {action: 'vp_ajax_hb_highend_option_import_option', option: $to_json, nonce: $nonce};
					$.post(ajaxurl, data, function(response) {
						$('.hb-import-template').removeClass('hb-inactive');
						$('.hb-templates').css('opacity', 1);
					}, 'JSON');

	        	}, 'html' );

	        }, 'html' );

    	}

	});

})(jQuery);