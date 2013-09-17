<?php

/*
 * Plugin Name: Camayak
 * Description: This plugin facilitates publishing and archiving functionality of the <a href="http://www.camayak.com">Camayak service</a>.
 * Version: 1.0.8
 * Author: Camayak
 * Author URI: http://www.camayak.com/
 */

// Load the XML-RPC shim plugin
include_once( 'wp-xmlrpc-modernization/wp-xmlrpc-modernization.php' );

// On admin post editor, give link to the corresponding Camayak assignment page
include_once( 'camayak-admin-notices.php' );
$camayak_admin_notices = new camayak_admin_notices();

// Add additional XML-RPC methods
include_once( 'camayak-xmlrpc.php' );
$camayak_xmlrpc = new camayak_xmlrpc();

// Load the XML-RPC De-whitespacer plugin
include_once( 'xmlrpc-dewhitespacer/xmlrpc-dewhitespacer.php' );

// Load the XML-RPC De-invalidchar plugin
include_once( 'xmlrpc-deinvalidchar/xmlrpc-deinvalidchar.php' );