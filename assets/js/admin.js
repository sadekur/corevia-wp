let thrailwp_modal = ( show = true ) => {
	if(show) {
		jQuery('#base-modal').show();
	}
	else {
		jQuery('#base-modal').hide();
	}
}

jQuery(function($){
	
	$('#thrail-wp_report-copy').click(function(e) {
		e.preventDefault();
		$('#thrail-wp_tools-report').select();

		try {
			if( document.execCommand('copy') ){
				$(this).html('<span class="dashicons dashicons-saved"></span>');
			}
		} catch (err) {
			console.log('Oops, unable to copy!');
		}
	});
})