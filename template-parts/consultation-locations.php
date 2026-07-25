<?php
/**
 * Lieux de consultation : horaires par jour et/ou carte Google Maps.
 *
 * Usage : get_template_part( 'template-parts/consultation-locations', null, array( 'show' => 'schedule' ) );
 * $args['show'] : 'schedule' | 'map' | 'both' (défaut 'both')
 *
 * Les horaires sont éditables via le champ "consultation_schedule" (Pods/ACF) de la page ;
 * l'URL de la carte via Apparence > Personnaliser > Nutriflow Settings.
 *
 * @package Nutriflow
 */

$show = isset( $args['show'] ) ? $args['show'] : 'both';

if ( 'schedule' === $show || 'both' === $show ) :
	$schedule = function_exists( 'nutriflow_get_field' ) ? nutriflow_get_field( 'consultation_schedule' ) : ( function_exists( 'get_field' ) ? get_field( 'consultation_schedule' ) : false );
	if ( ! $schedule ) {
		$schedule = nutriflow_default_schedule_html();
	}
	?>
	<div class="nf-schedule-wrapper">
		<?php echo wp_kses_post( $schedule ); ?>
	</div>
	<?php
endif;

if ( 'map' === $show || 'both' === $show ) :
	$map_url = nutriflow_get_option( 'map_embed_url', 'https://www.google.com/maps?q=Clinica%20Vital%2C%20Chauss%C3%A9e%20de%20Wavre%20133%2C%201050%20Ixelles&z=15&output=embed' );
	if ( $map_url ) :
	?>
	<div class="nf-map">
		<iframe
			src="<?php echo esc_url( $map_url ); ?>"
			title="Carte des lieux de consultation"
			loading="lazy"
			allowfullscreen
			referrerpolicy="no-referrer-when-downgrade"></iframe>
	</div>
	<?php
	endif;
endif;
