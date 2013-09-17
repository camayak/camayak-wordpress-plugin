<?php

/*
 * Plugin Name: XML-RPC De-whitespacer
 * Description: Removes leading or trailing whitespace in XML-RPC responses that can break XML-RPC clients.
 * Version: 1.0
 * Author: Max Cutler
 * Author URI: http://www.maxcutler.com
 *
*/

if ( defined( 'XMLRPC_REQUEST' ) ) {

	function xrdw_clean( $buffer ) {
		// use PHP's trim function to remove leading and trailing whitespace
		$trimmed = trim ( $buffer );
		// update the Content-Length value with the new buffer length.
		header( 'Content-Length:'.strlen( $trimmed ) );
		return $trimmed;
	}

	function xrdw_start() {
		// Use the output buffer.
		// When the XML-RPC server calls "exit()", PHP will use the callback and flush the output before shutting down.
		ob_start( 'xrdw_clean' );
	}

	add_action( 'plugins_loaded', 'xrdw_start', 1 );
}