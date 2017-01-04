<?php

/*
Plugin name: WP All Import for BuddyPress XProfile Data
Description: Export and import BuddyPress XProfile data using WP All Import and WP All Export
*/

function wpaibp_init() {
	require __DIR__ . '/import.php';
	require __DIR__ . '/export.php';
}
add_action( 'bp_init', 'wpaibp_init', 1000 );
