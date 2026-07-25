<?php
/**
 * Pods Configuration for Nutriflow Theme
 * 
 * This file registers Pods fields for all pages programmatically
 * 
 * @package Nutriflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if a field name is reserved
 */
function nutriflow_is_reserved_field_name( $name ) {
	// Liste manuelle des mots réservés pour WordPress posts/pages
	// Basé sur pods_reserved_keywords mais ne dépend pas de Pods
	$reserved_keywords = array(
		'id', 'ID', 'attachment', 'attachment_id', 'author', 'author_name',
		'category', 'link_category', 'name', 'p', 'page', 'paged', 'post',
		'post_format', 'post_mime_type', 'post_status', 'post_tag',
		'post_thumbnail', 'post_thumbnail_url', 'post_type', 's', 'search',
		'tag', 'taxonomy', 'term', 'terms', 'title', 'type',
		'calendar', 'cat', 'custom', 'day', 'hour', 'minute', 'monthnum',
		'nav_menu', 'nonce', 'offset', 'order', 'orderby', 'page_id',
		'pagename', 'posts', 'posts_per_page', 'preview', 'second', 'year',
	);
	
	return in_array( strtolower( $name ), $reserved_keywords, true );
}

/**
 * Register Pods fields for pages
 */
function nutriflow_setup_pods_fields() {
	if ( ! function_exists( 'pods_api' ) ) {
		return;
	}
	
	// Ne s'exécute que dans l'admin pour éviter les erreurs sur le front-end
	if ( ! is_admin() ) {
		return;
	}
	
	try {
		$api = pods_api();
		
		// Vérifier si le Pod "page" existe et l'étendre si nécessaire
		$pod = $api->load_pod( array( 'name' => 'page' ) );
		
		if ( empty( $pod ) || ( is_array( $pod ) && isset( $pod['type'] ) && $pod['type'] !== 'post_type' ) ) {
			// Étendre le type de contenu "page"
			$pod_data = array(
				'name' => 'page',
				'type' => 'post_type',
				'label' => 'Page',
				'storage' => 'meta',
			);
			
			$pod_result = $api->save_pod( $pod_data );
			if ( is_wp_error( $pod_result ) ) {
				error_log( 'Pods: Error extending page pod - ' . $pod_result->get_error_message() );
				return;
			}
		}
		
		// Recharger le pod pour avoir toutes les données
		$pod = $api->load_pod( array( 'name' => 'page' ) );
		
		if ( empty( $pod ) || is_wp_error( $pod ) ) {
			return;
		}
	} catch ( Exception $e ) {
		error_log( 'Pods: Exception during pod setup - ' . $e->getMessage() );
		return;
	}
	
	$pod_id = is_array( $pod ) ? $pod['id'] : ( is_object( $pod ) ? $pod->get_id() : 0 );
	if ( empty( $pod_id ) ) {
		return;
	}
	
	// Définir tous les champs à créer
	$fields = nutriflow_get_pods_fields_config();
	
	// Créer les groupes et les champs
	foreach ( $fields as $group_name => $group_data ) {
		try {
		$group_obj = null;
		
		// Créer le groupe si spécifié
		if ( isset( $group_data['group'] ) ) {
			$group_name = $group_data['group']['name'];
			
			// Vérifier si le nom du groupe est réservé
			if ( nutriflow_is_reserved_field_name( $group_name ) ) {
				error_log( 'Pods Group Skipped (reserved): ' . $group_name );
				continue; // Ignorer ce groupe
			}
			
			try {
				// Vérifier si le groupe existe déjà
				$existing_group = $api->load_group( array(
					'pod' => $pod,
					'name' => $group_name,
				) );
				
				if ( empty( $existing_group ) ) {
					$group_params = array(
						'pod_data' => $pod,
						'name' => $group_name,
						'label' => $group_data['group']['label'],
						'weight' => isset( $group_data['group']['weight'] ) ? $group_data['group']['weight'] : 0,
					);
					
					$group_id = $api->save_group( $group_params );
					if ( $group_id && ! is_wp_error( $group_id ) ) {
						$group_obj = $api->load_group( array( 'id' => $group_id ) );
					}
				} else {
					$group_obj = $existing_group;
				}
			} catch ( Exception $e ) {
				error_log( 'Pods: Exception creating group ' . $group_name . ' - ' . $e->getMessage() );
				continue; // Passer au groupe suivant
			}
		}
		
		// Créer les champs du groupe
		if ( isset( $group_data['fields'] ) && is_array( $group_data['fields'] ) ) {
			foreach ( $group_data['fields'] as $field_config ) {
				// Vérifier si le nom du champ est réservé
				if ( nutriflow_is_reserved_field_name( $field_config['name'] ) ) {
					error_log( 'Pods Field Skipped (reserved): ' . $field_config['name'] );
					continue; // Ignorer les noms réservés
				}
				
				// Vérifier si le champ existe déjà
				$existing_field = $api->load_field( array(
					'pod' => $pod,
					'name' => $field_config['name'],
				) );
				
				// Si le champ existe déjà, on récupère son ID pour la mise à jour
				$existing_field_id = null;
				if ( ! empty( $existing_field ) ) {
					if ( is_array( $existing_field ) && isset( $existing_field['id'] ) ) {
						$existing_field_id = $existing_field['id'];
					} elseif ( is_object( $existing_field ) && method_exists( $existing_field, 'get_id' ) ) {
						$existing_field_id = $existing_field->get_id();
					}
					if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
						error_log( 'Pods Field Already Exists: ' . $field_config['name'] . ' (ID: ' . $existing_field_id . ') - Will update' );
					}
				}
				
				$field_params = array(
					'pod_data' => $pod,
					'name' => $field_config['name'],
					'label' => $field_config['label'],
					'type' => $field_config['type'],
					'weight' => isset( $field_config['weight'] ) ? $field_config['weight'] : 0,
				);
				
				// Initialiser les options si nécessaire
				if ( ! isset( $field_params['options'] ) ) {
					$field_params['options'] = array();
				}
				
				// Ajouter la valeur par défaut si définie (Pods utilise 'default' dans les options)
				if ( isset( $field_config['default_value'] ) ) {
					$field_params['options']['default'] = $field_config['default_value'];
					$field_params['options']['default_value'] = $field_config['default_value'];
				}
				
				// Assigner au groupe si spécifié
				if ( $group_obj ) {
					$field_params['group'] = $group_obj;
				}
				
				// Options spécifiques pour les champs de type file
				if ( $field_config['type'] === 'file' ) {
					if ( ! isset( $field_params['options'] ) ) {
						$field_params['options'] = array();
					}
					// Dans Pods, les options de fichier sont dans le tableau options
					$field_params['options']['file_format_type'] = isset( $field_config['file_format_type'] ) ? $field_config['file_format_type'] : 'single';
					$field_params['options']['file_type'] = isset( $field_config['file_type'] ) ? $field_config['file_type'] : 'images';
					// Aussi définir au niveau racine pour compatibilité
					$field_params['file_format_type'] = isset( $field_config['file_format_type'] ) ? $field_config['file_format_type'] : 'single';
					$field_params['file_type'] = isset( $field_config['file_type'] ) ? $field_config['file_type'] : 'images';
				}
				
				// Options pour les champs de type table (équivalent des repeaters)
				if ( $field_config['type'] === 'table' && isset( $field_config['options'] ) ) {
					if ( ! isset( $field_params['options'] ) ) {
						$field_params['options'] = array();
					}
					
					// Configurer les colonnes de la table si spécifiées
					if ( isset( $field_config['options']['table_cols'] ) && is_array( $field_config['options']['table_cols'] ) ) {
						// Dans Pods, les colonnes de table sont configurées via l'option 'table_columns'
						$table_columns = array();
						$col_weight = 0;
						
						foreach ( $field_config['options']['table_cols'] as $col_name => $col_config ) {
							$col_data = array(
								'name' => $col_name,
								'label' => isset( $col_config['label'] ) ? $col_config['label'] : ucfirst( str_replace( '_', ' ', $col_name ) ),
								'type' => isset( $col_config['type'] ) ? $col_config['type'] : 'text',
								'weight' => $col_weight++,
							);
							
							// Ajouter les options supplémentaires si présentes
							if ( isset( $col_config['required'] ) ) {
								$col_data['required'] = $col_config['required'];
							}
							
							$table_columns[ $col_name ] = $col_data;
						}
						
						// Stocker les colonnes dans les options du champ
						// Note: La structure exacte peut varier selon la version de Pods
						$field_params['options']['table_columns'] = $table_columns;
						
						// Alternative: essayer avec différentes clés possibles
						$field_params['options']['table_cols'] = $table_columns;
					}
				}
				
				// Ajouter l'ID si le champ existe déjà (pour mise à jour)
				if ( $existing_field_id ) {
					$field_params['id'] = $existing_field_id;
				}
				
				// Sauvegarder le champ (création ou mise à jour)
				try {
					$result = $api->save_field( $field_params, true, false, true );
					if ( is_wp_error( $result ) ) {
						// Enregistrer l'erreur mais continuer avec les autres champs
						error_log( 'Pods Field Error: ' . $field_config['name'] . ' - ' . $result->get_error_message() );
					} elseif ( $result ) {
						if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
							$action = $existing_field_id ? 'Updated' : 'Created';
							error_log( 'Pods Field ' . $action . ' Successfully: ' . $field_config['name'] );
						}
					}
				} catch ( Exception $e ) {
					// Enregistrer l'exception mais continuer avec les autres champs
					error_log( 'Pods Field Exception: ' . $field_config['name'] . ' - ' . $e->getMessage() );
				}
			}
		}
		} catch ( Exception $e ) {
			error_log( 'Pods: Exception in group ' . $group_name . ' - ' . $e->getMessage() );
			continue; // Continuer avec le groupe suivant
		}
	}
}
// Activation de la création automatique des champs Pods
// Cette fonction s'exécute lorsque Pods est initialisé
add_action( 'pods_init', 'nutriflow_setup_pods_fields', 99 );

/**
 * Get all Pods fields configuration
 * 
 * @return array Fields configuration organized by groups
 */
function nutriflow_get_pods_fields_config() {
	return array(
		// Page d'accueil - Section Hero
		'homepage_hero' => array(
			'group' => array(
				'name' => 'homepage_hero',
				'label' => 'Section Hero (Page d\'accueil)',
				'weight' => 0,
			),
			'fields' => array(
				array(
					'name' => 'homepage_hero_title',
					'label' => 'Titre Hero',
					'type' => 'text',
					'default_value' => 'NUTRIFLOW',
					'weight' => 0,
				),
				array(
					'name' => 'homepage_hero_subtitle',
					'label' => 'Sous-titre Hero',
					'type' => 'text',
					'default_value' => '- NUTRITHÉRAPEUTE À BRUXELLES -',
					'weight' => 1,
				),
				array(
					'name' => 'homepage_hero_description',
					'label' => 'Description Hero',
					'type' => 'wysiwyg',
					'default_value' => 'Accompagnement en nutrithérapie: rééquilibrage alimentaire, nutrition sportive, troubles hormonaux et digestifs.',
					'weight' => 2,
				),
				array(
					'name' => 'homepage_hero_button',
					'label' => 'Texte du bouton Hero',
					'type' => 'text',
					'default_value' => 'PRENDRE RDV',
					'weight' => 3,
				),
				array(
					'name' => 'homepage_hero_image',
					'label' => 'Image Hero',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 4,
				),
			),
		),
		
		// Page d'accueil - Section À propos
		'homepage_about' => array(
			'group' => array(
				'name' => 'homepage_about',
				'label' => 'Section À propos (Page d\'accueil)',
				'weight' => 10,
			),
			'fields' => array(
				array(
					'name' => 'homepage_about_title',
					'label' => 'Titre À propos',
					'type' => 'text',
					'default_value' => 'Qui suis-je ?',
					'weight' => 0,
				),
				array(
					'name' => 'homepage_about_text_left',
					'label' => 'Texte À propos (gauche)',
					'type' => 'wysiwyg',
					'weight' => 1,
				),
				array(
					'name' => 'homepage_about_text_right',
					'label' => 'Texte À propos (droite)',
					'type' => 'wysiwyg',
					'weight' => 2,
				),
				array(
					'name' => 'homepage_about_image',
					'label' => 'Image À propos',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 3,
				),
			),
		),
		
		// Page d'accueil - Section Consultation
		'homepage_consult' => array(
			'group' => array(
				'name' => 'homepage_consult',
				'label' => 'Section Consultation (Page d\'accueil)',
				'weight' => 20,
			),
			'fields' => array(
				array(
					'name' => 'homepage_consult_title',
					'label' => 'Titre Consultation',
					'type' => 'text',
					'default_value' => 'L\'objectif de la consultation',
					'weight' => 0,
				),
				array(
					'name' => 'homepage_consult_text',
					'label' => 'Texte Consultation',
					'type' => 'wysiwyg',
					'weight' => 1,
				),
				array(
					'name' => 'homepage_consult_button',
					'label' => 'Bouton Consultation',
					'type' => 'text',
					'default_value' => 'DÉCOUVRIR LES ACCOMPAGNEMENTS',
					'weight' => 2,
				),
				array(
					'name' => 'homepage_consult_image',
					'label' => 'Image Consultation',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 3,
				),
			),
		),
		
		// Page d'accueil - Section Services
		'homepage_services' => array(
			'group' => array(
				'name' => 'homepage_services',
				'label' => 'Section Services (Page d\'accueil)',
				'weight' => 30,
			),
			'fields' => array(
				array(
					'name' => 'homepage_services_heading',
					'label' => 'Titre Section Services',
					'type' => 'text',
					'default_value' => 'Je t\'accompagne pour',
					'weight' => 0,
				),
				array(
					'name' => 'homepage_service_1_title',
					'label' => 'Service 1 - Titre',
					'type' => 'text',
					'default_value' => 'Rééquilibrage alimentaire',
					'weight' => 1,
				),
				array(
					'name' => 'homepage_service_1_description',
					'label' => 'Service 1 - Description',
					'type' => 'wysiwyg',
					'default_value' => 'Rééquilibrer ton alimentation et/ou perdre du poids.',
					'weight' => 2,
				),
				array(
					'name' => 'homepage_service_2_title',
					'label' => 'Service 2 - Titre',
					'type' => 'text',
					'default_value' => 'Troubles hormonaux',
					'weight' => 3,
				),
				array(
					'name' => 'homepage_service_2_description',
					'label' => 'Service 2 - Description',
					'type' => 'wysiwyg',
					'default_value' => 'Soulager tes symptômes et rééquilibrer les troubles hormonaux : SPM, endométriose, SOPK,...',
					'weight' => 4,
				),
				array(
					'name' => 'homepage_service_3_title',
					'label' => 'Service 3 - Titre',
					'type' => 'text',
					'default_value' => 'Nutrition & performances sportives',
					'weight' => 5,
				),
				array(
					'name' => 'homepage_service_3_description',
					'label' => 'Service 3 - Description',
					'type' => 'wysiwyg',
					'default_value' => 'Améliorer tes performances et mieux comprendre l\'impact de ton alimentation sur tes résultats.',
					'weight' => 6,
				),
				array(
					'name' => 'homepage_service_4_title',
					'label' => 'Service 4 - Titre',
					'type' => 'text',
					'default_value' => 'Troubles digestifs',
					'weight' => 7,
				),
				array(
					'name' => 'homepage_service_4_description',
					'label' => 'Service 4 - Description',
					'type' => 'wysiwyg',
					'default_value' => 'Prendre soin de ton microbiote mais également pour soulager les troubles digestifs chroniques (SII, MICI, SIBO,..).',
					'weight' => 8,
				),
			),
		),
		
		// Page d'accueil - Section Témoignages
		'homepage_testimonials' => array(
			'group' => array(
				'name' => 'homepage_testimonials',
				'label' => 'Section Témoignages (Page d\'accueil)',
				'weight' => 40,
			),
			'fields' => array(
				array(
					'name' => 'homepage_testimonials_heading',
					'label' => 'Titre Témoignages',
					'type' => 'text',
					'default_value' => 'Témoignages',
					'weight' => 0,
				),
				array(
					'name' => 'homepage_testimonial_1_text',
					'label' => 'Texte du témoignage 1',
					'type' => 'wysiwyg',
					'default_value' => '<p style="text-align: center;"><em>J\'ai contacté Florence afin de mieux comprendre quelle est l\'alimentation qui me correspondrait le mieux, et adopter des habitudes saines sur le long terme. Son écoute attentive et son accompagnement personnalisé m\'ont beaucoup appris, et le défi à été réussi. Je la recommande vivement.</em></p>',
					'weight' => 1,
				),
				array(
					'name' => 'homepage_testimonial_1_author',
					'label' => 'Auteur du témoignage 1',
					'type' => 'text',
					'default_value' => 'Nina Rozenberg',
					'weight' => 2,
				),
				array(
					'name' => 'homepage_testimonial_2_text',
					'label' => 'Texte du témoignage 2',
					'type' => 'wysiwyg',
					'default_value' => '<p style="text-align: center;"><em>Dans le cadre de ma reprise du sport, j\'ai fait appel à Florence pour adopter des habitudes nutritionnelles adaptées et durables. Son accompagnement, à la fois clair et bienveillant, m\'a permis d\'améliorer ma nutrition de manière concrète. Je suis très satisfait de son suivi et je la remercie pour son aide précieuse.</em></p>',
					'weight' => 3,
				),
				array(
					'name' => 'homepage_testimonial_2_author',
					'label' => 'Auteur du témoignage 2',
					'type' => 'text',
					'default_value' => 'Siwar Madrane',
					'weight' => 4,
				),
			),
		),
		
		// Page Accompagnement - Section Hero
		'accompagnement_hero' => array(
			'group' => array(
				'name' => 'accompagnement_hero',
				'label' => 'Section Hero (Page Accompagnement)',
				'weight' => 100,
			),
			'fields' => array(
				array(
					'name' => 'hero_title',
					'label' => 'Titre Hero',
					'type' => 'text',
					'default_value' => 'Je t\'accompagne pour',
					'weight' => 0,
				),
				array(
					'name' => 'hero_background',
					'label' => 'Image de fond Hero',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 1,
				),
				array(
					'name' => 'hero_card_1_title',
					'label' => 'Carte 1 - Titre',
					'type' => 'text',
					'default_value' => 'Rééquilibrage alimentaire',
					'weight' => 2,
				),
				array(
					'name' => 'hero_card_1_content',
					'label' => 'Carte 1 - Contenu',
					'type' => 'wysiwyg',
					'default_value' => 'Rééquilibrer ton alimentation et/ou perdre du poids.',
					'weight' => 3,
				),
				array(
					'name' => 'hero_card_2_title',
					'label' => 'Carte 2 - Titre',
					'type' => 'text',
					'default_value' => 'Troubles hormonaux',
					'weight' => 4,
				),
				array(
					'name' => 'hero_card_2_content',
					'label' => 'Carte 2 - Contenu',
					'type' => 'wysiwyg',
					'default_value' => 'Soulager tes simptômes et rééquilibrer les troubles hormonaux : thyroïde, SPM, endométriose, SOPK,...',
					'weight' => 5,
				),
				array(
					'name' => 'hero_card_3_title',
					'label' => 'Carte 3 - Titre',
					'type' => 'text',
					'default_value' => 'Nutrition & performances sportives',
					'weight' => 6,
				),
				array(
					'name' => 'hero_card_3_content',
					'label' => 'Carte 3 - Contenu',
					'type' => 'wysiwyg',
					'default_value' => 'Améliorer tes performances et mieux comprendre l\'impact de ton alimentation sur tes résultats.',
					'weight' => 7,
				),
				array(
					'name' => 'hero_card_4_title',
					'label' => 'Carte 4 - Titre',
					'type' => 'text',
					'default_value' => 'Troubles digestifs',
					'weight' => 8,
				),
				array(
					'name' => 'hero_card_4_content',
					'label' => 'Carte 4 - Contenu',
					'type' => 'wysiwyg',
					'default_value' => 'Prendre soin de ton microbiote mais également pour soulager les troubles digestifs chroniques (SII, MICI, SIBO,..).',
					'weight' => 9,
				),
			),
		),
		
		// Page Accompagnement - Section Tarifs
		'accompagnement_pricing' => array(
			'group' => array(
				'name' => 'accompagnement_pricing',
				'label' => 'Section Tarifs (Page Accompagnement)',
				'weight' => 110,
			),
			'fields' => array(
				array(
					'name' => 'pricing_title',
					'label' => 'Titre Tarifs',
					'type' => 'text',
					'default_value' => 'Comment ?',
					'weight' => 0,
				),
				// Carte Tarif 1
				array(
					'name' => 'pricing_card_1_title',
					'label' => 'Carte Tarif 1 - Titre',
					'type' => 'text',
					'default_value' => 'Première consultation de 1h15',
					'weight' => 10,
				),
				array(
					'name' => 'pricing_card_1_price',
					'label' => 'Carte Tarif 1 - Prix',
					'type' => 'wysiwyg',
					'default_value' => '- 90 euros -',
					'weight' => 11,
				),
				array(
					'name' => 'pricing_card_1_items',
					'label' => 'Carte Tarif 1 - Liste des items',
					'type' => 'wysiwyg',
					'default_value' => '<ul><li><strong>Questionnaire</strong> préparatoire</li><li><strong>Analyse</strong> des 3 piliers : alimentation, hygiène de vie et supplémentation</li><li>Etablissement des <strong>objectifs</strong></li><li><strong>Premier bilan</strong> nutritionnel et conseils adaptés</li></ul>',
					'weight' => 12,
				),
				// Carte Tarif 2
				array(
					'name' => 'pricing_card_2_title',
					'label' => 'Carte Tarif 2 - Titre',
					'type' => 'text',
					'default_value' => 'Consultation de suivi de 45 minutes',
					'weight' => 20,
				),
				array(
					'name' => 'pricing_card_2_price',
					'label' => 'Carte Tarif 2 - Prix',
					'type' => 'wysiwyg',
					'default_value' => '- 55 euros -',
					'weight' => 21,
				),
				array(
					'name' => 'pricing_card_2_items',
					'label' => 'Carte Tarif 2 - Liste des items',
					'type' => 'wysiwyg',
					'default_value' => '<ul><li>Premier retour d\'expérience et <strong>questions</strong> - réponses</li><li>Evaluation des <strong>résultats et adaptation</strong> de l\'accompagnement si nécessaire</li></ul>',
					'weight' => 22,
				),
				// Carte Tarif 3
				array(
					'name' => 'pricing_card_3_title',
					'label' => 'Carte Tarif 3 - Titre',
					'type' => 'text',
					'default_value' => 'Pack \'Accompagnement sur 3 mois\'',
					'weight' => 30,
				),
				array(
					'name' => 'pricing_card_3_price',
					'label' => 'Carte Tarif 3 - Prix',
					'type' => 'wysiwyg',
					'default_value' => '<del>-255</del> 235 euros -',
					'weight' => 31,
				),
				array(
					'name' => 'pricing_card_3_items',
					'label' => 'Carte Tarif 3 - Liste des items',
					'type' => 'wysiwyg',
					'default_value' => '<ul><li>Total de <strong>4 consultations</strong></li><li>Pour un <strong>changement ancré</strong> sur du long terme</li><li>Payable en plusieurs fois</li><li>Echanges sur whatsapp entre les consultations</li></ul>',
					'weight' => 32,
				),
			),
		),
		
		// Page Accompagnement - Section Sportif
		'accompagnement_sportif' => array(
			'group' => array(
				'name' => 'accompagnement_sportif',
				'label' => 'Section Sportif (Page Accompagnement)',
				'weight' => 120,
			),
			'fields' => array(
				array(
					'name' => 'sportif_title',
					'label' => 'Titre Section Sportif',
					'type' => 'text',
					'default_value' => 'La nutrition sportive ?',
					'weight' => 0,
				),
				array(
					'name' => 'sportif_card_title',
					'label' => 'Titre Carte Sportif',
					'type' => 'text',
					'default_value' => 'Accompagnement sur mesure',
					'weight' => 1,
				),
				array(
					'name' => 'sportif_content',
					'label' => 'Contenu Sportif',
					'type' => 'wysiwyg',
					'default_value' => '<ul><li>Pour t\'aider à y voir plus clair dans les <strong>besoins spécifiques</strong> d\'un·e sportif·ve et adapter ton alimentation</li><li>Pour te <strong>préparer à un challenge sportif</strong> ou <strong>compétition</strong></li><li><strong>Bilan nutritionnel</strong> sur mesure</li></ul>',
					'weight' => 2,
				),
			),
		),
		
		// Page Accompagnement - Section Localisation
		'accompagnement_location' => array(
			'group' => array(
				'name' => 'accompagnement_location',
				'label' => 'Section Localisation (Page Accompagnement)',
				'weight' => 130,
			),
			'fields' => array(
				array(
					'name' => 'location_title',
					'label' => 'Titre Localisation',
					'type' => 'text',
					'default_value' => 'Où se passent mes consultations ?',
					'weight' => 0,
				),
				array(
					'name' => 'location_info',
					'label' => 'Informations Localisation',
					'type' => 'wysiwyg',
					'default_value' => '<p style="text-align: center;">A <strong>Ixelles (Clinica Vital)</strong> ou en visio</p><p style="text-align: center;">Le <strong>mercredi</strong> de 8h30 à 18h30 à la <strong>Clinica Vital</strong></p><p style="text-align: center;">Les <strong>jeudi</strong> et <strong>vendredi</strong> de 8h30 à 19h en visio</p><p style="text-align: center;">Téléphone : +32 486 920 962</p><p style="text-align: center;">Email : fl.vanhecke@gmail.com</p>',
					'weight' => 1,
				),
				array(
					'name' => 'location_image',
					'label' => 'Image Localisation',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 2,
				),
				array(
					'name' => 'consultation_schedule',
					'label' => 'Horaires par jour',
					'type' => 'wysiwyg',
					'default_value' => '<ul class="nf-schedule"><li><span class="nf-schedule__day">Mercredi</span> <span class="nf-schedule__time">8h30 – 18h30</span> — <a href="https://www.clinicavital.be" target="_blank" rel="noopener">Clinica Vital</a>, Chaussée de Wavre 133, 1050 Ixelles</li><li><span class="nf-schedule__day">Jeudi</span> <span class="nf-schedule__time">8h30 – 19h</span> — En visio</li><li><span class="nf-schedule__day">Vendredi</span> <span class="nf-schedule__time">8h30 – 19h</span> — En visio</li></ul>',
					'weight' => 3,
				),
			),
		),
		
		// Page À propos - Section Introduction
		'apropos_intro' => array(
			'group' => array(
				'name' => 'apropos_intro',
				'label' => 'Section Introduction (Page À propos)',
				'weight' => 200,
			),
			'fields' => array(
				array(
					'name' => 'intro_title',
					'label' => 'Titre Introduction',
					'type' => 'text',
					'default_value' => 'Qui suis-je ?',
					'weight' => 0,
				),
				array(
					'name' => 'intro_text',
					'label' => 'Texte Introduction',
					'type' => 'wysiwyg',
					'default_value' => 'Je suis Florence, nutrithérapeute passionnée par l\'impact de la nutrition sur notre <strong>santé globale.</strong> Mon <strong>approche</strong> est douce, basée sur la science et centrée sur l\'<strong>écoute du corps.</strong>',
					'weight' => 1,
				),
				array(
					'name' => 'gallery_images',
					'label' => 'Galerie d\'Images',
					'type' => 'file',
					'file_format_type' => 'multi',
					'file_type' => 'images',
					'weight' => 2,
				),
			),
		),
		
		// Page À propos - Section Parcours
		'apropos_story' => array(
			'group' => array(
				'name' => 'apropos_story',
				'label' => 'Section Parcours (Page À propos)',
				'weight' => 210,
			),
			'fields' => array(
				array(
					'name' => 'story_content',
					'label' => 'Mon Parcours',
					'type' => 'wysiwyg',
					'default_value' => 'Mon corps a été mon <strong>premier guide.</strong> Dès l\'adolescence, j\'ai été confrontée à des <strong>déséquilibres</strong> (eczéma, douleurs <strong>chroniques</strong>, troubles <strong>digestifs</strong>, acné) que la médecine moderne n\'expliquait pas. En cherchant à comprendre les <strong>causes</strong> de mes maux et à trouver des solutions, j\'ai découvert la <strong>puissance</strong> de la <strong>nutrition</strong> dans mon processus de guérison. Je me suis donc formée en nutrithérapie pendant plusieurs années.<br/><br/>Aujourd\'hui, je me sens alignée, ancrée, et <strong>connectée</strong> à mes rythmes.<br/><br/>Ce chemin personnel m\'a donné des <strong>clés précieuses</strong>, que je transmets avec nuance et bienveillance. J\'aide les personnes à se <strong>reconnecter</strong> à leur corps, à comprendre leurs <strong>symptômes</strong> et à retrouver leur <strong>vitalité</strong> via leur alimentation, en écoutant ce que leur corps exprime.',
					'weight' => 0,
				),
				array(
					'name' => 'formations_title',
					'label' => 'Titre Formations',
					'type' => 'text',
					'default_value' => 'Mes formations',
					'weight' => 1,
				),
				array(
					'name' => 'formations_list',
					'label' => 'Liste des Formations',
					'type' => 'wysiwyg',
					'default_value' => '<ul><li><strong>CFNA</strong> (2022-2024) : Conseiller en nutrithérapie</li><li><strong>Oreka Formation</strong> (2025) : Nutrition et complémentation du sportif</li><li><strong>DFM Formation</strong> (2026) : Prise en charge des troubles du comportement alimentaire</li></ul>',
					'weight' => 2,
				),
				array(
					'name' => 'sport_title',
					'label' => 'Titre Section Sport',
					'type' => 'text',
					'default_value' => 'Le sport comme source de bien-être',
					'weight' => 3,
				),
				array(
					'name' => 'sport_content',
					'label' => 'Contenu Sport',
					'type' => 'wysiwyg',
					'default_value' => '<p>Le sport fait partie de ma vie depuis toujours. Enfant, j’ai exploré la danse, le tennis, la natation… puis, jeune adulte, la course à pied s’est imposée naturellement. Une pratique dont je ne peux aujourd’hui plus me passer. Avec le temps, j’ai aussi découvert le yoga, le vélo et d’autres formes de mouvement, chacune m’apportant un équilibre différent. En 2023, lors d’une pause professionnelle au Portugal, j’obtiens mon <strong>Yoga Teacher Training Certificate</strong>, renforçant encore ma vision globale du corps en mouvement.</p><p>En 2025, je franchis une nouvelle étape en devenant <strong>triathlète</strong>, avec la réalisation de mon premier triathlon olympique.</p><p>Au fil de ces expériences, une chose s’est imposée à moi : <strong>le sport est un formidable levier de bien-être</strong>, à condition d’être soutenu par une nutrition adaptée. Que l’on débute une activité physique, que l’on s’entraîne régulièrement ou que l’on vise la performance, l’alimentation joue un rôle central dans l’énergie, la récupération, la prévention des blessures et l’équilibre hormonal.</p><p>Sans un apport nutritionnel adéquat, le sport peut parfois devenir contre-productif : fatigue persistante, baisse de performance, troubles digestifs, dérèglements du cycle, blessures à répétition, ou encore une relation compliquée à l’alimentation et au corps. Chez certain·es sportif·ves, on observe également des risques plus sérieux comme le <strong>RED-S</strong> (syndrome de déficit énergétique relatif) ou des <strong>troubles du comportement alimentaire</strong>, souvent liés à une méconnaissance des besoins réels du corps.</p><p>C’est là que la nutrithérapie prend tout son sens. Mon approche en nutrition sportive vise à <strong>accompagner le corps</strong>, pas à le contraindre. Donner les bons apports au bon moment, comprendre ses besoins spécifiques, soutenir la récupération et préserver la santé sur le long terme.</p>',
					'weight' => 4,
				),
			),
		),
		
		// Page Contact
		'contact' => array(
			'group' => array(
				'name' => 'contact',
				'label' => 'Contenu Contact (Page Contact)',
				'weight' => 300,
			),
			'fields' => array(
				array(
					'name' => 'contact_image',
					'label' => 'Image Contact',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 0,
				),
				array(
					'name' => 'contact_title',
					'label' => 'Titre Contact',
					'type' => 'text',
					'default_value' => 'Contact',
					'weight' => 1,
				),
				array(
					'name' => 'contact_subtitle',
					'label' => 'Sous-titre Contact',
					'type' => 'text',
					'default_value' => 'Consultations en nutrithérapie',
					'weight' => 2,
				),
				array(
					'name' => 'contact_location',
					'label' => 'Lieu',
					'type' => 'text',
					'default_value' => 'à Ixelles ou en visio',
					'weight' => 3,
				),
				array(
					'name' => 'consultation_schedule',
					'label' => 'Horaires par jour',
					'type' => 'wysiwyg',
					'default_value' => '<ul class="nf-schedule"><li><span class="nf-schedule__day">Mercredi</span> <span class="nf-schedule__time">8h30 – 18h30</span> — <a href="https://www.clinicavital.be" target="_blank" rel="noopener">Clinica Vital</a>, Chaussée de Wavre 133, 1050 Ixelles</li><li><span class="nf-schedule__day">Jeudi</span> <span class="nf-schedule__time">8h30 – 19h</span> — En visio</li><li><span class="nf-schedule__day">Vendredi</span> <span class="nf-schedule__time">8h30 – 19h</span> — En visio</li></ul>',
					'weight' => 4,
				),
				array(
					'name' => 'contact_phone',
					'label' => 'Numéro de téléphone',
					'type' => 'text',
					'default_value' => '+32 486 920 962',
					'weight' => 5,
				),
				array(
					'name' => 'contact_email',
					'label' => 'Adresse email',
					'type' => 'email',
					'default_value' => 'fl.vanhecke@gmail.com',
					'weight' => 6,
				),
				array(
					'name' => 'contact_cta_text',
					'label' => 'Texte CTA Contact',
					'type' => 'text',
					'default_value' => 'Si tu as des questions ? N\'hésite pas à me contacter !',
					'weight' => 7,
				),
				array(
					'name' => 'contact_button_text',
					'label' => 'Texte Bouton Contact',
					'type' => 'text',
					'default_value' => 'PRENDRE RDV',
					'weight' => 8,
				),
			),
		),
	);
}
