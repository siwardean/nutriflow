<?php
/**
 * Nutriflow functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Nutriflow
 */

if ( ! defined( 'NUTRIFLOW_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'NUTRIFLOW_VERSION', '0.1.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function nutriflow_setup() {
	// Add default posts and comments RSS feed links to head.
	// phpcs:ignore // add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary Menu', 'nutriflow' ),
			'menu-2' => esc_html__( 'Footer Menu', 'nutriflow' ),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * Hybrid Theme - Block Editor Support
	 * Enable support for block styles and editor styles
	 */
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );

	// Responsive embeds
	add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', 'nutriflow_setup' );

/**
 * Custom image sizes.
 *
 * @link https://developer.wordpress.org/reference/functions/add_image_size/
 * @link https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/
 */
function nutriflow_image_size() {

	add_image_size( 'fullwidth', 1200 ); // 1200 pixels wide with unlimited height.
	add_image_size( 'team', 370, 370, true ); // true = cropped.
}
add_action( 'after_setup_theme', 'nutriflow_image_size' );

/**
 * Enqueue scripts and styles.
 */
function nutriflow_scripts() {
	// styles.
	wp_enqueue_style( 'nutriflow-wp', get_stylesheet_uri(), array(), NUTRIFLOW_VERSION );
	wp_enqueue_style( 'modern-normalize', '//cdn.jsdelivr.net/npm/modern-normalize@3.0.1/modern-normalize.min.css', array(), NUTRIFLOW_VERSION );
	// wp_enqueue_style( 'nutriflow-style', get_template_directory_uri() . '/assets/css/style.css', array(), NUTRIFLOW_VERSION );
	// javascript.
	// wp_enqueue_script( 'nutriflow-script', get_template_directory_uri() . '/assets/js/script.min.js', array( 'jquery' ), NUTRIFLOW_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'nutriflow_scripts' );

/**
 * Enqueue scripts and styles for admin UI.
 */
function nutriflow_admin_scripts() {
	wp_enqueue_style( 'nutriflow-admin-style', get_template_directory_uri() . '/assets/css/admin.css', array(), NUTRIFLOW_VERSION );
}
add_action( 'admin_enqueue_scripts', 'nutriflow_admin_scripts' );

/**
 * Vite script
 *
 * Along with connecting Vite HMR with WP, this file also enqueues the themes main CSS and JS.
 *
 */
require 'inc/vite-inc.php';

/**
 * Changes markup for search form.
 *
 * @param resource $form Adjust search form markup for a better a11y UX.
 */
function nutriflow_search_form( $form ) {
	$form = '<form method="get" class="search-form" action="' . home_url( '/' ) . '" >
		<div role="search"><label for="Search">' . __( 'Search for:' ) . '</label>
			<input type="text" value="' . get_search_query() . '" name="s" id="Search" class="search-field">
			<input type="submit" id="searchsubmit" value="' . esc_attr__( 'Submit' ) . '" class="search-submit">
		</div>
	</form>';

	return $form;
}
add_filter( 'get_search_form', 'nutriflow_search_form', 40 );

/**
 * Includes parent page title in HTML <title> if on a child page.
 *
 * @param array $title_parts_array Returns document title for the current page.
 *
 * @link https://developer.wordpress.org/reference/hooks/document_title_parts/
 */
function nutriflow_custom_html_title( $title_parts_array ) {
	if ( is_home() ) {
		$title_parts_array['title'] = 'Blog';
		if ( get_query_var( 'paged' ) === 0 ) {
			$title_parts_array['title'] = 'Blog - Page 1';
		}
	} elseif ( is_front_page() ) {
		$title_parts_array['title'] = 'Accueil';
	} elseif ( wp_get_post_parent_id( get_the_ID() ) ) {
		$title_parts_array['title'] = get_the_title() . ' - ' . get_the_title( wp_get_post_parent_id( get_the_ID() ) );
	} else {
		$title_parts_array['title'] = get_the_title();
	}

	$title_parts_array['site'] = get_bloginfo( 'name' );

	return $title_parts_array;
}
add_filter( 'document_title_parts', 'nutriflow_custom_html_title', 10 );

/**
 * Adds toggle button for drop-down menu.
 */
require get_template_directory() . '/inc/class-button-sublevel-walker.php';

/**
 * Widget areas.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Pods Configuration
 */
require get_template_directory() . '/inc/pods-config.php';
require get_template_directory() . '/inc/pods-helpers.php';
require get_template_directory() . '/inc/pods-migration-data.php';

/**
 * Filtrer les groupes Pods pour n'afficher que ceux correspondant au template de page
 */
add_filter( 'pods_meta_groups_get', 'nutriflow_filter_pods_groups_by_page_template', 10, 4 );
function nutriflow_filter_pods_groups_by_page_template( $groups, $pod_type, $pod_name, $page_id ) {
	// Ne filtrer que pour le pod 'page' dans l'admin
	if ( $pod_type !== 'post_type' || $pod_name !== 'page' || ! is_admin() ) {
		return $groups;
	}
	
	// Récupérer le template de page actuel
	global $post;
	
	// Essayer d'obtenir l'ID de la page depuis plusieurs sources
	if ( empty( $page_id ) && isset( $_GET['post'] ) ) {
		$page_id = intval( $_GET['post'] );
	} elseif ( empty( $page_id ) && $post ) {
		$page_id = $post->ID;
	}
	
	// Si on n'a toujours pas d'ID, retourner tous les groupes
	if ( empty( $page_id ) ) {
		return $groups;
	}
	$page_template = get_page_template_slug( $page_id );
	
	// Si c'est la page d'accueil (front page)
	$is_front_page = ( get_option( 'page_on_front' ) == $page_id );
	if ( $is_front_page || $page_template === 'front-page.php' ) {
		$page_template = 'front-page.php';
	}
	
	// Si aucun template n'est défini, essayer de détecter par le slug
	if ( empty( $page_template ) ) {
		$page = get_post( $page_id );
		if ( $page ) {
			$page_slug = $page->post_name;
			// Mapper les slugs aux templates
			if ( $page_slug === 'accompagnement' ) {
				$page_template = 'page-accompagnement.php';
			} elseif ( $page_slug === 'a-propos' || $page_slug === 'a-propos-2' ) {
				$page_template = 'page-a-propos.php';
			} elseif ( $page_slug === 'contact' ) {
				$page_template = 'page-contact.php';
			}
		}
	}
	
	// Définir les groupes à afficher pour chaque template
	$allowed_groups = array();
	
	if ( $page_template === 'front-page.php' || $is_front_page ) {
		// Page d'accueil
		$allowed_groups = array(
			'homepage_hero',
			'homepage_about',
			'homepage_consult',
			'homepage_services',
			'homepage_testimonials',
		);
	} elseif ( $page_template === 'page-accompagnement.php' ) {
		// Page Accompagnement
		$allowed_groups = array(
			'accompagnement_hero',
			'accompagnement_pricing',
			'accompagnement_sportif',
			'accompagnement_location',
		);
	} elseif ( $page_template === 'page-a-propos.php' ) {
		// Page À propos
		$allowed_groups = array(
			'apropos_intro',
			'apropos_story',
		);
	} elseif ( $page_template === 'page-contact.php' ) {
		// Page Contact
		$allowed_groups = array(
			'contact',
		);
	}
	
	// Si aucun template spécifique n'est détecté, retourner tous les groupes
	if ( empty( $allowed_groups ) ) {
		return $groups;
	}
	
	// Définir les préfixes de champs autorisés pour chaque template
	$allowed_field_prefixes = array();
	
	if ( $page_template === 'front-page.php' || $is_front_page ) {
		// Page d'accueil
		$allowed_field_prefixes = array( 'homepage_hero', 'homepage_about', 'homepage_consult', 'homepage_services_heading', 'homepage_service_1_title', 'homepage_service_1_description', 'homepage_service_2_title', 'homepage_service_2_description', 'homepage_service_3_title', 'homepage_service_3_description', 'homepage_service_4_title', 'homepage_service_4_description', 'homepage_testimonials', 'homepage_testimonial_text', 'homepage_testimonial_author' );
	} elseif ( $page_template === 'page-accompagnement.php' ) {
		// Page Accompagnement
		$allowed_field_prefixes = array( 'hero_title', 'hero_background', 'hero_card_1_title', 'hero_card_1_content', 'hero_card_2_title', 'hero_card_2_content', 'hero_card_3_title', 'hero_card_3_content', 'hero_card_4_title', 'hero_card_4_content', 'pricing_', 'pricing_card_', 'pricing_title', 'sportif_title', 'sportif_card_title', 'sportif_content', 'location_title', 'location_info', 'location_image' );
	} elseif ( $page_template === 'page-a-propos.php' ) {
		// Page À propos
		$allowed_field_prefixes = array( 'intro_', 'gallery_', 'story_', 'formations_', 'sport_title', 'sport_content' );
	} elseif ( $page_template === 'page-contact.php' ) {
		// Page Contact
		$allowed_field_prefixes = array( 'contact_image', 'contact_title', 'contact_subtitle', 'contact_location', 'contact_schedule', 'contact_phone', 'contact_email', 'contact_cta_text', 'contact_button_text' );
	}
	
	// Si aucun template spécifique n'est détecté, retourner tous les groupes
	if ( empty( $allowed_field_prefixes ) ) {
		return $groups;
	}
	
	// Filtrer les groupes en vérifiant si leurs champs correspondent aux préfixes autorisés
	$filtered_groups = array();
	foreach ( $groups as $key => $group ) {
		$group_fields = array();
		
		// Extraire les champs du groupe
		if ( is_array( $group ) && isset( $group['fields'] ) && is_array( $group['fields'] ) ) {
			$group_fields = $group['fields'];
		} elseif ( is_object( $group ) && method_exists( $group, 'get_fields' ) ) {
			$group_fields = $group->get_fields();
		}
		
		// Vérifier si au moins un champ correspond aux préfixes autorisés
		$has_allowed_field = false;
		
		// Si le groupe est vide, le garder (pour éviter de masquer des groupes valides)
		if ( empty( $group_fields ) ) {
			continue;
		}
		
		foreach ( $group_fields as $field ) {
			$field_name = null;
			
			if ( is_array( $field ) && isset( $field['name'] ) ) {
				$field_name = $field['name'];
			} elseif ( is_object( $field ) ) {
				if ( isset( $field->name ) ) {
					$field_name = $field->name;
				} elseif ( method_exists( $field, 'get_name' ) ) {
					$field_name = $field->get_name();
				}
			}
			
			if ( $field_name ) {
				// Vérifier si le nom du champ correspond à un préfixe autorisé
				foreach ( $allowed_field_prefixes as $prefix ) {
					// Correspondance exacte ou correspondance par préfixe
					if ( $field_name === $prefix || strpos( $field_name, $prefix ) === 0 ) {
						$has_allowed_field = true;
						break 2; // Sortir des deux boucles
					}
				}
			}
		}
		
		// Garder le groupe s'il contient au moins un champ autorisé
		if ( $has_allowed_field ) {
			$filtered_groups[ $key ] = $group;
		}
	}
	
	return $filtered_groups;
}

/**
 * ACF Field Groups (Fallback - kept for backward compatibility)
 * Note: Pods is now the primary field manager
 */
if ( function_exists( 'acf_add_local_field_group' ) && ! function_exists( 'pods' ) ) {
require get_template_directory() . '/inc/acf-fields.php';
}

/**
 * Register block pattern category for Nutriflow
 */
function nutriflow_register_block_pattern_category() {
	register_block_pattern_category(
		'nutriflow',
		array(
			'label' => __( 'Nutriflow', 'nutriflow' ),
		)
	);
}
add_action( 'init', 'nutriflow_register_block_pattern_category' );

/**
 * Helper function to get Nutriflow option
 */
function nutriflow_get_option( $option, $default = '' ) {
	return get_theme_mod( 'nutriflow_' . $option, $default );
}

/**
 * Replace "sportif" with "sportif·ve" for inclusive content on Contenu page
 */
function nutriflow_replace_sportif_inclusive( $content ) {
	// Check if we're on the Contenu page
	if ( is_page() && strtolower( get_the_title() ) === 'contenu' ) {
		// Replace all instances of "sportif" with "sportif·ve" (case-insensitive)
		$content = preg_replace( '/\bsportif\b/i', 'sportif·ve', $content );
		$content = preg_replace( '/\bsportives\b/i', 'sportif·ves', $content );
	}
	return $content;
}
add_filter( 'the_content', 'nutriflow_replace_sportif_inclusive', 10 );

/**
 * Replace "sportif" with "sportif·ve" for inclusive content on Accompagnement page
 */
function nutriflow_replace_sportif_inclusive_accompagnement( $content ) {
	// Check if we're on the Accompagnement page template
	if ( is_page_template( 'page-accompagnement.php' ) ) {
		// Replace all instances of "sportif" with "sportif·ve" (case-insensitive)
		$content = preg_replace( '/\bsportif\b/i', 'sportif·ve', $content );
		$content = preg_replace( '/\bsportives\b/i', 'sportif·ves', $content );
	}
	return $content;
}
add_filter( 'the_content', 'nutriflow_replace_sportif_inclusive_accompagnement', 10 );

/**
 * Initialize default pricing card values if fields are empty
 */
function nutriflow_init_pricing_defaults( $post_id ) {
	// Only for pages using the Accompagnement template
	if ( get_page_template_slug( $post_id ) !== 'page-accompagnement.php' ) {
		return;
	}
	
	// Check if pricing fields are empty and initialize defaults
	if ( ! nutriflow_get_field( 'pricing_card_1_title', $post_id ) ) {
		update_field( 'pricing_card_1_title', 'Première consultation de +- 1h15', $post_id );
		update_field( 'pricing_card_1_price', '- 70 euros -', $post_id );
		update_field( 'pricing_card_1_items', '<ul><li><strong>Questionnaire</strong> préparatoire</li><li><strong>Analyse</strong> des 3 piliers : alimentation, hygiène de vie et supplémentation</li><li>Etablissement des <strong>objectifs</strong></li><li><strong>Premier bilan</strong> nutritionnel et conseils adaptés</li></ul>', $post_id );
	}
	
	if ( ! nutriflow_get_field( 'pricing_card_2_title', $post_id ) ) {
		update_field( 'pricing_card_2_title', 'Consultation de suivi de 30 à 45 minutes', $post_id );
		update_field( 'pricing_card_2_price', '- 40 euros -', $post_id );
		update_field( 'pricing_card_2_items', '<ul><li>Premier retour d\'expérience et <strong>questions</strong> - réponses</li><li>Evaluation des <strong>résultats et adaptation</strong> de l\'accompagnement si nécessaire</li></ul>', $post_id );
	}
	
	if ( ! nutriflow_get_field( 'pricing_card_3_title', $post_id ) ) {
		update_field( 'pricing_card_3_title', 'Pack \'Accompagnement sur 3 mois\'', $post_id );
		update_field( 'pricing_card_3_price', '<del>-190</del> 175 euros -', $post_id );
		update_field( 'pricing_card_3_items', '<ul><li>Total de <strong>4 consultations</strong></li><li>Pour un <strong>changement ancré</strong> sur du long terme</li><li>Payable en plusieurs fois</li><li>Echanges sur whatsapp entre les consultations</li></ul>', $post_id );
	}
}
add_action( 'acf/load_value', 'nutriflow_init_pricing_defaults_on_load', 10, 3 );
function nutriflow_init_pricing_defaults_on_load( $value, $post_id, $field ) {
	// Only initialize once when loading the page template field
	if ( $field['name'] === 'pricing_card_1_title' && empty( $value ) && get_page_template_slug( $post_id ) === 'page-accompagnement.php' ) {
		nutriflow_init_pricing_defaults( $post_id );
	}
	return $value;
}

/**
 * Debug: Check if ACF fields are loaded (for troubleshooting)
 */
function nutriflow_check_acf_fields() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	if ( isset( $_GET['nutriflow_debug_acf'] ) && $_GET['nutriflow_debug_acf'] === '1' ) {
		echo '<div class="notice notice-info"><p><strong>Debug ACF Fields:</strong></p>';
		
		if ( ! function_exists( 'acf_get_field_groups' ) ) {
			echo '<p>❌ ACF n\'est pas activé</p>';
		} else {
			$field_groups = acf_get_field_groups();
			echo '<p>✅ ACF est activé. Nombre de groupes de champs: ' . count( $field_groups ) . '</p>';
			
			$accompagnement_group = acf_get_field_group( 'group_accompagnement_page' );
			if ( $accompagnement_group ) {
				echo '<p>✅ Le groupe "Contenu Accompagnement" est chargé</p>';
				echo '<pre>' . print_r( $accompagnement_group, true ) . '</pre>';
			} else {
				echo '<p>❌ Le groupe "Contenu Accompagnement" n\'est pas trouvé</p>';
			}
		}
		
		echo '</div>';
	}
}
add_action( 'admin_notices', 'nutriflow_check_acf_fields' );

/**
 * Add meta box to display media used on all pages
 */
function nutriflow_add_page_media_meta_box() {
	add_meta_box(
		'nutriflow-page-media',
		__( 'Médias utilisés sur la page', 'nutriflow' ),
		'nutriflow_page_media_meta_box_callback',
		'page',
		'side',
		'low'
	);
}
add_action( 'add_meta_boxes', 'nutriflow_add_page_media_meta_box' );

/**
 * Display media meta box content for all pages
 */
function nutriflow_page_media_meta_box_callback( $post ) {
	$template = get_page_template_slug( $post->ID );
	$images_found = array();
	
	// Détecter le type de page et les images associées
	if ( $template === 'page-accompagnement.php' ) {
		// Page Accompagnement
		$hero_bg = nutriflow_get_field( 'hero_background', $post->ID );
		if ( $hero_bg ) {
			$images_found[] = array(
				'label' => 'Image de fond Hero',
				'field_name' => 'hero_background',
				'image' => $hero_bg,
			);
		} else {
			$images_found[] = array(
				'label' => 'Image de fond Hero',
				'field_name' => 'hero_background',
				'image' => false,
				'default' => get_template_directory_uri() . '/assets/images/services/peppers-bg.jpg',
			);
		}
		
		$location_image = nutriflow_get_field( 'location_image', $post->ID );
		if ( $location_image ) {
			$images_found[] = array(
				'label' => 'Image Localisation',
				'field_name' => 'location_image',
				'image' => $location_image,
			);
		} else {
			$images_found[] = array(
				'label' => 'Image Localisation',
				'field_name' => 'location_image',
				'image' => false,
				'default' => get_template_directory_uri() . '/assets/images/location/cooking.jpg',
			);
		}
	} elseif ( $template === 'page-a-propos.php' ) {
		// Page À propos - Galerie d'images
		$gallery = nutriflow_get_field( 'gallery_images', $post->ID );
		if ( $gallery && is_array( $gallery ) && ! empty( $gallery ) ) {
			// Images définies dans le champ
			foreach ( $gallery as $index => $image ) {
				$images_found[] = array(
					'label' => 'Galerie - Image ' . ( $index + 1 ),
					'field_name' => 'gallery_images',
					'image' => $image,
					'index' => $index,
				);
			}
		} else {
			// Aucune image définie - Afficher les 4 images par défaut avec liens
			$default_gallery_images = array(
				'gallery-1.jpg',
				'gallery-2.jpg',
				'gallery-3.jpg',
				'gallery-4.jpg',
			);
			foreach ( $default_gallery_images as $index => $image_filename ) {
				$image_url = get_template_directory_uri() . '/assets/images/about/' . $image_filename;
				$images_found[] = array(
					'label' => 'Galerie - Image ' . ( $index + 1 ) . ' (par défaut)',
					'field_name' => 'gallery_images',
					'image' => false,
					'default' => $image_url,
					'default_filename' => $image_filename,
				);
			}
		}
	} elseif ( $template === 'page-contact.php' ) {
		// Page Contact
		$contact_image = nutriflow_get_field( 'contact_image', $post->ID );
		if ( $contact_image ) {
			$images_found[] = array(
				'label' => 'Image Contact',
				'field_name' => 'contact_image',
				'image' => $contact_image,
			);
		} else {
			$images_found[] = array(
				'label' => 'Image Contact',
				'field_name' => 'contact_image',
				'image' => false,
				'default' => get_template_directory_uri() . '/assets/images/contact/florence-kitchen.jpg',
			);
		}
	} elseif ( $post->ID == get_option( 'page_on_front' ) || $template === 'front-page.php' ) {
		// Page d'accueil
		$hero_image = nutriflow_get_field( 'homepage_hero_image', $post->ID );
		if ( $hero_image ) {
			$images_found[] = array(
				'label' => 'Image Hero',
				'field_name' => 'homepage_hero_image',
				'image' => $hero_image,
			);
		} else {
			$images_found[] = array(
				'label' => 'Image Hero',
				'field_name' => 'homepage_hero_image',
				'image' => false,
				'default' => get_template_directory_uri() . '/assets/images/hero/hero-kitchen.jpg',
			);
		}
		
		$about_image = nutriflow_get_field( 'homepage_about_image', $post->ID );
		if ( $about_image ) {
			$images_found[] = array(
				'label' => 'Image À propos',
				'field_name' => 'homepage_about_image',
				'image' => $about_image,
			);
		} else {
			$images_found[] = array(
				'label' => 'Image À propos',
				'field_name' => 'homepage_about_image',
				'image' => false,
				'default' => get_template_directory_uri() . '/assets/images/about/pool-woman.jpg',
			);
		}
		
		$consult_image = nutriflow_get_field( 'homepage_consult_image', $post->ID );
		if ( $consult_image ) {
			$images_found[] = array(
				'label' => 'Image Consultation',
				'field_name' => 'homepage_consult_image',
				'image' => $consult_image,
			);
		} else {
			$images_found[] = array(
				'label' => 'Image Consultation',
				'field_name' => 'homepage_consult_image',
				'image' => false,
				'default' => get_template_directory_uri() . '/assets/images/consultation/market-woman.jpg',
			);
		}
	}
	
	if ( empty( $images_found ) ) {
		echo '<p style="padding: 10px; color: #666; font-style: italic;">Aucune image personnalisable détectée sur cette page.</p>';
		return;
	}
	
	?>
	<div style="padding: 10px 0;">
		<?php foreach ( $images_found as $item ) : ?>
			<div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #ddd;">
				<h4 style="margin-top: 0; margin-bottom: 10px;"><?php echo esc_html( $item['label'] ); ?></h4>
				
				<?php if ( $item['image'] ) : 
					$img_url = $item['image']['sizes']['thumbnail'] ?? $item['image']['url'];
					$edit_url = admin_url( 'post.php?post=' . $item['image']['ID'] . '&action=edit' );
				?>
					<div style="margin-bottom: 10px;">
						<img src="<?php echo esc_url( $img_url ); ?>" 
							 alt="<?php echo esc_attr( $item['image']['alt'] ?? '' ); ?>" 
							 style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; background: #fff;">
					</div>
					<p style="margin: 5px 0; font-size: 12px; color: #666;">
						<strong>Fichier:</strong> <?php echo esc_html( $item['image']['filename'] ?? '' ); ?><br>
						<strong>Taille:</strong> <?php echo esc_html( $item['image']['width'] ?? '' ); ?> × <?php echo esc_html( $item['image']['height'] ?? '' ); ?> px<br>
						<strong>URL:</strong> <a href="<?php echo esc_url( $item['image']['url'] ); ?>" target="_blank" style="font-size: 11px; word-break: break-all;"><?php echo esc_html( $item['image']['url'] ); ?></a>
					</p>
					<p style="margin: 10px 0 0 0;">
						<a href="<?php echo esc_url( $edit_url ); ?>" 
						   class="button button-small" 
						   target="_blank">
							Modifier l'image
						</a>
						<span style="font-size: 11px; color: #666; margin-left: 10px;">
							Pour changer cette image, modifiez le champ "<?php echo esc_html( $item['field_name'] ); ?>" ci-dessous.
						</span>
					</p>
				<?php else : ?>
					<?php if ( isset( $item['default'] ) && is_string( $item['default'] ) && strpos( $item['default'], 'http' ) === 0 ) : 
						// Image par défaut avec URL - Afficher l'image et le lien
						$default_url = $item['default'];
						$default_filename = isset( $item['default_filename'] ) ? $item['default_filename'] : basename( $default_url );
					?>
						<div style="margin-bottom: 10px;">
							<a href="<?php echo esc_url( $default_url ); ?>" target="_blank">
								<img src="<?php echo esc_url( $default_url ); ?>" 
									 alt="<?php echo esc_attr( $default_filename ); ?>" 
									 style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; background: #fff; cursor: pointer;">
							</a>
						</div>
						<p style="margin: 5px 0; font-size: 12px; color: #666;">
							<strong>Fichier par défaut:</strong> <?php echo esc_html( $default_filename ); ?><br>
							<strong>URL:</strong> <a href="<?php echo esc_url( $default_url ); ?>" target="_blank" style="font-size: 11px; word-break: break-all;"><?php echo esc_html( $default_url ); ?></a>
						</p>
						<p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">
							<em>Cliquez sur l'image pour l'agrandir. Pour remplacer cette image par défaut, remplissez le champ "<?php echo esc_html( $item['field_name'] ); ?>" ci-dessous.</em>
						</p>
					<?php else : ?>
						<p style="color: #666; font-style: italic; margin: 5px 0;">Aucune image définie.</p>
						<?php if ( isset( $item['default'] ) ) : ?>
							<p style="font-size: 12px; color: #999; margin: 5px 0;">
								<strong>Image par défaut:</strong><br>
								<?php if ( is_string( $item['default'] ) && strpos( $item['default'], 'http' ) === 0 ) : ?>
									<a href="<?php echo esc_url( $item['default'] ); ?>" target="_blank" style="word-break: break-all;"><?php echo esc_html( $item['default'] ); ?></a>
								<?php else : ?>
									<?php echo esc_html( $item['default'] ); ?>
								<?php endif; ?>
							</p>
						<?php endif; ?>
						<p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">
							Pour ajouter une image, remplissez le champ "<?php echo esc_html( $item['field_name'] ); ?>" ci-dessous.
						</p>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

