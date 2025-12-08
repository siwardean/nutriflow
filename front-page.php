<?php
/**
 * Template Name: Accueil
 * Template for the Nutriflow home page
 *
 * @package Nutriflow
 */

get_header();

// Get Customizer settings
$calendly_url = nutriflow_get_option( 'calendly_url', 'https://calendly.com/fl-vanhecke/premiere-consultation' );

// Check if page has custom content from block editor
$has_content = get_the_content() && trim( get_the_content() ) !== '';
?>

<main id="primary" class="site-main nf-front-page">

	<?php if ( $has_content ) : ?>
		<?php
		// Display block editor content
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	<?php else : ?>
		<!-- Default Hero Section -->
		<section class="nf-hero" id="home">
			<div class="nf-hero__wrapper">
				<div class="nf-hero__content">
					<div class="nf-logo">
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logo-nutriflow.svg" alt="Nutriflow Logo" class="nf-logo__image nf-animate-on-scroll nf-fade-in" />
					</div>
					<h1 class="nf-hero__title nf-animate-on-scroll nf-fade-in nf-animate-delay-1">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('homepage_hero_title') ?: 'NUTRIFLOW';
						} else {
							echo 'NUTRIFLOW';
						}
						?>
					</h1>
					<p class="nf-hero__subtitle nf-animate-on-scroll nf-fade-in nf-animate-delay-2">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('homepage_hero_subtitle') ?: '- NUTRITHÉRAPEUTE À BRUXELLES -';
						} else {
							echo '- NUTRITHÉRAPEUTE À BRUXELLES -';
						}
						?>
					</p>
					<p class="nf-hero__description nf-animate-on-scroll nf-fade-in nf-animate-delay-3">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('homepage_hero_description') ?: 'Accompagnement en nutrithérapie: rééquilibrage alimentaire, nutrition sportive, troubles hormonaux et digestifs.';
						} else {
							echo 'Accompagnement en nutrithérapie: rééquilibrage alimentaire, nutrition sportive, troubles hormonaux et digestifs.';
						}
						?>
					</p>
					<a class="nf-btn nf-btn--primary nf-animate-on-scroll nf-fade-in nf-animate-delay-4" href="<?php echo esc_url( $calendly_url ); ?>" target="_blank">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('homepage_hero_button') ?: 'PRENDRE RDV';
						} else {
							echo 'PRENDRE RDV';
						}
						?>
					</a>
				</div>
				<div class="nf-hero__media">
					<?php 
					$hero_image = function_exists('get_field') ? get_field('homepage_hero_image') : false;
					if ( $hero_image ) : ?>
						<img src="<?php echo esc_url( $hero_image['url'] ); ?>" alt="<?php echo esc_attr( $hero_image['alt'] ); ?>" class="nf-animate-on-scroll nf-slide-in-right" />
					<?php else : ?>
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero/hero-kitchen.jpg" alt="Kitchen scene" class="nf-animate-on-scroll nf-slide-in-right" />
					<?php endif; ?>
				</div>
			</div>
		</section>

		<!-- Default About Me Section -->
		<section class="nf-about" id="about">
			<div class="nf-about__wrapper">
				<div class="nf-about__image">
					<?php 
					$about_image = function_exists('get_field') ? get_field('homepage_about_image') : false;
					if ( $about_image ) : ?>
						<img src="<?php echo esc_url( $about_image['url'] ); ?>" alt="<?php echo esc_attr( $about_image['alt'] ); ?>" class="nf-animate-on-scroll nf-slide-in-left" />
					<?php else : ?>
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/about/pool-woman.jpg" alt="Woman by pool" class="nf-animate-on-scroll nf-slide-in-left" />
					<?php endif; ?>
				</div>
				<div class="nf-about__content">
					<h2 class="nf-about__title nf-animate-on-scroll nf-fade-in nf-animate-delay-1">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('homepage_about_title') ?: 'Qui suis-je ?';
						} else {
							echo 'Qui suis-je ?';
						}
						?>
					</h2>
					<div class="nf-about__text">
						<p class="nf-about__text--left nf-animate-on-scroll nf-fade-in nf-animate-delay-2">
							<?php 
							if ( function_exists('get_field') && get_field('homepage_about_text_left') ) {
								echo wp_kses_post( get_field('homepage_about_text_left') );
							} else {
								echo 'Je suis Florence, <strong>nutrithérapeute passionnée</strong> par l\'impact de la nutrition sur notre santé globale. Mon <strong>approche</strong> est douce, basée sur la <strong>science</strong> et centrée sur l\'écoute du corps.';
							}
							?>
						</p>
						<p class="nf-about__text--right nf-animate-on-scroll nf-fade-in nf-animate-delay-3">
							<?php 
							if ( function_exists('get_field') && get_field('homepage_about_text_right') ) {
								echo wp_kses_post( get_field('homepage_about_text_right') );
							} else {
								echo 'La <strong>nutrithérapie</strong> est, selon moi, un outil de <strong>prévention</strong>, mais aussi de transformation et d\'autonomie : elle <strong>reconnecte</strong> à soi, à ses <strong>besoins</strong> et à son pouvoir d\'agir.';
							}
							?>
						</p>
					</div>
					<div class="nf-about__circles">
						<span class="nf-about__circle nf-about__circle--white"></span>
						<span class="nf-about__circle nf-about__circle--green"></span>
					</div>
				</div>
			</div>
		</section>

		<!-- Default Consultation Objective Section -->
		<section class="nf-consultation" id="consultation">
			<div class="nf-consultation__wrapper">
				<div class="nf-consultation__content">
					<h2 class="nf-consultation__title nf-animate-on-scroll nf-fade-in">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('homepage_consult_title') ?: 'L\'objectif de la consultation';
						} else {
							echo 'L\'objectif de la consultation';
						}
						?>
					</h2>
					<?php 
					$consult_text = function_exists('get_field') ? get_field('homepage_consult_text') : false;
					if ( $consult_text ) {
						echo '<div class="nf-animate-on-scroll nf-fade-in nf-animate-delay-1">' . wp_kses_post( $consult_text ) . '</div>';
					} else {
						echo '<div class="nf-animate-on-scroll nf-fade-in nf-animate-delay-1"><p class="nf-consultation__text">Je t\'accompagne pour mieux <strong>comprendre ton corps</strong>, ses <strong>besoins nutritionnels</strong> et ses <strong>mécanismes</strong>, afin que tu puisses faire des choix éclairés, <strong>durables</strong> et bienveillants.</p></div>';
						echo '<div class="nf-animate-on-scroll nf-fade-in nf-animate-delay-2"><p class="nf-consultation__text">Ensemble, nous rendrons les clés de la nutrition <strong>accessibles</strong>, simples et ancrées dans le plaisir. Pas de régime strict ni d\'interdits, mais des <strong>réflexes santé</strong> concrets, adaptés à ta réalité. Mon approche vise à reconnecter l\'alimentation au plaisir gourmand, tout en t\'apportant les clés d\'un mieux-être durable, étape par étape.</p></div>';
					}
					?>
					<a class="nf-btn nf-btn--primary nf-animate-on-scroll nf-fade-in nf-animate-delay-3" href="<?php echo esc_url( home_url( '/accompagnement/' ) ); ?>">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('homepage_consult_button') ?: 'DÉCOUVRIR LES ACCOMPAGNEMENTS';
						} else {
							echo 'DÉCOUVRIR LES ACCOMPAGNEMENTS';
						}
						?>
					</a>
				</div>
				<div class="nf-consultation__image">
					<?php 
					$consult_image = function_exists('get_field') ? get_field('homepage_consult_image') : false;
					if ( $consult_image ) : ?>
						<img src="<?php echo esc_url( $consult_image['url'] ); ?>" alt="<?php echo esc_attr( $consult_image['alt'] ); ?>" class="nf-animate-on-scroll nf-slide-in-right" />
					<?php else : ?>
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/consultation/market-woman.jpg" alt="Woman at market" class="nf-animate-on-scroll nf-slide-in-right" />
					<?php endif; ?>
				</div>
			</div>
		</section>

		<!-- Default Services Section -->
		<section class="nf-services" id="services">
			<h2 class="nf-services__heading nf-animate-on-scroll nf-fade-in">
				<?php 
				if ( function_exists('get_field') ) {
					echo get_field('homepage_services_heading') ?: 'Je t\'accompagne pour';
				} else {
					echo 'Je t\'accompagne pour';
				}
				?>
			</h2>
			<div class="nf-services__grid">
				<?php 
				if ( function_exists('have_rows') && have_rows('homepage_services') ) :
					$index = 0;
					while ( have_rows('homepage_services') ) : the_row(); 
						$index++;
						$delay = 'nf-animate-delay-' . ($index % 4 + 1);
					?>
						<article class="nf-service-card nf-animate-on-scroll nf-slide-in-up <?php echo esc_attr( $delay ); ?>">
							<h3 class="nf-service-card__title"><?php echo esc_html( get_sub_field('service_title') ); ?></h3>
							<p class="nf-service-card__description"><?php echo esc_html( get_sub_field('service_description') ); ?></p>
						</article>
					<?php endwhile;
				else : ?>
					<article class="nf-service-card nf-animate-on-scroll nf-slide-in-up nf-animate-delay-1">
						<h3 class="nf-service-card__title">Rééquilibrage alimentaire</h3>
						<p class="nf-service-card__description">Rééquilibrer ton alimentation et/ou perdre du poids.</p>
					</article>
					<article class="nf-service-card nf-animate-on-scroll nf-slide-in-up nf-animate-delay-2">
						<h3 class="nf-service-card__title">Troubles hormonaux</h3>
						<p class="nf-service-card__description">Soulager tes symptômes et rééquilibrer les troubles hormonaux : SPM, endométriose, SOPK,...</p>
					</article>
					<article class="nf-service-card nf-animate-on-scroll nf-slide-in-up nf-animate-delay-3">
						<h3 class="nf-service-card__title">Nutrition & performances sportives</h3>
						<p class="nf-service-card__description">Améliorer tes performances et mieux comprendre l'impact de ton alimentation sur tes résultats.</p>
					</article>
					<article class="nf-service-card nf-animate-on-scroll nf-slide-in-up nf-animate-delay-4">
						<h3 class="nf-service-card__title">Troubles digestifs</h3>
						<p class="nf-service-card__description">Prendre soin de ton microbiote mais également pour soulager les troubles digestifs chroniques (SII, MICI, SIBO,..).</p>
					</article>
				<?php endif; ?>
			</div>
		</section>

		<!-- Default Testimonials Section -->
		<section class="nf-testimonials" id="testimonials">
			<h2 class="nf-testimonials__heading nf-animate-on-scroll nf-fade-in">
				<?php 
				if ( function_exists('get_field') ) {
					echo get_field('homepage_testimonials_heading') ?: 'Témoignages';
				} else {
					echo 'Témoignages';
				}
				?>
			</h2>
			<div class="nf-testimonials__quote nf-animate-on-scroll nf-fade-in nf-animate-delay-1">
				<span class="nf-testimonials__quote-mark">&ldquo;</span>
				<?php 
				if ( function_exists('have_rows') && have_rows('homepage_testimonials') ) :
					while ( have_rows('homepage_testimonials') ) : the_row(); ?>
						<blockquote class="nf-testimonial">
							<p><?php echo esc_html( get_sub_field('testimonial_text') ); ?></p>
							<cite><?php echo esc_html( get_sub_field('testimonial_author') ); ?></cite>
						</blockquote>
					<?php endwhile;
				else : ?>
					<blockquote class="nf-testimonial">
						<p>J'ai contacté Florence afin de mieux comprendre quelle est l'alimentation qui me correspondrait le mieux, et adopter des habitudes saines sur le long terme. Son écoute attentive et son accompagnement personnalisé m'ont beaucoup appris, et le défi à été réussi. Je la recommande vivement.</p>
						<cite>Nina Rozenberg</cite>
					</blockquote>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
