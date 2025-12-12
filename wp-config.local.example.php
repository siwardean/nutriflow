<?php
/**
 * Local WordPress Configuration
 * 
 * Copy this file to wp-config.local.php (or add to your existing wp-config.php)
 * This file should NOT be committed to Git (add to .gitignore)
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

