<?php

function bbg_export_sections( $sections ) {
	$sections['bp_xprofile'] = array(
		'title' => 'BuddyPress XProfile',
		'content' => 'bp_xprofile',
	);
	return $sections;
}
add_filter( 'wp_all_export_available_sections', 'bbg_export_sections' );

function bbg_export_data( $data ) {
	$field_groups = BP_XProfile_Group::get( array(
		'hide_empty_groups' => true,
		'fetch_fields' => true,
	) );

	$field_data = array();
	foreach ( $field_groups as $field_group ) {
		foreach ( $field_group->fields as $field ) {
			$field_data[] = array(
				'label' => 'bp_xprofile_field-' . $field->id,
				'name' => $field->name,
				'type' => 'bp_xprofile',
			);
		}
	}

	$data['bp_xprofile'] = $field_data;
	return $data;
}
add_filter( 'wp_all_export_available_data', 'bbg_export_data' );

function bbg_export_csv_rows( $articles, $options ) {
	static $done;

	if ( ! empty( $done ) ) {
		return $articles;
	}

	foreach ( $options['cc_value'] as $field_key => $field_fullvalue ) {
		if ( 'bp_xprofile_field-' !== substr( $field_fullvalue, 0, 18 ) ) {
			continue;
		}

		$field_id = (int) str_replace( 'bp_xprofile_field-', '', $field_fullvalue );

		foreach ( $articles as &$article ) {
			$value = xprofile_get_field_data( $field_id, $article['id'] );
			$field_name = $options['cc_name'][ $field_key ];

			if ( is_array( $value ) ) {
				$value = json_encode( $value );
			}

			$article[ $field_name ] = $value;
		}
	}

	return $articles;
}
add_filter( 'wp_all_export_csv_rows', 'bbg_export_csv_rows', 10, 2 );

/** Import *******************************************************************/

/**
 * "Other" section.
 */
function wpaibp_show_other_section( $sections ) {
	// Actually, I guess better to hook to pmxi_extend_options_custom_fields and add
	// a custom template that'll be a new metabox.
	$sections[] = 'other';
	return $sections;
}
add_filter( 'pmxi_visible_template_sections', 'wpaibp_show_other_section', 20 );
