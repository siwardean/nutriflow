<?php
/**
 * Template Name: Contact
 * Template for the Contact page
 *
 * @package Nutriflow
 */

get_header();

// Get Customizer settings
$calendly_url = nutriflow_get_option( 'calendly_url', 'https://calendly.com/fl-vanhecke' );
$phone        = nutriflow_get_option( 'phone', '+32 486 920 962' );
$email        = nutriflow_get_option( 'email', 'fl.vanhecke@gmail.com' );

// Check if page has custom content from block editor
$has_content = get_the_content() && trim( get_the_content() ) !== '';
?>

<main id="primary" class="site-main nf-contact-page">

	<?php if ( $has_content ) : ?>
		<?php
		// Display block editor content
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	<?php else : ?>
		<!-- Contact Section -->
		<section class="nf-contact">
			<div class="nf-contact__wrapper">
				<div class="nf-contact__image">
					<?php 
					$contact_image = function_exists('get_field') ? get_field('contact_image') : false;
					if ( $contact_image ) : ?>
						<img src="<?php echo esc_url( $contact_image['url'] ); ?>" alt="<?php echo esc_attr( $contact_image['alt'] ); ?>" class="nf-animate-on-scroll nf-slide-in-left" />
					<?php else : ?>
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/contact/florence-kitchen.jpg" alt="Florence dans sa cuisine" class="nf-animate-on-scroll nf-slide-in-left" />
					<?php endif; ?>
				</div>
				<div class="nf-contact__content">
					<h1 class="nf-contact__title nf-animate-on-scroll nf-fade-in">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('contact_title') ?: 'Contact';
						} else {
							echo 'Contact';
						}
						?>
					</h1>
					
					<h2 class="nf-contact__subtitle nf-animate-on-scroll nf-fade-in nf-animate-delay-1">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('contact_subtitle') ?: 'Consultations en nutrithérapie';
						} else {
							echo 'Consultations en nutrithérapie';
						}
						?>
					</h2>
					
					<ul class="nf-contact__list nf-animate-on-scroll nf-fade-in nf-animate-delay-2">
						<?php
						$icon_pin   = '<span class="nf-contact__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></span>';
						$icon_phone = '<span class="nf-contact__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></span>';
						$icon_mail  = '<span class="nf-contact__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></span>';

						// Lieu : généré depuis les lieux de consultation et la visio (ex. "à Ixelles ou en visio")
						$contact_location = nutriflow_locations_intro();
						if ( $contact_location ) {
							$contact_location = mb_strtoupper( mb_substr( $contact_location, 0, 1 ) ) . mb_substr( $contact_location, 1 );
							echo '<li>' . $icon_pin . '<span>' . esc_html( $contact_location ) . '</span></li>';
						}

						// Téléphone
						$contact_phone = function_exists('get_field') ? get_field('contact_phone') : false;
						$contact_phone = $contact_phone ? $contact_phone : $phone;
						echo '<li><a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $contact_phone ) ) . '">' . $icon_phone . '<span>' . esc_html( $contact_phone ) . '</span></a></li>';

						// Email
						$contact_email = function_exists('get_field') ? get_field('contact_email') : false;
						$contact_email = $contact_email ? $contact_email : $email;
						echo '<li><a href="mailto:' . esc_attr( $contact_email ) . '">' . $icon_mail . '<span>' . esc_html( $contact_email ) . '</span></a></li>';
						?>
					</ul>

					<div class="nf-contact__schedule nf-animate-on-scroll nf-fade-in nf-animate-delay-2">
						<?php get_template_part( 'template-parts/consultation-locations', null, array( 'show' => 'schedule' ) ); ?>
					</div>

					<p class="nf-contact__cta-text nf-animate-on-scroll nf-fade-in nf-animate-delay-3">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('contact_cta_text') ?: 'Si tu as des questions ? N\'hésite pas à me contacter !';
						} else {
							echo 'Si tu as des questions ? N\'hésite pas à me contacter !';
						}
						?>
					</p>
					
					<a href="<?php echo esc_url( $calendly_url ); ?>" target="_blank" class="nf-btn nf-btn--primary nf-contact__btn nf-animate-on-scroll nf-fade-in nf-animate-delay-4">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('contact_button_text') ?: 'PRENDRE RDV';
						} else {
							echo 'PRENDRE RDV';
						}
						?>
					</a>

					<div class="nf-contact__map nf-animate-on-scroll nf-fade-in">
						<?php get_template_part( 'template-parts/consultation-locations', null, array( 'show' => 'map' ) ); ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
