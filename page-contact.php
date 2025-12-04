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
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/contact/florence-kitchen.jpg" alt="Florence dans sa cuisine" />
				</div>
				<div class="nf-contact__content">
					<h1 class="nf-contact__title">Contact</h1>
					
					<h2 class="nf-contact__subtitle">Consultations en nutrithérapie</h2>
					
					<ul class="nf-contact__list">
						<li>à Ixelles ou en visio</li>
						<li>Le vendredi de 8h à 18h30 et le samedi de 10h à 18h30</li>
						<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
						<li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
					</ul>
					
					<p class="nf-contact__cta-text">Si tu as des questions ? N'hésites pas à me contacter !</p>
					
					<a href="<?php echo esc_url( $calendly_url ); ?>" target="_blank" class="nf-btn nf-btn--primary nf-contact__btn">PRENDRE RDV</a>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
