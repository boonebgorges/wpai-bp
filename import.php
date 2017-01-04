<?php

include 'rapid-addon.php';

$wpaibp_addon = new RapidAddon( 'BuddyPress XProfile Data', 'buddypress_addon' );

$field_groups = BP_XProfile_Group::get( array(
	'hide_empty_groups' => true,
	'fetch_fields' => true,
) );

$fields = array();
foreach ( $field_groups as $field_group ) {
	foreach ( $field_group->fields as $field ) {
		$wpaibp_addon->add_field( 'bp_xprofile_' . $field->id, $field->name, 'text' );
		$fields[] = $field;
	}
}

$wpaibp_addon->set_import_function( 'wpaibp_handle_record' );

$wpaibp_addon->run();

function wpaibp_handle_record( $user_id, $data, $import_options ) {
	foreach ( $data as $key => $value ) {
		$field_id = intval( substr( $key, 12 ) ); // bp_xprofile_

		xprofile_set_field_data( $field_id, $user_id, $value );
	}
}

/**
 * Ensure that the "featured" section (ie add-ons) shows on Users screen.
 */
function wpaibp_visible_template_sections( $sections, $post_type ) {
	if ( 'import_users' == $post_type ) {
		$sections[] = 'featured';
	}

	return $sections;
}
add_filter( 'pmxi_visible_template_sections', 'wpaibp_visible_template_sections', 999, 2 );
