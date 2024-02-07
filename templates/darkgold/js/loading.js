$( document ).ready( function( ) {
	var $loader	= $( "#loader" );
	var $admins	= $( ".admins" ); // div
	var $servers	= $( ".servers" ); // div
	var $current	= 0;

	$( "#adm" ).click( function( ) {
		if( $current == 0 )
			return false;

		$current = 0;
		$servers.fadeOut( 'fast', function( ) {
			$admins.fadeIn( );
		} );
		return false;
	} );
	$( "#serv" ).click( function( ) {
		if( $current == 1 )
			return false;
					
		$current = 1;
		$admins.fadeOut( 'fast', function( ) {
			$servers.fadeIn( );
		} );
		return false;
	} );
} );
