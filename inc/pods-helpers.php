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
 * Get the active field system (ACF or Pods)
 * 
 * Priority order:
 * 1. NUTRIFLOW_FIELD_SYSTEM constant (wp-config.php or environment variable)
 *    - 'acf' or true → Use ACF
 *    - 'pods' or false → Use Pods
 * 2. NUTRIFLOW_FORCE_ACF constant (backward compatibility)
 * 3. Environment variable NUTRIFLOW_FIELD_SYSTEM
 * 4. Database option
 * 5. Default: 'pods' (use Pods)
 * 
 * @return string 'acf' or 'pods'
 */
function nutriflow_get_field_system() {
	// Priority 1: NUTRIFLOW_FIELD_SYSTEM constant (most explicit)
	if ( defined( 'NUTRIFLOW_FIELD_SYSTEM' ) ) {
		$system = NUTRIFLOW_FIELD_SYSTEM;
		if ( $system === 'acf' || $system === true ) {
			return 'acf';
		}
		if ( $system === 'pods' || $system === false ) {
			return 'pods';
		}
	}
	
	// Priority 2: NUTRIFLOW_FORCE_ACF (backward compatibility)
	if ( defined( 'NUTRIFLOW_FORCE_ACF' ) && NUTRIFLOW_FORCE_ACF ) {
		return 'acf';
	}
	
	// Priority 3: Environment variable (useful for deployment/config files)
	if ( isset( $_ENV['NUTRIFLOW_FIELD_SYSTEM'] ) ) {
		$system = $_ENV['NUTRIFLOW_FIELD_SYSTEM'];
		if ( $system === 'acf' || $system === 'true' || $system === '1' ) {
			return 'acf';
		}
		if ( $system === 'pods' || $system === 'false' || $system === '0' ) {
			return 'pods';
		}
	}
	
	// Priority 4: Database option (can be changed dynamically)
	$db_option = get_option( 'nutriflow_field_system', 'pods' );
	if ( $db_option === 'acf' || $db_option === true ) {
		return 'acf';
	}
	
	// Priority 5: Old database option (backward compatibility)
	if ( get_option( 'nutriflow_force_acf', false ) ) {
		return 'acf';
	}
	
	// Default: Use Pods
	return 'pods';
}

/**
 * Check if we should force ACF usage (for rollback) - backward compatibility
 * 
 * @deprecated Use nutriflow_get_field_system() instead
 * @return bool
 */
function nutriflow_should_use_acf() {
	return nutriflow_get_field_system() === 'acf';
}

/**
 * Get field value with full ACF/Pods compatibility
 * 
 * This function intelligently chooses between ACF and Pods based on:
 * - Force ACF setting (for rollback)
 * - Which system has data available
 * - Fallback logic to ensure no data loss
 * 
 * Priority order:
 * 1. If FORCE_ACF is enabled and ACF exists: Use ACF (with Pods fallback if empty)
 * 2. If Pods exists and pod has the field: Use Pods (with ACF fallback if empty)
 * 3. Fallback to ACF if available
 * 4. Return false if neither system has the value
 * 
 * @param string $field_name Field name
 * @param int|string $post_id Post ID or 'option'
 * @return mixed Field value
 */
function nutriflow_get_field( $field_name, $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}
	
	$active_system = nutriflow_get_field_system();
	// ACF is available if the native ACF function exists (before our wrapper)
	$acf_available = function_exists( 'acf_get_field' ) || ( function_exists( 'get_field' ) && class_exists( 'ACF' ) );
	// Pods is available if the pods() function exists
	$pods_available = function_exists( 'pods' );
	
	// Use the active system
	if ( $active_system === 'acf' && $acf_available ) {
		// If ACF is active, use native ACF function directly
		if ( function_exists( 'acf_get_field' ) ) {
			// Use native ACF
			$acf_value = call_user_func_array( 'get_field', array( $field_name, $post_id ) );
		} else {
			$acf_value = get_field( $field_name, $post_id );
		}
		
		// If ACF has a value, use it
		if ( ! empty( $acf_value ) || $acf_value === '0' || $acf_value === 0 ) {
			return $acf_value;
		}
		
		// If ACF is empty but Pods is available, try Pods as fallback
		if ( $pods_available ) {
			$pod = pods( 'page', $post_id );
			if ( $pod && $pod->exists() ) {
				$pods_value = nutriflow_get_pods_field_value( $pod, $field_name );
				if ( ! empty( $pods_value ) || $pods_value === '0' || $pods_value === 0 ) {
					return $pods_value;
				}
			}
		}
		
		// Return ACF value even if empty (to maintain consistency)
		return $acf_value;
	}
	
	// Default: Use Pods
	if ( $active_system === 'pods' && $pods_available ) {
		$pod = pods( 'page', $post_id );
		if ( $pod && $pod->exists() ) {
			$pods_value = nutriflow_get_pods_field_value( $pod, $field_name );
			
			// If Pods has a value, use it
			if ( ! empty( $pods_value ) || $pods_value === '0' || $pods_value === 0 || $pods_value === false ) {
				return $pods_value;
			}
		}
		
		// Fallback to ACF if Pods doesn't have value
		if ( $acf_available ) {
			// Use native ACF function if available, otherwise our wrapper
			if ( function_exists( 'acf_get_field' ) ) {
				$acf_value = call_user_func_array( 'get_field', array( $field_name, $post_id ) );
			} else {
				$acf_value = get_field( $field_name, $post_id );
			}
			if ( ! empty( $acf_value ) || $acf_value === '0' || $acf_value === 0 ) {
				return $acf_value;
			}
		}
		
		// Return null if Pods didn't have a value
		return null;
	}
	
	// Final fallback: If Pods is requested but not available, try ACF
	if ( $acf_available ) {
		if ( function_exists( 'acf_get_field' ) ) {
			return call_user_func_array( 'get_field', array( $field_name, $post_id ) );
		}
		return get_field( $field_name, $post_id );
	}
	
	return false;
}

/**
 * Get field value from Pods and handle format conversion
 * 
 * @param object $pod Pods object
 * @param string $field_name Field name
 * @return mixed Field value
 */
function nutriflow_get_pods_field_value( $pod, $field_name ) {
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

/**
 * Check if field has rows (for repeaters) - Compatible ACF/Pods
 * 
 * @param string $field_name Field name
 * @param int $post_id Post ID
 * @return bool|int False or number of rows
 */
function nutriflow_have_rows( $field_name, $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}
	
	$active_system = nutriflow_get_field_system();
	$acf_available = function_exists( 'have_rows' );
	$pods_available = function_exists( 'pods' );
	
	// Use the active system
	if ( $active_system === 'acf' && $acf_available ) {
		$acf_rows = have_rows( $field_name, $post_id );
		if ( $acf_rows ) {
			return $acf_rows;
		}
		// If ACF doesn't have rows, try Pods as fallback
		if ( $pods_available ) {
			return nutriflow_have_rows_pods( $field_name, $post_id );
		}
		return false;
	}
	
	// Default: Try Pods first
	if ( $pods_available ) {
		$pods_rows = nutriflow_have_rows_pods( $field_name, $post_id );
		if ( $pods_rows ) {
			return $pods_rows;
		}
	}
	
	// Fallback to ACF
	if ( $acf_available ) {
		return have_rows( $field_name, $post_id );
	}
	
	return false;
}

/**
 * Check if Pods field has rows
 * 
 * @param string $field_name Field name
 * @param int $post_id Post ID
 * @return bool|int False or number of rows
 */
function nutriflow_have_rows_pods( $field_name, $post_id ) {
	$pod = pods( 'page', $post_id );
	if ( ! $pod || ! $pod->exists() ) {
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
 * 
 * These functions wrap the ACF functions if they exist, otherwise use our compatibility layer.
 * This ensures that all templates work with both ACF and Pods seamlessly.
 */
if ( ! function_exists( 'get_field' ) ) {
	/**
	 * WordPress wrapper for get_field - uses Pods/ACF compatibility layer
	 * 
	 * @param string $field_name Field name
	 * @param int|string $post_id Post ID
	 * @return mixed Field value
	 */
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

