<?php
	/*
		by xPaw :facepalm:
	*/
	
	require "include/geoip.inc";
	require "include/config.inc.php";
	require "include/amxx_langs.inc.php";
	
	$Id = sql_get_ban_details( (int)SubStr( $_GET[ 'sid' ], 1 ) );
	
	if( !$Id[ 'bid' ] ) Die( "Damnit, damnit, damnit, damnit!!" );
	
	$ShowAdmin = $_GET[ 'adm' ] == 1 ? 1 : 0;
	$Language = $_GET[ 'lang' ];
	$_SESSION[ 'lang' ] = $amxx_langs[ $Language ] ? $amxx_langs[ $Language ] : "english";
	
	$GeoIp = geoip_open( $config->path_root."/include/GeoIP.dat", GEOIP_STANDARD );
	$Id[ 'cc_player' ] = geoip_country_code_by_addr( $GeoIp, $Id[ 'player_ip' ] );
	
	if( $ShowAdmin )
		$Id[ 'cc_admin' ] = geoip_country_code_by_addr( $GeoIp, $Id[ 'admin_ip' ] );
	
	geoip_close( $GeoIp );
	
	$Smarty = new dynamicPage;
	$Smarty->assign( "show_admin", $ShowAdmin );
	$Smarty->assign( "ban_detail", $Id );
	/*$smarty->assign( "design", $config->design ); */
	$Smarty->display( 'motd.tpl' );
?>