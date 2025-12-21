<?php
/**
 * Pods Data Migration - Pré-remplir les champs Pods avec le contenu actuel
 * 
 * Cette fonction remplit tous les champs Pods avec les valeurs par défaut du site
 * 
 * @package Nutriflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sauvegarder un champ de type "table" (répétable) dans Pods
 * 
 * NOTE: Les champs de type "table" dans Pods nécessitent une manipulation spéciale
 * et peuvent causer des erreurs lors de la sauvegarde programmatique.
 * Pour l'instant, cette fonction retourne false pour éviter les erreurs.
 * Les utilisateurs devront remplir ces champs manuellement dans l'éditeur Pods.
 * 
 * @param object $pod L'objet Pods
 * @param string $field_name Le nom du champ
 * @param array $rows_data Les données des lignes (tableau de tableaux associatifs)
 * @return bool false - Les champs table doivent être remplis manuellement
 */
function nutriflow_save_table_field( $pod, $field_name, $rows_data ) {
	// DÉSACTIVÉ TEMPORAIREMENT : Les champs de type "table" causent des erreurs
	// "Array to string conversion" lors de la sauvegarde programmatique.
	// Ils doivent être remplis manuellement dans l'éditeur Pods.
	
	// Log pour information (seulement si WP_DEBUG est activé)
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( 'Pods Table Field (' . $field_name . ') : Sauvegarde désactivée - doit être rempli manuellement' );
	}
	
	return false;
	
	/* CODE DÉSACTIVÉ - Cause des erreurs Array to string conversion
	if ( ! $pod || ! $pod->exists() || empty( $rows_data ) ) {
		return false;
	}
	
	try {
		// Essayer différentes méthodes de sauvegarde
		// Méthode 1: Sauvegarde directe avec tableau
		$result = $pod->save( array( $field_name => $rows_data ) );
		
		if ( $result ) {
			return true;
		}
		
		// Si ça ne fonctionne pas, essayer via l'API directement
		// ... (code non implémenté car cause des erreurs)
		
		return false;
	} catch ( Exception $e ) {
		error_log( 'Pods Table Field Save Error (' . $field_name . '): ' . $e->getMessage() );
		return false;
	} catch ( Error $e ) {
		error_log( 'Pods Table Field Save Fatal Error (' . $field_name . '): ' . $e->getMessage() );
		return false;
	}
	*/
}

/**
 * Pré-remplir tous les champs Pods avec le contenu actuel
 * 
 * Cette fonction doit être appelée une fois pour migrer le contenu existant vers Pods
 */
function nutriflow_prefill_pods_fields() {
	if ( ! function_exists( 'pods' ) ) {
		return;
	}
	
	// Ne s'exécute que dans l'admin
	if ( ! is_admin() ) {
		return;
	}
	
	// Page d'accueil (Front Page)
	$front_page_id = get_option( 'page_on_front' );
	if ( $front_page_id ) {
		nutriflow_prefill_homepage_fields( $front_page_id );
	}
	
	// Page Accompagnement
	$accompagnement_page = get_page_by_path( 'accompagnement' );
	if ( $accompagnement_page ) {
		nutriflow_prefill_accompagnement_fields( $accompagnement_page->ID );
	}
	
	// Page À propos
	$apropos_page = get_page_by_path( 'a-propos' );
	if ( $apropos_page ) {
		nutriflow_prefill_apropos_fields( $apropos_page->ID );
	}
	
	// Page Contact
	$contact_page = get_page_by_path( 'contact' );
	if ( $contact_page ) {
		nutriflow_prefill_contact_fields( $contact_page->ID );
	}
}

/**
 * Pré-remplir les champs de la page d'accueil
 */
function nutriflow_prefill_homepage_fields( $page_id ) {
	$pod = pods( 'page', $page_id );
	if ( ! $pod || ! $pod->exists() ) {
		return;
	}
	
	// Messages d'erreur pour le débogage
	$errors = array();
	
	// Section Hero
	$data_to_save = array();
	
	if ( ! $pod->field( 'homepage_hero_title' ) ) {
		$data_to_save['homepage_hero_title'] = 'NUTRIFLOW';
	}
	if ( ! $pod->field( 'homepage_hero_subtitle' ) ) {
		$data_to_save['homepage_hero_subtitle'] = '- NUTRITHÉRAPEUTE À BRUXELLES -';
	}
	if ( ! $pod->field( 'homepage_hero_description' ) ) {
		$data_to_save['homepage_hero_description'] = 'Accompagnement en nutrithérapie: rééquilibrage alimentaire, nutrition sportive, troubles hormonaux et digestifs.';
	}
	if ( ! $pod->field( 'homepage_hero_button' ) ) {
		$data_to_save['homepage_hero_button'] = 'PRENDRE RDV';
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
		$data_to_save = array();
	}
	
	// Section À propos
	if ( ! $pod->field( 'homepage_about_title' ) ) {
		$data_to_save['homepage_about_title'] = 'Qui suis-je ?';
	}
	if ( ! $pod->field( 'homepage_about_text_left' ) ) {
		$data_to_save['homepage_about_text_left'] = 'Je suis Florence, <strong>nutrithérapeute passionnée</strong> par l\'impact de la nutrition sur notre santé globale. Mon <strong>approche</strong> est douce, basée sur la <strong>science</strong> et centrée sur l\'écoute du corps.';
	}
	if ( ! $pod->field( 'homepage_about_text_right' ) ) {
		$data_to_save['homepage_about_text_right'] = 'La <strong>nutrithérapie</strong> est, selon moi, un outil de <strong>prévention</strong>, mais aussi de transformation et d\'autonomie : elle <strong>reconnecte</strong> à soi, à ses <strong>besoins</strong> et à son pouvoir d\'agir.';
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
		$data_to_save = array();
	}
	
	// Section Consultation
	if ( ! $pod->field( 'homepage_consult_title' ) ) {
		$data_to_save['homepage_consult_title'] = 'L\'objectif de la consultation';
	}
	if ( ! $pod->field( 'homepage_consult_text' ) ) {
		$data_to_save['homepage_consult_text'] = '<p class="nf-consultation__text">Je t\'accompagne pour mieux <strong>comprendre ton corps</strong>, ses <strong>besoins nutritionnels</strong> et ses <strong>mécanismes</strong>, afin que tu puisses faire des choix éclairés, <strong>durables</strong> et bienveillants.</p><p class="nf-consultation__text">Ensemble, nous rendrons les clés de la nutrition <strong>accessibles</strong>, simples et ancrées dans le plaisir. Pas de régime strict ni d\'interdits, mais des <strong>réflexes santé</strong> concrets, adaptés à ta réalité. Mon approche vise à reconnecter l\'alimentation au plaisir gourmand, tout en t\'apportant les clés d\'un mieux-être durable, étape par étape.</p>';
	}
	if ( ! $pod->field( 'homepage_consult_button' ) ) {
		$data_to_save['homepage_consult_button'] = 'DÉCOUVRIR LES ACCOMPAGNEMENTS';
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
		$data_to_save = array();
	}
	
	// Section Services
	if ( ! $pod->field( 'homepage_services_heading' ) ) {
		$pod->save( array( 'homepage_services_heading' => 'Je t\'accompagne pour' ) );
	}
	
	// Services - Pré-remplir avec les valeurs par défaut
	if ( ! $pod->field( 'homepage_service_1_title' ) ) {
		$pod->save( array( 'homepage_service_1_title' => 'Rééquilibrage alimentaire' ) );
	}
	if ( ! $pod->field( 'homepage_service_1_description' ) ) {
		$pod->save( array( 'homepage_service_1_description' => 'Rééquilibrer ton alimentation et/ou perdre du poids.' ) );
	}
	if ( ! $pod->field( 'homepage_service_2_title' ) ) {
		$pod->save( array( 'homepage_service_2_title' => 'Troubles hormonaux' ) );
	}
	if ( ! $pod->field( 'homepage_service_2_description' ) ) {
		$pod->save( array( 'homepage_service_2_description' => 'Soulager tes symptômes et rééquilibrer les troubles hormonaux : SPM, endométriose, SOPK,...' ) );
	}
	if ( ! $pod->field( 'homepage_service_3_title' ) ) {
		$pod->save( array( 'homepage_service_3_title' => 'Nutrition & performances sportif·ves' ) );
	}
	if ( ! $pod->field( 'homepage_service_3_description' ) ) {
		$pod->save( array( 'homepage_service_3_description' => 'Améliorer tes performances et mieux comprendre l\'impact de ton alimentation sur tes résultats.' ) );
	}
	if ( ! $pod->field( 'homepage_service_4_title' ) ) {
		$pod->save( array( 'homepage_service_4_title' => 'Troubles digestifs' ) );
	}
	if ( ! $pod->field( 'homepage_service_4_description' ) ) {
		$pod->save( array( 'homepage_service_4_description' => 'Prendre soin de ton microbiote mais également pour soulager les troubles digestifs chroniques (SII, MICI, SIBO,..).' ) );
	}
	
	// Section Témoignages - Pré-remplir avec les valeurs par défaut
	$testimonial_data = array();
	$current_heading = $pod->field( 'homepage_testimonials_heading', true );
	$current_text_1 = $pod->field( 'homepage_testimonial_1_text', true );
	$current_author_1 = $pod->field( 'homepage_testimonial_1_author', true );
	$current_text_2 = $pod->field( 'homepage_testimonial_2_text', true );
	$current_author_2 = $pod->field( 'homepage_testimonial_2_author', true );
	
	if ( empty( $current_heading ) || $current_heading === false ) {
		$testimonial_data['homepage_testimonials_heading'] = 'Témoignages';
	}
	if ( empty( $current_text_1 ) || $current_text_1 === false ) {
		$testimonial_data['homepage_testimonial_1_text'] = '<p style="text-align: center;"><em>J\'ai contacté Florence afin de mieux comprendre quelle est l\'alimentation qui me correspondrait le mieux, et adopter des habitudes saines sur le long terme. Son écoute attentive et son accompagnement personnalisé m\'ont beaucoup appris, et le défi à été réussi. Je la recommande vivement.</em></p>';
	}
	if ( empty( $current_author_1 ) || $current_author_1 === false ) {
		$testimonial_data['homepage_testimonial_1_author'] = 'Nina Rozenberg';
	}
	if ( empty( $current_text_2 ) || $current_text_2 === false ) {
		$testimonial_data['homepage_testimonial_2_text'] = '<p style="text-align: center;"><em>Dans le cadre de ma reprise du sport, j\'ai fait appel à Florence pour adopter des habitudes nutritionnelles adaptées et durables. Son accompagnement, à la fois clair et bienveillant, m\'a permis d\'améliorer ma nutrition de manière concrète. Je suis très satisfait de son suivi et je la remercie pour son aide précieuse.</em></p>';
	}
	if ( empty( $current_author_2 ) || $current_author_2 === false ) {
		$testimonial_data['homepage_testimonial_2_author'] = 'Siwar Madrane';
	}
	
	if ( ! empty( $testimonial_data ) ) {
		$result = $pod->save( $testimonial_data );
		if ( ! $result && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'Erreur lors de la sauvegarde des témoignages pour la page ' . $page_id . ': ' . print_r( $testimonial_data, true ) );
		}
	}
	
	// Afficher les erreurs si nécessaire
	if ( ! empty( $errors ) && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( 'Pods Migration Errors (Homepage): ' . implode( ', ', $errors ) );
	}
}

/**
 * Pré-remplir les champs de la page Accompagnement
 */
function nutriflow_prefill_accompagnement_fields( $page_id ) {
	$pod = pods( 'page', $page_id );
	if ( ! $pod || ! $pod->exists() ) {
		return;
	}
	
	// Section Hero
	$data_to_save = array();
	
	if ( ! $pod->field( 'hero_title' ) ) {
		$data_to_save['hero_title'] = 'Je t\'accompagne pour';
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
		$data_to_save = array();
	}
	
	// Hero Cards - Pré-remplir avec les valeurs par défaut
	if ( ! $pod->field( 'hero_card_1_title' ) ) {
		$pod->save( array( 'hero_card_1_title' => 'Rééquilibrage alimentaire' ) );
	}
	if ( ! $pod->field( 'hero_card_1_content' ) ) {
		$pod->save( array( 'hero_card_1_content' => 'Rééquilibrer ton alimentation et/ou perdre du poids.' ) );
	}
	if ( ! $pod->field( 'hero_card_2_title' ) ) {
		$pod->save( array( 'hero_card_2_title' => 'Troubles hormonaux' ) );
	}
	if ( ! $pod->field( 'hero_card_2_content' ) ) {
		$pod->save( array( 'hero_card_2_content' => 'Soulager tes simptômes et rééquilibrer les troubles hormonaux : SPM, endométriose, SOPK,...' ) );
	}
	if ( ! $pod->field( 'hero_card_3_title' ) ) {
		$pod->save( array( 'hero_card_3_title' => 'Nutrition & performances sportif·ves' ) );
	}
	if ( ! $pod->field( 'hero_card_3_content' ) ) {
		$pod->save( array( 'hero_card_3_content' => 'Améliorer tes performances et mieux comprendre l\'impact de ton alimentation sur tes résultats.' ) );
	}
	if ( ! $pod->field( 'hero_card_4_title' ) ) {
		$pod->save( array( 'hero_card_4_title' => 'Troubles digestifs' ) );
	}
	if ( ! $pod->field( 'hero_card_4_content' ) ) {
		$pod->save( array( 'hero_card_4_content' => 'Prendre soin de ton microbiote mais également pour soulager les troubles digestifs chroniques (SII, MICI, SIBO,..).' ) );
	}
	
	// Section Tarifs
	if ( ! $pod->field( 'pricing_title' ) ) {
		$data_to_save['pricing_title'] = 'Comment ?';
	}
	
	// Carte Tarif 1
	if ( ! $pod->field( 'pricing_card_1_title' ) ) {
		$data_to_save['pricing_card_1_title'] = 'Première consultation de +- 1h15';
	}
	if ( ! $pod->field( 'pricing_card_1_price' ) ) {
		$data_to_save['pricing_card_1_price'] = '- 70 euros -';
	}
	if ( ! $pod->field( 'pricing_card_1_items' ) ) {
		$data_to_save['pricing_card_1_items'] = '<ul><li><strong>Questionnaire</strong> préparatoire</li><li><strong>Analyse</strong> des 3 piliers : alimentation, hygiène de vie et supplémentation</li><li>Etablissement des <strong>objectifs</strong></li><li><strong>Premier bilan</strong> nutritionnel et conseils adaptés</li></ul>';
	}
	
	// Carte Tarif 2
	if ( ! $pod->field( 'pricing_card_2_title' ) ) {
		$data_to_save['pricing_card_2_title'] = 'Consultation de suivi de 30 à 45 minutes';
	}
	if ( ! $pod->field( 'pricing_card_2_price' ) ) {
		$data_to_save['pricing_card_2_price'] = '- 40 euros -';
	}
	if ( ! $pod->field( 'pricing_card_2_items' ) ) {
		$data_to_save['pricing_card_2_items'] = '<ul><li>Premier retour d\'expérience et <strong>questions</strong> - réponses</li><li>Evaluation des <strong>résultats et adaptation</strong> de l\'accompagnement si nécessaire</li></ul>';
	}
	
	// Carte Tarif 3
	if ( ! $pod->field( 'pricing_card_3_title' ) ) {
		$data_to_save['pricing_card_3_title'] = 'Pack \'Accompagnement sur 3 mois\'';
	}
	if ( ! $pod->field( 'pricing_card_3_price' ) ) {
		$data_to_save['pricing_card_3_price'] = '<del>-190</del> 175 euros -';
	}
	if ( ! $pod->field( 'pricing_card_3_items' ) ) {
		$data_to_save['pricing_card_3_items'] = '<ul><li>Total de <strong>4 consultations</strong></li><li>Pour un <strong>changement ancré</strong> sur du long terme</li><li>Payable en plusieurs fois</li><li>Echanges sur whatsapp entre les consultations</li></ul>';
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
		$data_to_save = array();
	}
	
	// Section Sportif
	if ( ! $pod->field( 'sportif_title' ) ) {
		$data_to_save['sportif_title'] = 'Tu souhaites un accompagnement spécial " Nutrition du sportif·ve " ?';
	}
	if ( ! $pod->field( 'sportif_card_title' ) ) {
		$data_to_save['sportif_card_title'] = 'Accompagnement sur mesure';
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
		$data_to_save = array();
	}
	
	// Contenu Sportif (fusionné : contenu + liste)
	$current_sportif_content = $pod->field( 'sportif_content', true );
	if ( empty( $current_sportif_content ) || $current_sportif_content === false ) {
		$data_to_save['sportif_content'] = '<p>Pour t\'aider à y voir plus clair dans les <strong>besoins spécifiques</strong> d\'un·e sportif·ve et adapter ton alimentation.</p><ul><li>Pour comprendre l\'impact de l\'alimentation sur tes performances</li><li>Pour optimiser ton alimentation avant, pendant et après l\'effort</li><li>Pour gérer ton poids de façon adaptée à ta pratique</li><li>Pour optimiser ta récupération</li></ul>';
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
		$data_to_save = array();
	}
	
	// Section Localisation
	if ( ! $pod->field( 'location_title' ) ) {
		$pod->save( array( 'location_title' => 'Où se passent mes consultations ?' ) );
	}
	
	// Location Info (WYSIWYG) - Pré-remplir avec les valeurs par défaut
	if ( ! $pod->field( 'location_info' ) ) {
		$location_info = '<p>A <strong>Ixelles</strong> ou en visio</p><p>Le <strong>vendredi</strong> de 8h à 18h30 et le <strong>samedi</strong> de 10h à 18h30</p>';
		$pod->save( array( 'location_info' => $location_info ) );
	}
	
	// Location Info (WYSIWYG) - Pré-remplir avec les valeurs par défaut
	if ( ! $pod->field( 'location_info' ) ) {
		$location_info = '<p>A <strong>Ixelles</strong> ou en visio</p><p>Le <strong>vendredi</strong> de 8h à 18h30 et le <strong>samedi</strong> de 10h à 18h30</p>';
		$pod->save( array( 'location_info' => $location_info ) );
	}
}

/**
 * Pré-remplir les champs de la page À propos
 */
function nutriflow_prefill_apropos_fields( $page_id ) {
	$pod = pods( 'page', $page_id );
	if ( ! $pod || ! $pod->exists() ) {
		return;
	}
	
	// Section Introduction
	$data_to_save = array();
	
	if ( ! $pod->field( 'intro_title' ) ) {
		$data_to_save['intro_title'] = 'Qui suis-je ?';
	}
	if ( ! $pod->field( 'intro_text' ) ) {
		$data_to_save['intro_text'] = 'Je suis Florence, nutrithérapeute passionnée par l\'impact de la nutrition sur notre <strong>santé globale.</strong> Mon <strong>approche</strong> est douce, basée sur la science et centrée sur l\'<strong>écoute du corps.</strong>';
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
		$data_to_save = array();
	}
	
	// Section Parcours
	if ( ! $pod->field( 'story_content' ) ) {
		$data_to_save['story_content'] = '<p>Mon corps a été mon <strong>premier guide.</strong> Dès l\'adolescence, j\'ai été confrontée à des <strong>déséquilibres</strong> (eczéma, douleurs <strong>chroniques</strong>, troubles <strong>digestifs</strong>, acné) que la médecine moderne n\'expliquait pas. En cherchant à comprendre les <strong>causes</strong> de mes maux et à trouver des solutions, j\'ai découvert la <strong>puissance</strong> de la <strong>nutrition</strong> dans mon processus de guérison. Je me suis donc formée en nutrithérapie pendant plusieurs années.</p><p>Aujourd\'hui, je me sens alignée, ancrée, et <strong>connectée</strong> à mes rythmes.</p><p>Ce chemin personnel m\'a donné des <strong>clés précieuses</strong>, que je transmets avec nuance et bienveillance. J\'aide les personnes à se <strong>reconnecter</strong> à leur corps, à comprendre leurs <strong>symptômes</strong> et à retrouver leur <strong>vitalité</strong> via leur alimentation, en écoutant ce que leur corps exprime.</p>';
	}
	if ( ! $pod->field( 'formations_title' ) ) {
		$data_to_save['formations_title'] = 'Mes formations';
	}
	
	// Formations List (WYSIWYG)
	$current_formations_list = $pod->field( 'formations_list', true );
	if ( empty( $current_formations_list ) || $current_formations_list === false ) {
		$data_to_save['formations_list'] = '<ul><li><strong>CFNA</strong> (2022-2024) : Conseiller en nutrithérapie</li><li><strong>Oreka Formation</strong> (2025) : Nutrition et complémentation du sportif</li></ul>';
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
		$data_to_save = array();
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
		$data_to_save = array();
	}
	
	// Section Sport
	if ( ! $pod->field( 'sport_title' ) ) {
		$data_to_save['sport_title'] = 'Le sport comme source de bien-être';
	}
	if ( ! $pod->field( 'sport_content' ) ) {
		$data_to_save['sport_content'] = '<p>Je fais du sport depuis toute petite : <strong>danse</strong>, tennis, <strong>natation</strong>,... Puis jeune adulte je mords très vite à la <strong>course à pied</strong> dont je ne peux aujourd\'hui plus me passer, mais je découvre également le <strong>yoga</strong>, le <strong>vélo</strong> et bien d\'autres activités sportives. J\'obtiens mon <strong>Yoga Teacher Training Certificate</strong> en 2023 au Portugal lors d\'une pause professionnelle.</p><p>En 2025, je deviens <strong>triathlète</strong> avec mon tout premier triathlon olympique.</p><p>La pratique sportive est pour moi la recherche d\'un <strong>bien-être général</strong> de mon corps et la <strong>recherche de l\'équilibre</strong>.</p>';
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
	}
}

/**
 * Pré-remplir les champs de la page Contact
 */
function nutriflow_prefill_contact_fields( $page_id ) {
	$pod = pods( 'page', $page_id );
	if ( ! $pod || ! $pod->exists() ) {
		return;
	}
	
	$data_to_save = array();
	
	if ( ! $pod->field( 'contact_title' ) ) {
		$data_to_save['contact_title'] = 'Contact';
	}
	if ( ! $pod->field( 'contact_subtitle' ) ) {
		$data_to_save['contact_subtitle'] = 'Consultations en nutrithérapie';
	}
	if ( ! $pod->field( 'contact_cta_text' ) ) {
		$data_to_save['contact_cta_text'] = 'Si tu as des questions ? N\'hésite pas à me contacter !';
	}
	if ( ! $pod->field( 'contact_button_text' ) ) {
		$data_to_save['contact_button_text'] = 'PRENDRE RDV';
	}
	
	if ( ! empty( $data_to_save ) ) {
		$pod->save( $data_to_save );
		$data_to_save = array();
	}
	
	// Contact Information - Pré-remplir avec les valeurs par défaut
	if ( ! $pod->field( 'contact_location' ) ) {
		$pod->save( array( 'contact_location' => 'à Ixelles ou en visio' ) );
	}
	if ( ! $pod->field( 'contact_schedule' ) ) {
		$pod->save( array( 'contact_schedule' => 'Le vendredi de 8h à 18h30 et le samedi de 10h à 18h30' ) );
	}
	if ( ! $pod->field( 'contact_phone' ) ) {
		$pod->save( array( 'contact_phone' => '+32 486 920 962' ) );
	}
	if ( ! $pod->field( 'contact_email' ) ) {
		$pod->save( array( 'contact_email' => 'fl.vanhecke@gmail.com' ) );
	}
}

// Hook pour exécuter la migration automatiquement (décommenter pour l'activer)
// add_action( 'admin_init', 'nutriflow_prefill_pods_fields', 999 );

// Alternative : créer une page admin pour déclencher manuellement la migration
add_action( 'admin_menu', 'nutriflow_add_migration_menu' );
function nutriflow_add_migration_menu() {
	add_submenu_page(
		'tools.php',
		'Migration Pods',
		'Migration Pods',
		'manage_options',
		'nutriflow-pods-migration',
		'nutriflow_migration_page'
	);
}

function nutriflow_migration_page() {
	if ( isset( $_POST['nutriflow_migrate_data'] ) && check_admin_referer( 'nutriflow_migrate' ) ) {
		try {
			nutriflow_prefill_pods_fields();
			echo '<div class="notice notice-success"><p>✅ Migration terminée ! Les champs ont été pré-remplis avec le contenu actuel.</p>';
			echo '<p><strong>Champs pré-remplis automatiquement :</strong></p>';
			echo '<ul style="margin-left: 20px;">';
			echo '<li>✅ Tous les champs texte (titles, subtitles, buttons)</li>';
			echo '<li>✅ Tous les champs WYSIWYG (descriptions, contenu, location_info, sportif_content)</li>';
			echo '<li>✅ Tous les champs de tarifs (pricing cards)</li>';
			echo '<li>✅ Témoignages (texte et auteur)</li>';
			echo '<li>✅ Informations de contact (lieu, horaires, téléphone, email)</li>';
			echo '<li>✅ Cartes Hero page Accompagnement (4 cartes avec titre et contenu)</li>';
			echo '<li>✅ Services page d\'accueil (4 services avec titre et description)</li>';
			echo '<li>✅ Contenu Sportif (sportif_content - WYSIWYG avec contenu et liste fusionnés)</li>';
			echo '<li>✅ Liste des Formations (formations_list - WYSIWYG)</li>';
			echo '</ul></div>';
		} catch ( Exception $e ) {
			echo '<div class="notice notice-error"><p>❌ Erreur lors de la migration : ' . esc_html( $e->getMessage() ) . '</p></div>';
		}
	}
	?>
	<div class="wrap">
		<h1>Migration Pods - Pré-remplir les Champs</h1>
		<p>Cette fonction va remplir tous les champs Pods avec le contenu actuel du site.</p>
		<p><strong>Note :</strong> Cette action ne remplacera que les champs vides. Les champs déjà remplis ne seront pas modifiés.</p>
		<form method="post">
			<?php wp_nonce_field( 'nutriflow_migrate' ); ?>
			<p>
				<button type="submit" name="nutriflow_migrate_data" class="button button-primary button-large">
					🚀 Pré-remplir tous les champs Pods
				</button>
			</p>
		</form>
	</div>
	<?php
}

