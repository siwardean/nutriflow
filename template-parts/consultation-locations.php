<?php
/**
 * Lieux de consultation : cartes des lieux (horaires par jour) et/ou carte géographique.
 *
 * Usage : get_template_part( 'template-parts/consultation-locations', null, array( 'show' => 'schedule' ) );
 * $args['show'] : 'schedule' | 'map' | 'both' (défaut 'both')
 *
 * Les lieux (nom, adresse, horaires, site, lien Google Maps) et les horaires en visio
 * sont édités dans l'admin de la page Contact, groupe "Lieux de consultation"
 * (voir inc/consultation-locations.php). La carte (Leaflet + OpenStreetMap) place
 * automatiquement tous les lieux et adapte son cadrage.
 *
 * @package Nutriflow
 */

$show      = isset( $args['show'] ) ? $args['show'] : 'both';
$locations = nutriflow_get_locations();

$nf_icon_pin   = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
$nf_icon_video = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>';
$nf_icon_arrow = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>';

if ( 'schedule' === $show || 'both' === $show ) :
	// Une carte par lieu + une carte "En visio", triées par premier jour de la semaine
	// (ordre de saisie à jour égal), pour une lecture chronologique, surtout en une colonne sur mobile.
	$cards = array();
	foreach ( $locations as $index => $location ) {
		$slots   = nutriflow_schedule_lines( $location['horaires'] );
		$cards[] = array(
			'type'     => 'lieu',
			'rank'     => nutriflow_schedule_rank( $slots ),
			'order'    => $index,
			'location' => $location,
			'slots'    => $slots,
		);
	}
	$visio_lines = nutriflow_get_visio_schedule();
	if ( ! empty( $visio_lines ) ) {
		$cards[] = array(
			'type'  => 'visio',
			'rank'  => nutriflow_schedule_rank( $visio_lines ),
			'order' => count( $locations ),
			'slots' => $visio_lines,
		);
	}
	usort( $cards, function ( $a, $b ) {
		return ( $a['rank'] <=> $b['rank'] ) ?: ( $a['order'] <=> $b['order'] );
	} );
	?>
	<div class="nf-lieux">
		<?php foreach ( $cards as $card ) : ?>
			<?php if ( 'visio' === $card['type'] ) : ?>
				<article class="nf-lieu nf-lieu--visio">
					<div class="nf-lieu__head">
						<span class="nf-lieu__icon"><?php echo $nf_icon_video; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<h3 class="nf-lieu__name">En visio</h3>
					</div>
					<ul class="nf-lieu__slots">
						<?php foreach ( $card['slots'] as $slot ) :
							list( $day, $time ) = nutriflow_split_schedule_line( $slot );
							?>
							<li>
								<span class="nf-lieu__day"><?php echo esc_html( $day ); ?></span>
								<?php if ( '' !== $time ) : ?><span class="nf-lieu__time"><?php echo esc_html( $time ); ?></span><?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</article>
				<?php continue; ?>
			<?php endif; ?>
			<?php
			$location = $card['location'];
			// Adresse sur deux lignes : rue d'un côté, code postal et ville de l'autre.
			$address_parts = array_map( 'trim', explode( ',', $location['adresse'] ) );
			$address_city  = count( $address_parts ) > 1 ? array_pop( $address_parts ) : '';
			$address_line  = implode( ', ', $address_parts );
			?>
			<article class="nf-lieu">
				<div class="nf-lieu__head">
					<span class="nf-lieu__icon"><?php echo $nf_icon_pin; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3 class="nf-lieu__name">
						<?php if ( '' !== $location['site'] ) : ?>
							<a href="<?php echo esc_url( $location['site'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $location['nom'] ? $location['nom'] : $location['adresse'] ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $location['nom'] ? $location['nom'] : $location['adresse'] ); ?>
						<?php endif; ?>
					</h3>
				</div>
				<?php if ( ! empty( $card['slots'] ) ) : ?>
					<ul class="nf-lieu__slots">
						<?php foreach ( $card['slots'] as $slot ) :
							list( $day, $time ) = nutriflow_split_schedule_line( $slot );
							?>
							<li>
								<span class="nf-lieu__day"><?php echo esc_html( $day ); ?></span>
								<?php if ( '' !== $time ) : ?><span class="nf-lieu__time"><?php echo esc_html( $time ); ?></span><?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( '' !== $location['adresse'] ) : ?>
					<p class="nf-lieu__address">
						<?php echo esc_html( $address_line ); ?>
						<?php if ( $address_city ) : ?><br><?php echo esc_html( $address_city ); ?><?php endif; ?>
					</p>
				<?php endif; ?>
				<a class="nf-lieu__link" href="<?php echo esc_url( $location['maps_url'] ); ?>" target="_blank" rel="noopener">Itinéraire <?php echo $nf_icon_arrow; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
			</article>
		<?php endforeach; ?>
	</div>
	<?php
endif;

if ( 'map' === $show || 'both' === $show ) :
	$markers = array();
	foreach ( $locations as $location ) {
		if ( null === $location['lat'] || null === $location['lng'] ) {
			continue;
		}
		$markers[] = array(
			'nom'     => $location['nom'],
			'adresse' => $location['adresse'],
			'lat'     => $location['lat'],
			'lng'     => $location['lng'],
			'url'     => $location['maps_url'],
		);
	}
	if ( ! empty( $locations ) ) :
	?>
	<div class="nf-map">
		<?php if ( ! empty( $markers ) ) : ?>
			<div class="nf-map__canvas" data-nf-map="<?php echo esc_attr( wp_json_encode( $markers ) ); ?>" aria-label="Carte des lieux de consultation"></div>
		<?php else :
			// Aucune adresse géocodée (service indisponible) : carte Google Maps du premier lieu.
			$first = $locations[0];
			$query = trim( $first['nom'] . ', ' . $first['adresse'], ', ' );
			?>
			<iframe
				src="<?php echo esc_url( 'https://www.google.com/maps?q=' . rawurlencode( $query ) . '&z=16&output=embed' ); ?>"
				title="Carte des lieux de consultation"
				loading="lazy"
				allowfullscreen
				referrerpolicy="no-referrer-when-downgrade"></iframe>
		<?php endif; ?>
	</div>
	<?php
	endif;
endif;
