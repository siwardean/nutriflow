<?php
/**
 * Nutriflow Theme Customizer
 *
 * @package Nutriflow
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function nutriflow_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'nutriflow_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'nutriflow_customize_partial_blogdescription',
			)
		);
	}

	// =============================================
	// Nutriflow Custom Settings Section
	// =============================================
	$wp_customize->add_section( 'nutriflow_settings', array(
		'title'    => __( 'Nutriflow Settings', 'nutriflow' ),
		'priority' => 30,
	) );

	// Calendly URL
	$wp_customize->add_setting( 'nutriflow_calendly_url', array(
		'default'           => 'https://calendly.com/fl-vanhecke/premiere-consultation',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'nutriflow_calendly_url', array(
		'label'    => __( 'Calendly URL', 'nutriflow' ),
		'section'  => 'nutriflow_settings',
		'type'     => 'url',
	) );

	// Instagram URL
	$wp_customize->add_setting( 'nutriflow_instagram_url', array(
		'default'           => 'https://www.instagram.com/nutriflow.florence/',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'nutriflow_instagram_url', array(
		'label'    => __( 'Instagram URL', 'nutriflow' ),
		'section'  => 'nutriflow_settings',
		'type'     => 'url',
	) );

	// LinkedIn URL
	$wp_customize->add_setting( 'nutriflow_linkedin_url', array(
		'default'           => 'https://www.linkedin.com/in/florence-van-hecke-30386712b/',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'nutriflow_linkedin_url', array(
		'label'    => __( 'LinkedIn URL', 'nutriflow' ),
		'section'  => 'nutriflow_settings',
		'type'     => 'url',
	) );

	// Phone Number
	$wp_customize->add_setting( 'nutriflow_phone', array(
		'default'           => '+32 486 920 962',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'nutriflow_phone', array(
		'label'    => __( 'Phone Number', 'nutriflow' ),
		'section'  => 'nutriflow_settings',
		'type'     => 'text',
	) );

	// Email
	$wp_customize->add_setting( 'nutriflow_email', array(
		'default'           => 'fl.vanhecke@gmail.com',
		'sanitize_callback' => 'sanitize_email',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'nutriflow_email', array(
		'label'    => __( 'Email', 'nutriflow' ),
		'section'  => 'nutriflow_settings',
		'type'     => 'email',
	) );

	// Address
	$wp_customize->add_setting( 'nutriflow_address', array(
		'default'           => 'Bruxelles, Belgique',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'nutriflow_address', array(
		'label'    => __( 'Address', 'nutriflow' ),
		'section'  => 'nutriflow_settings',
		'type'     => 'text',
	) );
}
add_action( 'customize_register', 'nutriflow_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function nutriflow_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function nutriflow_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function nutriflow_customize_preview_js() {
	wp_enqueue_script( 'nutriflow-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), NUTRIFLOW_VERSION, true );
}
add_action( 'customize_preview_init', 'nutriflow_customize_preview_js' );
