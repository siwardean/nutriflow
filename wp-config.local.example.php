<?php
/**
 * Local WordPress Configuration
 * 
 * INSTRUCTIONS:
 * =============
 * 1. Copy this file to the WordPress ROOT directory (same level as wp-config.php)
 * 2. Rename it to: wp-config.local.php
 * 3. Uncomment the configuration you need
 * 4. This file should NOT be committed to Git (already in .gitignore)
 * 
 * Location: /wp-config.local.php (WordPress root, NOT in the theme folder)
 * 
 * Environment-specific configurations for Nutriflow theme
 */

// ============================================
// Field System Configuration (ACF vs Pods)
// ============================================

// Option 1: Use Pods (default for development)
// Uncomment the line below to use Pods:
define( 'NUTRIFLOW_FIELD_SYSTEM', 'pods' );

// Option 2: Force ACF (for rollback or production)
// Uncomment the line below to force ACF:
// define( 'NUTRIFLOW_FIELD_SYSTEM', 'acf' );

// Option 3: Backward compatibility (old method)
// define( 'NUTRIFLOW_FORCE_ACF', true ); // Forces ACF

// ============================================
// Development Settings
// ============================================

// Debug mode (optional)
// define( 'WP_DEBUG', true );
// define( 'WP_DEBUG_LOG', true );
// define( 'WP_DEBUG_DISPLAY', false );

