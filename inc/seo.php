<?php
/**
 * SEO Functions for Nutriflow Theme
 * 
 * Handles meta tags, Open Graph, Schema.org structured data, and other SEO optimizations
 *
 * @package Nutriflow
 */

/**
 * Get SEO meta description
 */
function nutriflow_get_seo_description( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Check for custom meta description (from Pods or custom field)
	if ( function_exists( 'nutriflow_get_field' ) ) {
		$custom_desc = nutriflow_get_field( 'seo_description', $post_id );
		if ( ! empty( $custom_desc ) ) {
			return wp_strip_all_tags( $custom_desc );
		}
	}

	// Fallback to excerpt
	if ( has_excerpt( $post_id ) ) {
		$excerpt = get_the_excerpt( $post_id );
		if ( ! empty( $excerpt ) ) {
			return wp_strip_all_tags( $excerpt );
		}
	}

	// Fallback to content preview
	$content = get_post_field( 'post_content', $post_id );
	if ( ! empty( $content ) ) {
		$content = wp_strip_all_tags( $content );
		$content = wp_trim_words( $content, 30, '' );
		if ( ! empty( $content ) ) {
			return $content;
		}
	}

	// Default description
	return 'Nutrithérapeute à Bruxelles. Accompagnement en nutrithérapie : rééquilibrage alimentaire, nutrition sportive, troubles hormonaux et digestifs.';
}

/**
 * Get SEO title
 */
function nutriflow_get_seo_title( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Check for custom SEO title
	if ( function_exists( 'nutriflow_get_field' ) ) {
		$custom_title = nutriflow_get_field( 'seo_title', $post_id );
		if ( ! empty( $custom_title ) ) {
			return wp_strip_all_tags( $custom_title );
		}
	}

	// Use post title
	if ( $post_id && get_the_title( $post_id ) ) {
		return get_the_title( $post_id );
	}

	// Fallback
	return get_bloginfo( 'name' ) . ' - Nutrithérapeute à Bruxelles';
}

/**
 * Get Open Graph image
 */
function nutriflow_get_og_image( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Featured image
	if ( has_post_thumbnail( $post_id ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
		if ( $image && isset( $image[0] ) ) {
			return $image[0];
		}
	}

	// Default image
	$default_image = get_template_directory_uri() . '/logo-nutriflow-blue-navy.png';
	
	return $default_image;
}

/**
 * Add meta tags to head
 */
function nutriflow_add_seo_meta_tags() {
	global $post;

	$title = '';
	$description = '';
	$url = '';
	$image = '';
	$type = 'website';

	if ( is_front_page() ) {
		$title = get_bloginfo( 'name' ) . ' - Nutrithérapeute à Bruxelles | Accompagnement en nutrithérapie';
		$description = 'Florence Van Hecke, nutrithérapeute à Bruxelles. Accompagnement personnalisé en nutrithérapie : rééquilibrage alimentaire, nutrition sportive, troubles hormonaux et digestifs. Prenez rendez-vous.';
		$url = home_url( '/' );
	} elseif ( is_singular() && $post ) {
		$title = nutriflow_get_seo_title( $post->ID );
		$description = nutriflow_get_seo_description( $post->ID );
		$url = get_permalink( $post->ID );
		$type = is_page() ? 'website' : 'article';
	} elseif ( is_archive() ) {
		$title = get_the_archive_title();
		$description = get_the_archive_description();
		if ( empty( $description ) ) {
			$description = 'Archives de ' . get_bloginfo( 'name' );
		}
		$url = get_term_link( get_queried_object() );
	}

	$image = nutriflow_get_og_image( $post ? $post->ID : null );
	$site_name = get_bloginfo( 'name' );

	// Meta description
	if ( ! empty( $description ) ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}

	// Open Graph tags
	echo '<meta property="og:locale" content="fr_BE">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( ! empty( $description ) ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
	if ( ! empty( $image ) ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
		echo '<meta property="og:image:width" content="1200">' . "\n";
		echo '<meta property="og:image:height" content="630">' . "\n";
	}

	// Twitter Card
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( ! empty( $description ) ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( ! empty( $image ) ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	// Canonical URL
	if ( is_singular() && $post ) {
		echo '<link rel="canonical" href="' . esc_url( get_permalink( $post->ID ) ) . '">' . "\n";
	} elseif ( is_front_page() ) {
		echo '<link rel="canonical" href="' . esc_url( home_url( '/' ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'nutriflow_add_seo_meta_tags', 1 );

/**
 * Add Schema.org structured data
 */
function nutriflow_add_schema_markup() {
	// Get contact info from customizer
	$phone = nutriflow_get_option( 'phone', '+32 486 920 962' );
	$email = nutriflow_get_option( 'email', 'fl.vanhecke@gmail.com' );
	$address = 'Flagey, Ixelles, Bruxelles, Belgique';
	
	// Clean phone number for schema
	$phone_clean = preg_replace( '/[^0-9+]/', '', $phone );
	
	$schema = array(
		'@context' => 'https://schema.org',
		'@type' => 'LocalBusiness',
		'@id' => home_url( '/#business' ),
		'name' => get_bloginfo( 'name' ),
		'description' => 'Nutrithérapeute à Bruxelles. Accompagnement personnalisé en nutrithérapie : rééquilibrage alimentaire, nutrition sportive, troubles hormonaux et digestifs.',
		'url' => home_url( '/' ),
		'telephone' => $phone_clean,
		'email' => $email,
		'address' => array(
			'@type' => 'PostalAddress',
			'addressLocality' => 'Ixelles',
			'addressRegion' => 'Bruxelles-Capitale',
			'addressCountry' => 'BE',
			'streetAddress' => 'Flagey'
		),
		'geo' => array(
			'@type' => 'GeoCoordinates',
			'latitude' => '50.8270',
			'longitude' => '4.3744'
		),
		'priceRange' => '$$',
		'image' => array(
			get_template_directory_uri() . '/logo-nutriflow-blue-navy.png'
		),
		'sameAs' => array(
			nutriflow_get_option( 'instagram_url', 'https://www.instagram.com/nutriflow.florence/' ),
			nutriflow_get_option( 'linkedin_url', 'https://www.linkedin.com/in/florence-van-hecke-30386712b/' )
		),
		'areaServed' => array(
			'@type' => 'City',
			'name' => 'Bruxelles'
		),
		'hasOfferCatalog' => array(
			'@type' => 'OfferCatalog',
			'name' => 'Services de nutrithérapie',
			'itemListElement' => array(
				array(
					'@type' => 'Offer',
					'itemOffered' => array(
						'@type' => 'Service',
						'name' => 'Consultation en nutrithérapie',
						'description' => 'Accompagnement personnalisé en nutrithérapie'
					)
				)
			)
		)
	);

	// Add person information
	$schema['founder'] = array(
		'@type' => 'Person',
		'name' => 'Florence Van Hecke',
		'jobTitle' => 'Nutrithérapeute',
		'email' => $email
	);

	echo '<script type="application/ld+json">' . "\n";
	echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
	echo "\n" . '</script>' . "\n";

	// Add BreadcrumbList schema on singular posts/pages
	if ( is_singular() ) {
		$breadcrumb_schema = array(
			'@context' => 'https://schema.org',
			'@type' => 'BreadcrumbList',
			'itemListElement' => array(
				array(
					'@type' => 'ListItem',
					'position' => 1,
					'name' => 'Accueil',
					'item' => home_url( '/' )
				)
			)
		);

		if ( is_page() ) {
			$breadcrumb_schema['itemListElement'][] = array(
				'@type' => 'ListItem',
				'position' => 2,
				'name' => get_the_title(),
				'item' => get_permalink()
			);
		}

		echo '<script type="application/ld+json">' . "\n";
		echo wp_json_encode( $breadcrumb_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
		echo "\n" . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'nutriflow_add_schema_markup', 5 );

/**
 * Optimize title tag
 */
function nutriflow_seo_title( $title, $sep = '|' ) {
	if ( is_feed() ) {
		return $title;
	}

	if ( is_front_page() ) {
		return get_bloginfo( 'name' ) . ' - Nutrithérapeute à Bruxelles';
	}

	if ( is_singular() ) {
		$seo_title = nutriflow_get_seo_title();
		return $seo_title . ' ' . $sep . ' ' . get_bloginfo( 'name' );
	}

	return $title;
}
add_filter( 'wp_title', 'nutriflow_seo_title', 10, 2 );
add_filter( 'document_title_parts', function( $title ) {
	if ( is_front_page() ) {
		$title['title'] = get_bloginfo( 'name' ) . ' - Nutrithérapeute à Bruxelles';
		unset( $title['tagline'] );
	}
	return $title;
}, 10, 1 );

/**
 * Ensure images have alt text
 */
function nutriflow_add_image_alt( $attr, $attachment, $size ) {
	if ( empty( $attr['alt'] ) && $attachment->post_parent ) {
		$attr['alt'] = get_the_title( $attachment->post_parent );
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'nutriflow_add_image_alt', 10, 3 );

/**
 * Enable XML sitemap (WordPress 5.5+)
 */
function nutriflow_enable_xml_sitemap() {
	// WordPress core sitemap is enabled by default in WP 5.5+
	// Just ensure it's accessible
	if ( ! get_option( 'blog_public' ) ) {
		update_option( 'blog_public', 1 );
	}
}
add_action( 'init', 'nutriflow_enable_xml_sitemap' );

/**
 * Add robots meta for search pages and 404
 */
function nutriflow_robots_meta() {
	if ( is_404() || is_search() ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
	}
}
add_action( 'wp_head', 'nutriflow_robots_meta', 1 );

