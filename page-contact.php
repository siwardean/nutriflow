<?php
/**
 * Template Name: Contact
 * Template for the Contact page
 *
 * @package Nutriflow
 */

get_header();

// Get Customizer settings
$calendly_url = nutriflow_get_option( 'calendly_url', 'https://calendly.com/fl-vanhecke/premiere-consultation' );
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
						if ( function_exists('have_rows') && have_rows('contact_items') ) :
							while ( have_rows('contact_items') ) : the_row(); 
								$item_text = get_sub_field('item_text');
								$item_type = get_sub_field('item_type');
								$item_link = get_sub_field('item_link');
								
								if ( $item_type === 'link' && $item_link ) {
									echo '<li><a href="' . esc_url( $item_link ) . '">' . esc_html( $item_text ) . '</a></li>';
								} else {
									echo '<li>' . esc_html( $item_text ) . '</li>';
								}
							endwhile;
						else : ?>
							<li>à Ixelles ou en visio</li>
							<li>Le vendredi de 8h à 18h30 et le samedi de 10h à 18h30</li>
							<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
							<li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
						<?php endif; ?>
					</ul>
					
					<p class="nf-contact__cta-text nf-animate-on-scroll nf-fade-in nf-animate-delay-3">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('contact_cta_text') ?: 'Si tu as des questions ? N\'hésites pas à me contacter !';
						} else {
							echo 'Si tu as des questions ? N\'hésites pas à me contacter !';
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
				</div>
			</div>
		</section>
	<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
