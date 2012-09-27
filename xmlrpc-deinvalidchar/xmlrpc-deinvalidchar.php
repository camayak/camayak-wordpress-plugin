<?php

/*
 * Plugin Name: XML-RPC De-Invalid Char Remover
 * Description: Strips invalid characters from XML-RPC responses that can break some XML-RPC clients.
 * Version: 1.0
 * Author: shastaw
 * Author URI: http://wordpress.org/support/topic/editing-core-file-simplepie-parsing-chokes-on-entity
 *
*/

if ( defined( 'XMLRPC_REQUEST' ) ) {

	function xrdic_clean( $buffer ) {
		$ret = "";
        $current;
        if (empty( $buffer )) {
            return $ret;
        }
        $length = strlen( $buffer );
        for ( $i=0; $i < $length; $i++ ) {
            $current = ord( $buffer{$i} );
            if (($current == 0x9) ||
                ($current == 0xA) ||
                ($current == 0xD) ||
                (($current >= 0x20) && ($current <= 0xD7FF)) ||
                (($current >= 0xE000) && ($current <= 0xFFFD)) ||
                (($current >= 0x10000) && ($current <= 0x10FFFF))) {
                $ret .= chr( $current );
            }
            else {
                $ret .= " ";
            }
        }
        return $ret;
	}

	function xrdic_start() {
		// Use the output buffer.
		// When the XML-RPC server calls "exit()", PHP will use the callback and flush the output before shutting down.
		ob_start( 'xrdic_clean' );
	}

	add_action( 'plugins_loaded', 'xrdic_start', 2 );
}