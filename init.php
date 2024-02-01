<?php
/**
 * Plugin Name: ACF UUID Field
 * Description: ACF field type for generating UUIDs. 
 * Plugin URI: http://example.com/magic-plugin
 * Version: 1.0.0
 * Text Domain: acf-uuid
 * 
 * @package ACF_UUID
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'uuid_include_acf_field_uuid_field' );
/**
 * Registers the ACF field type.
 */
function uuid_include_acf_field_uuid_field() {
	if ( ! function_exists( 'acf_register_field_type' ) ) {
		return;
	}

	require_once __DIR__ . '/vendor/autoload.php';
	require_once __DIR__ . '/class-uuid-acf-field-uuid-field.php';

	acf_register_field_type( 'uuid_acf_field_uuid_field' );
}
