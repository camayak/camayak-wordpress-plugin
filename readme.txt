=== Camayak ===
Contributors: maxcutler, picklepete
Tags: camayak
Requires at least: 3.3
Tested up to: 3.5
Stable tag: trunk

This plugin facilitates publishing and archiving functionality of the Camayak.com service.

== Changelog ==

= 1.0.8 =

* Fixing an issue with the dewhitespacer plugin, it's imperative that the Content-Length header is updated with the trimmed response length.

= 1.0.7 =

* Upgrading to `wp-xmlrpc-modernization` v0.9 which fixes some bugs and aligns methods to 3.5 core.

= 1.0.6 =

* Overriding wp.editPost until it's possible to edit a post with a thumbnail again.

= 1.0.5 =

* Added xmlrpc-deinvalidchar plugin.

= 1.0.4 =

* Added xmlrpc-dewhitespacer plugin.

= 1.0.3 =

* Removed GitHub updater temporarily.

= 1.0.2 =

* Updated wp-xmlrpc-modernization to 0.8.2 release.
* Re-added wp.getPostTerms and wp.setPostTerms XML-RPC methods.

= 1.0.1 =

* Updated wp-xmlrpc-modernization to 0.8.1 release.

= 1.0 =

* Initial release.