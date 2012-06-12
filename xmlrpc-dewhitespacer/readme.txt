=== XML-RPC De-whitespacer ===
Contributors: maxcutler
Tags: xmlrpc, xml-rpc
Requires at least: 3.0
Tested up to: 3.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Removes leading and trailing whitespace from XML-RPC responses that can break some XML-RPC clients.

== Description ==

Uses PHP's output buffering to buffer XML-RPC responses and strip superfluous whitespace.

Plugins, themes, and any other files executed during the WordPress load process can unintentionally emit whitespace,
such as by including a newline character after an explicit closing "?>" PHP tag instead of letting PHP auto-close the
file.

== Changelog ==

= 1.0 =

* Initial release.