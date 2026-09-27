<?php
/**
 * Lieux de consultation : données structurées, géocodage et helpers d'affichage.
 *
 * Les lieux sont édités par la cliente dans l'admin de la page Contact
 * (groupe Pods "Lieux de consultation") : nom, adresse, site web, créneaux
 * (un jour et ses horaires par ligne), lien Google Maps. Les pages Contact et Accompagnement affichent à partir
 * de ces données la liste des horaires par jour et une carte (Leaflet +
 * OpenStreetMap) dont le cadrage englobe automatiquement tous les lieux.
 *
 * Les adresses sont géocodées via Nominatim (OpenStreetMap), avec mise en
 * cache : un lieu ajouté ou une adresse modifiée dans l'admin est replacé
 * sur la carte sans autre intervention.
 *
 * @package Nutriflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Nombre maximum de lieux physiques éditables dans l'admin. */
define( 'NUTRIFLOW_MAX_LOCATIONS', 3 );

/** Clés d'un lieu, dans l'ordre des champs Pods "lieu_N_<clé>". */
function nutriflow_location_keys() {
	return array( 'nom', 'adresse', 'site', 'horaires', 'maps' );
}

/**
 * Lieux par défaut (contenu actuel du site), utilisés en fallback tant que
 * les champs de la page Contact ne sont pas remplis, et pour les pré-remplir.
 */
function nutriflow_default_locations() {
	return array(
		array(
			'nom'      => 'Clinica Vital',
			'adresse'  => 'Chaussée de Wavre 261A, 1050 Ixelles',
			'site'     => 'https://www.clinicavital.be',
			'horaires' => 'Mercredi 8h30 – 18h30',
			'maps'     => 'https://maps.app.goo.gl/WasSsYSMMiYL91ZA8',
		),
	);
}

/** Horaires en visio par défaut : une ligne par jour. */
function nutriflow_default_visio_schedule() {
	return "Jeudi 8h30 – 19h";
}

/**
 * Coordonnées connues pour les adresses par défaut : la carte fonctionne
 * même si le géocodage en ligne est indisponible.
 */
function nutriflow_known_coordinates() {
	return array(
		'chaussée de wavre 261a, 1050 ixelles' => array( 'lat' => 50.8357423, 'lng' => 4.3779692 ),
	);
}

/**
 * ID de la page qui porte les champs des lieux (page avec le template Contact).
 */
function nutriflow_get_locations_page_id() {
	static $page_id = null;
	if ( null !== $page_id ) {
		return $page_id;
	}

	$pages = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-contact.php',
	) );

	if ( ! empty( $pages ) ) {
		$page_id = (int) $pages[0];
		return $page_id;
	}

	$page    = get_page_by_path( 'contact' );
	$page_id = $page ? (int) $page->ID : 0;
	return $page_id;
}

/** Valeur texte d'un champ de la page des lieux ('' si vide). */
function nutriflow_location_field( $name, $page_id ) {
	$value = function_exists( 'nutriflow_get_field' ) ? nutriflow_get_field( $name, $page_id ) : get_post_meta( $page_id, $name, true );
	return is_string( $value ) ? trim( $value ) : '';
}

/**
 * Vrai dès que les champs de l'admin font foi : soit ils ont été pré-remplis une fois
 * (marqueur posé au déploiement), soit au moins un lieu est renseigné. Tant que ce
 * n'est pas le cas (thème déployé, admin pas encore visité), les valeurs par défaut
 * s'affichent. Ensuite, des champs vidés par la cliente donnent un affichage vide :
 * on ne retombe jamais sur les valeurs par défaut.
 */
function nutriflow_locations_configured() {
	$page_id = nutriflow_get_locations_page_id();
	if ( ! $page_id ) {
		return false;
	}
	if ( get_post_meta( $page_id, '_nf_locations_seeded', true ) ) {
		return true;
	}
	for ( $i = 1; $i <= NUTRIFLOW_MAX_LOCATIONS; $i++ ) {
		if ( '' !== nutriflow_location_field( "lieu_{$i}_nom", $page_id ) || '' !== nutriflow_location_field( "lieu_{$i}_adresse", $page_id ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Lieux de consultation, avec coordonnées (lat/lng ou null) et lien Google Maps.
 *
 * @return array[] Chaque lieu : nom, adresse, site, horaires, maps, lat, lng, maps_url.
 */
function nutriflow_get_locations() {
	static $locations = null;
	if ( null !== $locations ) {
		return $locations;
	}

	$locations = array();

	if ( nutriflow_locations_configured() ) {
		$page_id = nutriflow_get_locations_page_id();
		for ( $i = 1; $i <= NUTRIFLOW_MAX_LOCATIONS; $i++ ) {
			$location = array();
			foreach ( nutriflow_location_keys() as $key ) {
				$location[ $key ] = nutriflow_location_field( "lieu_{$i}_{$key}", $page_id );
			}
			if ( '' === $location['nom'] && '' === $location['adresse'] ) {
				continue;
			}
			$locations[] = $location;
		}
	} else {
		$locations = nutriflow_default_locations();
	}

	foreach ( $locations as &$location ) {
		$coords              = nutriflow_geocode_address( $location['adresse'] );
		$location['lat']     = $coords ? $coords['lat'] : null;
		$location['lng']     = $coords ? $coords['lng'] : null;
		$location['maps_url'] = nutriflow_location_maps_url( $location );
	}
	unset( $location );

	return $locations;
}

/**
 * Découpe un champ d'horaires multi-lignes en créneaux (une ligne = un créneau),
 * sans les lignes vides. Le champ peut revenir formaté par Pods (wpautop) :
 * <br> et </p> sont ramenés à des sauts de ligne.
 */
function nutriflow_schedule_lines( $raw ) {
	$raw   = wp_strip_all_tags( preg_replace( '/<br\s*\/?>|<\/p>/i', "\n", (string) $raw ) );
	$lines = preg_split( '/\r\n|\r|\n/', $raw );
	return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
}

/**
 * Créneaux en visio (un par ligne du champ "Consultations en visio").
 */
function nutriflow_get_visio_schedule() {
	if ( nutriflow_locations_configured() ) {
		$raw = nutriflow_location_field( 'visio_horaires', nutriflow_get_locations_page_id() );
	} else {
		$raw = nutriflow_default_visio_schedule();
	}
	return nutriflow_schedule_lines( $raw );
}

/**
 * Sépare "Mercredi 8h30 – 18h30" en jour ("Mercredi") et plage horaire ("8h30 – 18h30") :
 * le jour est tout ce qui précède le premier mot contenant un chiffre.
 */
function nutriflow_split_schedule_line( $line ) {
	$line = trim( $line );
	if ( preg_match( '/^(.*?)\s*(\S*\d.*)$/u', $line, $m ) && '' !== trim( $m[1] ) ) {
		return array( trim( $m[1] ), trim( $m[2] ) );
	}
	return array( $line, '' );
}

/**
 * Rang dans la semaine (1 = lundi … 7 = dimanche) du premier jour cité dans un texte,
 * 99 si aucun jour n'est reconnu. Sert à trier les cartes de lieux par jour.
 */
function nutriflow_weekday_rank( $text ) {
	$days = array( 'lundi' => 1, 'mardi' => 2, 'mercredi' => 3, 'jeudi' => 4, 'vendredi' => 5, 'samedi' => 6, 'dimanche' => 7 );
	$text = function_exists( 'mb_strtolower' ) ? mb_strtolower( (string) $text ) : strtolower( (string) $text );
	$best = 99;
	$pos  = PHP_INT_MAX;
	foreach ( $days as $name => $rank ) {
		$found = strpos( $text, $name );
		if ( false !== $found && $found < $pos ) {
			$pos  = $found;
			$best = $rank;
		}
	}
	return $best;
}

/**
 * Rang du premier jour d'une liste de créneaux (le plus tôt dans la semaine).
 */
function nutriflow_schedule_rank( $lines ) {
	$best = 99;
	foreach ( (array) $lines as $line ) {
		$best = min( $best, nutriflow_weekday_rank( $line ) );
	}
	return $best;
}

/**
 * Lien Google Maps d'un lieu : celui saisi dans l'admin, sinon une recherche
 * Google Maps générée à partir du nom et de l'adresse.
 */
function nutriflow_location_maps_url( $location ) {
	if ( ! empty( $location['maps'] ) ) {
		return $location['maps'];
	}
	$query = trim( $location['nom'] . ', ' . $location['adresse'], ', ' );
	return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $query );
}

/**
 * Résumé des lieux : "Ixelles (Clinica Vital)" ou "Ixelles (Clinica Vital) et Uccle (Centre X)",
 * ou sans les noms : "Ixelles" / "Ixelles et Uccle". La ville est lue après le code postal.
 *
 * @param bool $with_names Inclure le nom du lieu entre parenthèses.
 */
function nutriflow_locations_summary( $with_names = true ) {
	$parts = array();
	foreach ( nutriflow_get_locations() as $location ) {
		$city = '';
		if ( preg_match( '/\b\d{4}\s+([^,]+)$/u', $location['adresse'], $m ) ) {
			$city = trim( $m[1] );
		}
		if ( $city && $location['nom'] && $with_names ) {
			$part = $city . ' (' . $location['nom'] . ')';
		} elseif ( $city ) {
			$part = $city;
		} else {
			$part = $location['nom'];
		}
		if ( '' !== $part && ! in_array( $part, $parts, true ) ) {
			$parts[] = $part;
		}
	}
	if ( empty( $parts ) ) {
		return '';
	}
	$last = array_pop( $parts );
	return $parts ? implode( ', ', $parts ) . ' et ' . $last : $last;
}

/**
 * Phrase d'introduction générée depuis les lieux et la visio :
 * "à Ixelles ou en visio", "à Ixelles", "en visio", ou '' si rien n'est configuré.
 * Ainsi, arrêter la visio (champ vide) ou changer de lieu met à jour toutes les pages.
 *
 * @param bool $with_names Inclure le nom des lieux entre parenthèses.
 */
function nutriflow_locations_intro( $with_names = false ) {
	$summary = nutriflow_locations_summary( $with_names );
	$visio   = ! empty( nutriflow_get_visio_schedule() );
	if ( $summary && $visio ) {
		return 'à ' . $summary . ' ou en visio';
	}
	if ( $summary ) {
		return 'à ' . $summary;
	}
	return $visio ? 'en visio' : '';
}

/**
 * Géocode une adresse via Nominatim (OpenStreetMap), avec cache permanent des
 * succès (option) et cache d'une heure des échecs (transient).
 *
 * @return array|null array( 'lat' => float, 'lng' => float ) ou null.
 */
function nutriflow_geocode_address( $address ) {
	$address = trim( (string) $address );
	if ( '' === $address ) {
		return null;
	}

	$normalized = function_exists( 'mb_strtolower' ) ? mb_strtolower( $address ) : strtolower( $address );
	$normalized = preg_replace( '/\s+/u', ' ', $normalized );

	$known = nutriflow_known_coordinates();
	if ( isset( $known[ $normalized ] ) ) {
		return $known[ $normalized ];
	}

	$key   = md5( $normalized );
	$cache = get_option( 'nutriflow_geocode_cache', array() );
	if ( ! is_array( $cache ) ) {
		$cache = array();
	}
	if ( isset( $cache[ $key ] ) && is_array( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}
	if ( get_transient( 'nutriflow_geocode_fail_' . $key ) ) {
		return null;
	}

	// Nominatim ne connaît pas les noms d'établissements : on envoie l'adresse seule,
	// puis l'adresse suffixée du pays si la première tentative ne donne rien.
	$queries = array( $address );
	if ( ! preg_match( '/belgi/i', $address ) ) {
		$queries[] = $address . ', Belgique';
	}

	$result = null;
	foreach ( $queries as $index => $query ) {
		if ( $index > 0 ) {
			usleep( 1100000 ); // Politique d'usage Nominatim : 1 requête par seconde maximum.
		}
		$response = wp_remote_get(
			'https://nominatim.openstreetmap.org/search?' . http_build_query( array( 'format' => 'json', 'limit' => 1, 'q' => $query ) ),
			array(
				'timeout'    => 8,
				'user-agent' => 'Nutriflow WordPress theme (' . home_url() . ')',
			)
		);
		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			continue;
		}
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! empty( $data[0]['lat'] ) && ! empty( $data[0]['lon'] ) ) {
			$result = array( 'lat' => (float) $data[0]['lat'], 'lng' => (float) $data[0]['lon'] );
			break;
		}
	}

	if ( $result ) {
		$cache[ $key ] = $result;
		update_option( 'nutriflow_geocode_cache', $cache, false );
	} else {
		set_transient( 'nutriflow_geocode_fail_' . $key, 1, HOUR_IN_SECONDS );
	}

	return $result;
}

/**
 * À l'enregistrement de la page Contact : géocode immédiatement les nouvelles
 * adresses (le résultat est en cache pour l'affichage) et mémorise celles
 * introuvables pour avertir dans l'admin.
 */
function nutriflow_geocode_locations_on_save( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}
	if ( 'page-contact.php' !== get_page_template_slug( $post_id ) ) {
		return;
	}

	$not_found = array();
	for ( $i = 1; $i <= NUTRIFLOW_MAX_LOCATIONS; $i++ ) {
		$address = trim( (string) get_post_meta( $post_id, "lieu_{$i}_adresse", true ) );
		if ( '' === $address ) {
			continue;
		}
		if ( ! nutriflow_geocode_address( $address ) ) {
			$not_found[] = $address;
		}
	}

	if ( $not_found ) {
		set_transient( 'nutriflow_geocode_notice_' . get_current_user_id(), $not_found, 5 * MINUTE_IN_SECONDS );
	}
}
add_action( 'save_post_page', 'nutriflow_geocode_locations_on_save', 999 );

/**
 * Avertissement dans l'admin quand une adresse n'a pas pu être placée sur la carte.
 */
function nutriflow_geocode_admin_notice() {
	$not_found = get_transient( 'nutriflow_geocode_notice_' . get_current_user_id() );
	if ( empty( $not_found ) || ! is_array( $not_found ) ) {
		return;
	}
	delete_transient( 'nutriflow_geocode_notice_' . get_current_user_id() );
	echo '<div class="notice notice-warning is-dismissible"><p><strong>Carte des lieux :</strong> l\'adresse suivante n\'a pas été trouvée sur OpenStreetMap et n\'apparaîtra pas sur la carte : '
		. esc_html( implode( ' ; ', $not_found ) )
		. '. Vérifiez l\'orthographe, au format « Rue Exemple 12, 1000 Bruxelles ».</p></div>';
}
add_action( 'admin_notices', 'nutriflow_geocode_admin_notice' );

/**
 * Pré-remplit les champs "Lieux de consultation" de la page Contact avec le
 * contenu actuel du site s'ils sont vides (première activation / déploiement).
 */
function nutriflow_seed_location_fields() {
	$page_id = nutriflow_get_locations_page_id();
	if ( ! $page_id || nutriflow_locations_configured() ) {
		return;
	}

	foreach ( nutriflow_default_locations() as $index => $location ) {
		$n = $index + 1;
		foreach ( nutriflow_location_keys() as $key ) {
			update_post_meta( $page_id, "lieu_{$n}_{$key}", $location[ $key ] );
		}
	}
	if ( '' === trim( (string) get_post_meta( $page_id, 'visio_horaires', true ) ) ) {
		update_post_meta( $page_id, 'visio_horaires', nutriflow_default_visio_schedule() );
	}
	// À partir d'ici, ce sont les champs de l'admin qui font foi, même vides.
	update_post_meta( $page_id, '_nf_locations_seeded', 1 );
}

/**
 * Définition Pods des champs "Lieux de consultation" (page Contact).
 */
function nutriflow_location_pods_fields() {
	$fields = array();
	$weight = 0;
	for ( $i = 1; $i <= NUTRIFLOW_MAX_LOCATIONS; $i++ ) {
		$fields[] = array(
			'name'        => "lieu_{$i}_nom",
			'label'       => "Lieu {$i} : nom",
			'type'        => 'text',
			'description' => 1 === $i ? 'Ex. : Clinica Vital.' : "Laisser vide s'il n'y a pas de lieu {$i} : la carte et le repère n'apparaissent pas.",
			'weight'      => $weight++,
		);
		$fields[] = array(
			'name'        => "lieu_{$i}_adresse",
			'label'       => "Lieu {$i} : adresse",
			'type'        => 'text',
			'description' => 'Rue et numéro, code postal et ville (ex. : Chaussée de Wavre 261A, 1050 Ixelles). Sert à placer le lieu sur la carte.',
			'weight'      => $weight++,
		);
		$fields[] = array(
			'name'        => "lieu_{$i}_horaires",
			'label'       => "Lieu {$i} : jours et horaires",
			'type'        => 'paragraph',
			'description' => 'Un créneau par ligne, ex. : Mercredi 8h30 – 18h30 (puis Vendredi 14h – 18h sur la ligne suivante).',
			'weight'      => $weight++,
		);
		$fields[] = array(
			'name'        => "lieu_{$i}_site",
			'label'       => "Lieu {$i} : site web (optionnel)",
			'type'        => 'website',
			'description' => 'Le nom du lieu devient un lien vers ce site.',
			'weight'      => $weight++,
		);
		$fields[] = array(
			'name'        => "lieu_{$i}_maps",
			'label'       => "Lieu {$i} : lien Google Maps (optionnel)",
			'type'        => 'website',
			'description' => 'Lien de partage Google Maps. Si vide, un lien est généré à partir de l\'adresse.',
			'weight'      => $weight++,
		);
	}
	$fields[] = array(
		'name'          => 'visio_horaires',
		'label'         => 'Consultations en visio : jours et horaires',
		'type'          => 'paragraph',
		'description'   => 'Une ligne par jour, ex. : Jeudi 8h30 – 19h',
		'default_value' => nutriflow_default_visio_schedule(),
		'weight'        => $weight++,
	);
	return $fields;
}
