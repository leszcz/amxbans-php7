$( document ).ready( function( ) {
	$.ajax( {
		type: "POST",
		url: "include/admin/ver_check.php",
		data: "v=" + v,
		success: function( Result ) {
			$( '#update' ).html( Result ).fadeIn( 'slow' );
		}
	} );
} );