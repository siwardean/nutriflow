<?php
/**
 * Register ACF Field Groups for Nutriflow Theme
 * 
 * @package Nutriflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register ACF fields for A Propos (About) page template
 */
if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group( array(
		'key' => 'group_apropos_page',
		'title' => 'Contenu A Propos',
		'fields' => array(
			// Intro Section
			array(
				'key' => 'field_intro_title',
				'label' => 'Titre Introduction',
				'name' => 'intro_title',
				'type' => 'text',
				'instructions' => 'Titre principal de la section introduction',
				'default_value' => 'Qui suis-je ?',
				'placeholder' => 'Qui suis-je ?',
			),
			array(
				'key' => 'field_intro_text',
				'label' => 'Texte Introduction',
				'name' => 'intro_text',
				'type' => 'wysiwyg',
				'instructions' => 'Texte de présentation',
				'toolbar' => 'basic',
				'media_upload' => 0,
				'default_value' => 'Je suis Florence, nutrithérapeute passionnée par l\'impact de la nutrition sur notre <strong>santé globale.</strong> Mon <strong>approche</strong> est douce, basée sur la science et centrée sur l\'<strong>écoute du corps.</strong>',
			),
			
			// Gallery Section
			array(
				'key' => 'field_gallery_images',
				'label' => 'Galerie d\'Images',
				'name' => 'gallery_images',
				'type' => 'gallery',
				'instructions' => 'Ajoutez 4 images pour la galerie',
				'min' => 4,
				'max' => 4,
				'insert' => 'append',
				'library' => 'all',
			),
			
			// Story Section
			array(
				'key' => 'field_story_content',
				'label' => 'Mon Parcours',
				'name' => 'story_content',
				'type' => 'wysiwyg',
				'instructions' => 'Contenu de la section "Mon Parcours"',
				'toolbar' => 'full',
				'media_upload' => 0,
				'default_value' => '<p>Mon corps a été mon <strong>premier guide.</strong> Dès l\'adolescence, j\'ai été confrontée à des <strong>déséquilibres</strong> (eczéma, douleurs <strong>chroniques</strong>, troubles <strong>digestifs</strong>, acné) que la médecine moderne n\'expliquait pas. En cherchant à comprendre les <strong>causes</strong> de mes maux et à trouver des solutions, j\'ai découvert la <strong>puissance</strong> de la <strong>nutrition</strong> dans mon processus de guérison. Je me suis donc formée en nutrithérapie pendant plusieurs années.</p><p>Aujourd\'hui, je me sens alignée, ancrée, et <strong>connectée</strong> à mes rythmes.</p><p>Ce chemin personnel m\'a donné des <strong>clés précieuses</strong>, que je transmets avec nuance et bienveillance. J\'aide les personnes à se <strong>reconnecter</strong> à leur corps, à comprendre leurs <strong>symptômes</strong> et à retrouver leur <strong>vitalité</strong> via leur alimentation, en écoutant ce que leur corps exprime.</p>',
			),
			
			// Formations Section
			array(
				'key' => 'field_formations_title',
				'label' => 'Titre Formations',
				'name' => 'formations_title',
				'type' => 'text',
				'instructions' => 'Titre de la section formations',
				'default_value' => 'Mes formations',
				'placeholder' => 'Mes formations',
			),
			array(
				'key' => 'field_formations_list',
				'label' => 'Liste des Formations',
				'name' => 'formations_list',
				'type' => 'repeater',
				'instructions' => 'Ajoutez vos formations',
				'button_label' => 'Ajouter une formation',
				'sub_fields' => array(
					array(
						'key' => 'field_formation_item',
						'label' => 'Formation',
						'name' => 'formation_item',
						'type' => 'wysiwyg',
						'toolbar' => 'basic',
						'media_upload' => 0,
					),
				),
			),
			
			// Sport Section
			array(
				'key' => 'field_sport_title',
				'label' => 'Titre Section Sport',
				'name' => 'sport_title',
				'type' => 'text',
				'instructions' => 'Titre de la section sport',
				'default_value' => 'Le sport comme source de bien-être',
				'placeholder' => 'Le sport comme source de bien-être',
			),
			array(
				'key' => 'field_sport_content',
				'label' => 'Contenu Sport',
				'name' => 'sport_content',
				'type' => 'wysiwyg',
				'instructions' => 'Contenu de la section sport',
				'toolbar' => 'full',
				'media_upload' => 0,
				'default_value' => '<p>Je fais du sport depuis toute petite : <strong>danse</strong>, tennis, <strong>natation</strong>,... Puis jeune adulte je mords très vite à la <strong>course à pied</strong> dont je ne peux aujourd\'hui plus me passer, mais je découvre également le <strong>yoga</strong>, le <strong>vélo</strong> et bien d\'autres activités sportives. J\'obtiens mon <strong>Yoga Teacher Training Certificate</strong> en 2023 au Portugal lors d\'une pause professionnelle.</p><p>En 2025, je deviens <strong>triathlète</strong> avec mon tout premier triathlon olympique.</p><p>La pratique sportive est pour moi la recherche d\'un <strong>bien-être général</strong> de mon corps et la <strong>recherche de l\'équilibre</strong>.</p>',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-a-propos.php',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
	) );

	// Contact Page Fields
	acf_add_local_field_group( array(
		'key' => 'group_contact_page',
		'title' => 'Contenu Contact',
		'fields' => array(
			array(
				'key' => 'field_contact_title',
				'label' => 'Titre Principal',
				'name' => 'contact_title',
				'type' => 'text',
				'default_value' => 'Contact',
				'placeholder' => 'Contact',
			),
			array(
				'key' => 'field_contact_subtitle',
				'label' => 'Sous-titre',
				'name' => 'contact_subtitle',
				'type' => 'text',
				'default_value' => 'Consultations en nutrithérapie',
				'placeholder' => 'Consultations en nutrithérapie',
			),
			array(
				'key' => 'field_contact_image',
				'label' => 'Image de Contact',
				'name' => 'contact_image',
				'type' => 'image',
				'instructions' => 'Image affichée à gauche',
				'return_format' => 'array',
				'preview_size' => 'medium',
			),
			array(
				'key' => 'field_contact_items',
				'label' => 'Informations de Contact',
				'name' => 'contact_items',
				'type' => 'repeater',
				'instructions' => 'Ajoutez les informations de contact',
				'button_label' => 'Ajouter une ligne',
				'sub_fields' => array(
					array(
						'key' => 'field_item_text',
						'label' => 'Texte',
						'name' => 'item_text',
						'type' => 'text',
					),
					array(
						'key' => 'field_item_type',
						'label' => 'Type',
						'name' => 'item_type',
						'type' => 'select',
						'choices' => array(
							'text' => 'Texte simple',
							'link' => 'Lien (téléphone/email)',
						),
						'default_value' => 'text',
					),
					array(
						'key' => 'field_item_link',
						'label' => 'Lien',
						'name' => 'item_link',
						'type' => 'url',
						'instructions' => 'Ex: tel:+32486920962 ou mailto:email@example.com',
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_item_type',
									'operator' => '==',
									'value' => 'link',
								),
							),
						),
					),
				),
			),
			array(
				'key' => 'field_contact_cta_text',
				'label' => 'Texte d\'Appel à l\'Action',
				'name' => 'contact_cta_text',
				'type' => 'text',
				'default_value' => 'Si tu as des questions ? N\'hésites pas à me contacter !',
			),
			array(
				'key' => 'field_contact_button_text',
				'label' => 'Texte du Bouton',
				'name' => 'contact_button_text',
				'type' => 'text',
				'default_value' => 'PRENDRE RDV',
				'placeholder' => 'PRENDRE RDV',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-contact.php',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
	) );

	// Accompagnement Page Fields  
	acf_add_local_field_group( array(
		'key' => 'group_accompagnement_page',
		'title' => 'Contenu Accompagnement',
		'active' => 1,
		'fields' => array(
			// Hero Section
			array(
				'key' => 'field_hero_title',
				'label' => 'Titre Hero',
				'name' => 'hero_title',
				'type' => 'text',
				'default_value' => 'Je t\'accompagne pour',
			),
			array(
				'key' => 'field_hero_background',
				'label' => 'Image de fond Hero',
				'name' => 'hero_background',
				'type' => 'image',
				'return_format' => 'array',
			),
			array(
				'key' => 'field_hero_cards',
				'label' => 'Cartes Hero',
				'name' => 'hero_cards',
				'type' => 'repeater',
				'button_label' => 'Ajouter une carte',
				'sub_fields' => array(
					array(
						'key' => 'field_card_title',
						'label' => 'Titre',
						'name' => 'card_title',
						'type' => 'text',
					),
					array(
						'key' => 'field_card_description',
						'label' => 'Description',
						'name' => 'card_description',
						'type' => 'textarea',
					),
				),
			),
			// Pricing Section
			array(
				'key' => 'field_pricing_title',
				'label' => 'Titre Tarifs',
				'name' => 'pricing_title',
				'type' => 'text',
				'default_value' => 'Comment ?',
			),
			// Pricing Cards - Compatible avec ACF Free (pas de repeater)
			array(
				'key' => 'field_pricing_card_1_title',
				'label' => 'Carte Tarif 1 - Titre',
				'name' => 'pricing_card_1_title',
				'type' => 'text',
				'default_value' => 'Première consultation de +- 1h15',
			),
			array(
				'key' => 'field_pricing_card_1_price',
				'label' => 'Carte Tarif 1 - Prix',
				'name' => 'pricing_card_1_price',
				'type' => 'wysiwyg',
				'toolbar' => 'basic',
				'media_upload' => 0,
				'instructions' => 'Utilisez <del></del> pour barrer les anciens prix',
				'default_value' => '- 70 euros -',
			),
			array(
				'key' => 'field_pricing_card_1_items',
				'label' => 'Carte Tarif 1 - Liste des items',
				'name' => 'pricing_card_1_items',
				'type' => 'wysiwyg',
				'toolbar' => 'full',
				'media_upload' => 0,
				'instructions' => 'Utilisez l\'éditeur pour créer une liste à puces. Chaque item doit être sur une nouvelle ligne.',
				'default_value' => '<ul><li><strong>Questionnaire</strong> préparatoire</li><li><strong>Analyse</strong> des 3 piliers : alimentation, hygiène de vie et supplémentation</li><li>Etablissement des <strong>objectifs</strong></li><li><strong>Premier bilan</strong> nutritionnel et conseils adaptés</li></ul>',
			),
			array(
				'key' => 'field_pricing_card_2_title',
				'label' => 'Carte Tarif 2 - Titre',
				'name' => 'pricing_card_2_title',
				'type' => 'text',
				'default_value' => 'Consultation de suivi de 30 à 45 minutes',
			),
			array(
				'key' => 'field_pricing_card_2_price',
				'label' => 'Carte Tarif 2 - Prix',
				'name' => 'pricing_card_2_price',
				'type' => 'wysiwyg',
				'toolbar' => 'basic',
				'media_upload' => 0,
				'instructions' => 'Utilisez <del></del> pour barrer les anciens prix',
				'default_value' => '- 40 euros -',
			),
			array(
				'key' => 'field_pricing_card_2_items',
				'label' => 'Carte Tarif 2 - Liste des items',
				'name' => 'pricing_card_2_items',
				'type' => 'wysiwyg',
				'toolbar' => 'full',
				'media_upload' => 0,
				'instructions' => 'Utilisez l\'éditeur pour créer une liste à puces. Chaque item doit être sur une nouvelle ligne.',
				'default_value' => '<ul><li>Premier retour d\'expérience et <strong>questions</strong> - réponses</li><li>Evaluation des <strong>résultats et adaptation</strong> de l\'accompagnement si nécessaire</li></ul>',
			),
			array(
				'key' => 'field_pricing_card_3_title',
				'label' => 'Carte Tarif 3 - Titre',
				'name' => 'pricing_card_3_title',
				'type' => 'text',
				'default_value' => 'Pack \'Accompagnement sur 3 mois\'',
			),
			array(
				'key' => 'field_pricing_card_3_price',
				'label' => 'Carte Tarif 3 - Prix',
				'name' => 'pricing_card_3_price',
				'type' => 'wysiwyg',
				'toolbar' => 'basic',
				'media_upload' => 0,
				'instructions' => 'Utilisez <del></del> pour barrer les anciens prix',
				'default_value' => '<del>-190</del> 175 euros -',
			),
			array(
				'key' => 'field_pricing_card_3_items',
				'label' => 'Carte Tarif 3 - Liste des items',
				'name' => 'pricing_card_3_items',
				'type' => 'wysiwyg',
				'toolbar' => 'full',
				'media_upload' => 0,
				'instructions' => 'Utilisez l\'éditeur pour créer une liste à puces. Chaque item doit être sur une nouvelle ligne.',
				'default_value' => '<ul><li>Total de <strong>4 consultations</strong></li><li>Pour un <strong>changement ancré</strong> sur du long terme</li><li>Payable en plusieurs fois</li><li>Echanges sur whatsapp entre les consultations</li></ul>',
			),
			array(
				'key' => 'field_pricing_card_4_title',
				'label' => 'Carte Tarif 4 - Titre (optionnel)',
				'name' => 'pricing_card_4_title',
				'type' => 'text',
				'default_value' => '',
			),
			array(
				'key' => 'field_pricing_card_4_price',
				'label' => 'Carte Tarif 4 - Prix (optionnel)',
				'name' => 'pricing_card_4_price',
				'type' => 'wysiwyg',
				'toolbar' => 'basic',
				'media_upload' => 0,
				'instructions' => 'Utilisez <del></del> pour barrer les anciens prix',
				'default_value' => '',
			),
			array(
				'key' => 'field_pricing_card_4_items',
				'label' => 'Carte Tarif 4 - Liste des items (optionnel)',
				'name' => 'pricing_card_4_items',
				'type' => 'wysiwyg',
				'toolbar' => 'full',
				'media_upload' => 0,
				'instructions' => 'Utilisez l\'éditeur pour créer une liste à puces. Chaque item doit être sur une nouvelle ligne.',
				'default_value' => '',
			),
			// Sportif Section
			array(
				'key' => 'field_sportif_title',
				'label' => 'Titre Section Sportif',
				'name' => 'sportif_title',
				'type' => 'text',
				'default_value' => 'Tu souhaites un accompagnement spécial " Nutrition du sportif·ve " ?',
			),
			array(
				'key' => 'field_sportif_card_title',
				'label' => 'Titre Carte Sportif',
				'name' => 'sportif_card_title',
				'type' => 'text',
				'default_value' => 'Accompagnement sur mesure',
			),
			array(
				'key' => 'field_sportif_items',
				'label' => 'Liste Sportif',
				'name' => 'sportif_items',
				'type' => 'repeater',
				'button_label' => 'Ajouter un item',
				'sub_fields' => array(
					array(
						'key' => 'field_sportif_item',
						'label' => 'Item',
						'name' => 'sportif_item',
						'type' => 'wysiwyg',
						'toolbar' => 'basic',
						'media_upload' => 0,
					),
				),
			),
			// Location Section
			array(
				'key' => 'field_location_title',
				'label' => 'Titre Localisation',
				'name' => 'location_title',
				'type' => 'text',
				'default_value' => 'Où se passent mes consultations ?',
			),
			array(
				'key' => 'field_location_info',
				'label' => 'Informations Localisation',
				'name' => 'location_info',
				'type' => 'wysiwyg',
				'toolbar' => 'full',
				'instructions' => 'Horaires, adresse, téléphone, email',
			),
			array(
				'key' => 'field_location_image',
				'label' => 'Image Localisation',
				'name' => 'location_image',
				'type' => 'image',
				'return_format' => 'array',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-accompagnement.php',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
	) );

	// Homepage/Front Page Fields
	acf_add_local_field_group( array(
		'key' => 'group_homepage',
		'title' => 'Contenu Page d\'Accueil',
		'fields' => array(
			// Hero Section
			array(
				'key' => 'field_homepage_hero_title',
				'label' => 'Hero - Titre',
				'name' => 'homepage_hero_title',
				'type' => 'text',
				'default_value' => 'NUTRIFLOW',
			),
			array(
				'key' => 'field_homepage_hero_subtitle',
				'label' => 'Hero - Sous-titre',
				'name' => 'homepage_hero_subtitle',
				'type' => 'text',
				'default_value' => '- NUTRITHÉRAPEUTE À BRUXELLES -',
			),
			array(
				'key' => 'field_homepage_hero_description',
				'label' => 'Hero - Description',
				'name' => 'homepage_hero_description',
				'type' => 'textarea',
				'default_value' => 'Accompagnement en nutrithérapie: rééquilibrage alimentaire, nutrition sportive, troubles hormonaux et digestifs.',
			),
			array(
				'key' => 'field_homepage_hero_button',
				'label' => 'Hero - Texte du Bouton',
				'name' => 'homepage_hero_button',
				'type' => 'text',
				'default_value' => 'PRENDRE RDV',
			),
			array(
				'key' => 'field_homepage_hero_image',
				'label' => 'Hero - Image',
				'name' => 'homepage_hero_image',
				'type' => 'image',
				'return_format' => 'array',
			),
			// About Section
			array(
				'key' => 'field_homepage_about_title',
				'label' => 'A Propos - Titre',
				'name' => 'homepage_about_title',
				'type' => 'text',
				'default_value' => 'Qui suis-je ?',
			),
			array(
				'key' => 'field_homepage_about_text_left',
				'label' => 'A Propos - Texte Gauche',
				'name' => 'homepage_about_text_left',
				'type' => 'wysiwyg',
				'toolbar' => 'basic',
				'media_upload' => 0,
				'default_value' => 'Je suis Florence, <strong>nutrithérapeute passionnée</strong> par l\'impact de la nutrition sur notre santé globale. Mon <strong>approche</strong> est douce, basée sur la <strong>science</strong> et centrée sur l\'écoute du corps.',
			),
			array(
				'key' => 'field_homepage_about_text_right',
				'label' => 'A Propos - Texte Droite',
				'name' => 'homepage_about_text_right',
				'type' => 'wysiwyg',
				'toolbar' => 'basic',
				'media_upload' => 0,
				'default_value' => 'La <strong>nutrithérapie</strong> est, selon moi, un outil de <strong>prévention</strong>, mais aussi de transformation et d\'autonomie : elle <strong>reconnecte</strong> à soi, à ses <strong>besoins</strong> et à son pouvoir d\'agir.',
			),
			array(
				'key' => 'field_homepage_about_image',
				'label' => 'A Propos - Image',
				'name' => 'homepage_about_image',
				'type' => 'image',
				'return_format' => 'array',
			),
			// Consultation Section
			array(
				'key' => 'field_homepage_consult_title',
				'label' => 'Consultation - Titre',
				'name' => 'homepage_consult_title',
				'type' => 'text',
				'default_value' => 'L\'objectif de la consultation',
			),
			array(
				'key' => 'field_homepage_consult_text',
				'label' => 'Consultation - Texte',
				'name' => 'homepage_consult_text',
				'type' => 'wysiwyg',
				'toolbar' => 'full',
				'media_upload' => 0,
				'default_value' => '<p class="nf-consultation__text">Je t\'accompagne pour mieux <strong>comprendre ton corps</strong>, ses <strong>besoins nutritionnels</strong> et ses <strong>mécanismes</strong>, afin que tu puisses faire des choix éclairés, <strong>durables</strong> et bienveillants.</p><p class="nf-consultation__text">Ensemble, nous rendrons les clés de la nutrition <strong>accessibles</strong>, simples et ancrées dans le plaisir. Pas de régime strict ni d\'interdits, mais des <strong>réflexes santé</strong> concrets, adaptés à ta réalité. Mon approche vise à reconnecter l\'alimentation au plaisir gourmand, tout en t\'apportant les clés d\'un mieux-être durable, étape par étape.</p>',
			),
			array(
				'key' => 'field_homepage_consult_button',
				'label' => 'Consultation - Texte du Bouton',
				'name' => 'homepage_consult_button',
				'type' => 'text',
				'default_value' => 'DÉCOUVRIR LES ACCOMPAGNEMENTS',
			),
			array(
				'key' => 'field_homepage_consult_image',
				'label' => 'Consultation - Image',
				'name' => 'homepage_consult_image',
				'type' => 'image',
				'return_format' => 'array',
			),
			// Services Section
			array(
				'key' => 'field_homepage_services_heading',
				'label' => 'Services - Titre Principal',
				'name' => 'homepage_services_heading',
				'type' => 'text',
				'default_value' => 'Je t\'accompagne pour',
			),
			array(
				'key' => 'field_homepage_services',
				'label' => 'Services - Liste',
				'name' => 'homepage_services',
				'type' => 'repeater',
				'button_label' => 'Ajouter un service',
				'sub_fields' => array(
					array(
						'key' => 'field_service_title',
						'label' => 'Titre',
						'name' => 'service_title',
						'type' => 'text',
					),
					array(
						'key' => 'field_service_description',
						'label' => 'Description',
						'name' => 'service_description',
						'type' => 'textarea',
					),
				),
			),
			// Testimonials Section
			array(
				'key' => 'field_homepage_testimonials_heading',
				'label' => 'Témoignages - Titre',
				'name' => 'homepage_testimonials_heading',
				'type' => 'text',
				'default_value' => 'Témoignages',
			),
			array(
				'key' => 'field_homepage_testimonials',
				'label' => 'Témoignages - Liste',
				'name' => 'homepage_testimonials',
				'type' => 'repeater',
				'button_label' => 'Ajouter un témoignage',
				'sub_fields' => array(
					array(
						'key' => 'field_testimonial_text',
						'label' => 'Texte',
						'name' => 'testimonial_text',
						'type' => 'textarea',
					),
					array(
						'key' => 'field_testimonial_author',
						'label' => 'Auteur',
						'name' => 'testimonial_author',
						'type' => 'text',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'page_type',
					'operator' => '==',
					'value' => 'front_page',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
	) );

endif;

