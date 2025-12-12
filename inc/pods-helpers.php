<?php
/**
 * Pods Helper Functions - Compatibility layer for ACF to Pods migration
 * 
 * These functions provide ACF-like API that works with Pods
 * 
 * @package Nutriflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get field value (Pods equivalent of get_field)
 * 
 * @param string $field_name Field name
 * @param int|string $post_id Post ID or 'option'
 * @return mixed Field value
 */
function nutriflow_get_field( $field_name, $post_id = null ) {
	if ( function_exists( 'pods' ) ) {
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}
		
		$pod = pods( 'page', $post_id );
		if ( ! $pod || ! $pod->exists() ) {
			// Fallback to ACF if Pods pod doesn't exist
			if ( function_exists( 'get_field' ) ) {
				return get_field( $field_name, $post_id );
			}
			return false;
		}
		
		// Get field data to check type
		$field_data = $pod->fields( $field_name );
		$field_type = null;
		
		if ( $field_data && is_array( $field_data ) ) {
			$field_type = isset( $field_data['type'] ) ? $field_data['type'] : null;
		} elseif ( is_object( $field_data ) && method_exists( $field_data, 'get_type' ) ) {
			$field_type = $field_data->get_type();
		}
		
		// Get field value from Pods
		$value = $pod->field( $field_name, true );
		
		// Handle image/file fields - convert Pods format to ACF-like format
		if ( in_array( $field_type, array( 'file', 'picture' ), true ) ) {
			// Try to get attachment ID directly
			$attachment_id = null;
			
			if ( is_numeric( $value ) ) {
				$attachment_id = (int) $value;
			} elseif ( is_array( $value ) && isset( $value['ID'] ) ) {
				$attachment_id = (int) $value['ID'];
			} elseif ( is_array( $value ) && ! empty( $value ) ) {
				// If it's an array of attachments, get the first one
				$first_item = reset( $value );
				if ( is_numeric( $first_item ) ) {
					$attachment_id = (int) $first_item;
				} elseif ( is_array( $first_item ) && isset( $first_item['ID'] ) ) {
					$attachment_id = (int) $first_item['ID'];
				}
			}
			
			if ( $attachment_id ) {
				$attachment = get_post( $attachment_id );
				if ( $attachment ) {
					$image_sizes = array();
					$image_sizes['thumbnail'] = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
					$image_sizes['medium'] = wp_get_attachment_image_url( $attachment_id, 'medium' );
					$image_sizes['large'] = wp_get_attachment_image_url( $attachment_id, 'large' );
					$image_sizes['full'] = wp_get_attachment_image_url( $attachment_id, 'full' );
					
					return array(
						'ID' => $attachment_id,
						'url' => wp_get_attachment_url( $attachment_id ),
						'alt' => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
						'width' => '',
						'height' => '',
						'filename' => basename( get_attached_file( $attachment_id ) ),
						'sizes' => $image_sizes,
					);
				}
			}
			
			// If we can't get attachment info, return false for file fields
			if ( 'file' === $field_type || 'picture' === $field_type ) {
				return false;
			}
		}
		
		return $value;
	}
	
	// Fallback to ACF if Pods is not available
	if ( function_exists( 'get_field' ) ) {
		return get_field( $field_name, $post_id );
	}
	
	return false;
}

/**
 * Check if field has rows (for repeaters) - Pods equivalent of have_rows
 * 
 * @param string $field_name Field name
 * @param int $post_id Post ID
 * @return bool|int False or number of rows
 */
function nutriflow_have_rows( $field_name, $post_id = null ) {
	if ( function_exists( 'pods_field' ) ) {
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}
		
		$pod = pods( 'page', $post_id );
		if ( ! $pod ) {
			return false;
		}
		
		$field_data = $pod->field( $field_name );
		
		// Check if it's a repeater/table field
		if ( is_array( $field_data ) && ! empty( $field_data ) ) {
			// Set up global for the_row() function
			global $nutriflow_repeater_data, $nutriflow_repeater_index, $nutriflow_repeater_field;
			$nutriflow_repeater_data = $field_data;
			$nutriflow_repeater_index = 0;
			$nutriflow_repeater_field = $field_name;
			return count( $field_data );
		}
		
		return false;
	}
	
	// Fallback to ACF
	if ( function_exists( 'have_rows' ) ) {
		return have_rows( $field_name, $post_id );
	}
	
	return false;
}

/**
 * Get sub field value - Pods equivalent of get_sub_field
 * 
 * @param string $sub_field_name Sub field name
 * @return mixed Field value
 */
function nutriflow_get_sub_field( $sub_field_name ) {
	global $nutriflow_repeater_data, $nutriflow_repeater_index;
	
	if ( isset( $nutriflow_repeater_data[ $nutriflow_repeater_index ] ) ) {
		$row = $nutriflow_repeater_data[ $nutriflow_repeater_index ];
		
		// If row is an array, check for sub_field
		if ( is_array( $row ) && isset( $row[ $sub_field_name ] ) ) {
			return $row[ $sub_field_name ];
		}
		
		// If row is an object
		if ( is_object( $row ) && isset( $row->$sub_field_name ) ) {
			return $row->$sub_field_name;
		}
	}
	
	// Fallback to ACF
	if ( function_exists( 'get_sub_field' ) ) {
		return get_sub_field( $sub_field_name );
	}
	
	return false;
}

/**
 * Setup next row - Pods equivalent of the_row
 */
function nutriflow_the_row() {
	global $nutriflow_repeater_index;
	if ( isset( $nutriflow_repeater_index ) ) {
		$nutriflow_repeater_index++;
	}
	
	// Fallback to ACF
	if ( function_exists( 'the_row' ) ) {
		the_row();
	}
}

/**
 * Alias functions for backward compatibility
 */
if ( ! function_exists( 'get_field' ) ) {
	function get_field( $field_name, $post_id = null ) {
		return nutriflow_get_field( $field_name, $post_id );
	}
}

if ( ! function_exists( 'have_rows' ) ) {
	function have_rows( $field_name, $post_id = null ) {
		return nutriflow_have_rows( $field_name, $post_id );
	}
}

if ( ! function_exists( 'get_sub_field' ) ) {
	function get_sub_field( $sub_field_name ) {
		return nutriflow_get_sub_field( $sub_field_name );
	}
}

if ( ! function_exists( 'the_row' ) ) {
	function the_row() {
		nutriflow_the_row();
	}
}

