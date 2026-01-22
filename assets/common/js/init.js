const thrailwp_modal = ( show = true ) => {
	const modal = document.getElementById( 'thrail-wp-modal' );
	if ( show ) {
		modal.style.display = '';
	} else {
		modal.style.display = 'none';
	}
}