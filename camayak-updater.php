<?php

if ( is_admin() ) {
	include_once( 'updater.php' );

	$user = 'camayak';
	$repo = 'camayak-wordpress-plugin';
	$branch = 'master';
	$config = array(
		'slug' => plugin_basename( __FILE__ ), // this is the slug of your plugin
		'proper_folder_name' => 'camayak', // this is the name of the folder your plugin lives in
		'api_url' => 'https://api.github.com/repos/' . $user . '/' . $repo, // the github API url of your github repo
		'raw_url' => 'https://raw.github.com/' . $user . '/' . $repo . '/' . $branch, // the github raw url of your github repo
		'github_url' => 'https://github.com/' . $user . '/' . $repo, // the github url of your github repo
		'zip_url' => 'https://github.com/' . $user . '/' . $repo . '/zipball/' . $branch, // the zip url of the github repo
		'sslverify' => true, // whether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
		'requires' => '3.3', // which version of WordPress does your plugin require?
		'tested' => '3.4', // which version of WordPress is your plugin tested up to?
		'readme' => 'readme.txt'
	);
	new WPGitHubUpdater($config);
}