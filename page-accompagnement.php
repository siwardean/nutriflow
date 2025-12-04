<?php
/**
 * Template Name: Accompagnement
 * Template for the Accompagnement page
 *
 * @package Nutriflow
 */

get_header();

// Get Customizer settings
$calendly_url  = nutriflow_get_option( 'calendly_url', 'https://calendly.com/fl-vanhecke/premiere-consultation' );
$instagram_url = nutriflow_get_option( 'instagram_url', 'https://www.instagram.com/nutriflow.florence/' );
$linkedin_url  = nutriflow_get_option( 'linkedin_url', 'https://www.linkedin.com/in/florence-van-hecke-30386712b/' );
$phone         = nutriflow_get_option( 'phone', '+32 486 920 962' );
$email         = nutriflow_get_option( 'email', 'fl.vanhecke@gmail.com' );

// Check if page has custom content from block editor
$has_content = get_the_content() && trim( get_the_content() ) !== '';
?>

<main id="primary" class="site-main nf-accompagnement-page">

	<?php if ( $has_content ) : ?>
		<?php
		// Display block editor content
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	<?php else : ?>
		<!-- Hero Section with Peppers Background -->
		<section class="nf-accomp-hero" style="background-image: url('<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/services/peppers-bg.jpg');">
			<h1 class="nf-accomp-hero__title">Je t'accompagne pour</h1>
			<div class="nf-accomp-hero__grid">
				<article class="nf-accomp-card nf-accomp-card--filled">
					<h3 class="nf-accomp-card__title">Rééquilibrage alimentaire</h3>
					<p class="nf-accomp-card__description">Rééquilibrer ton alimentation et/ou perdre du poids.</p>
				</article>
				<article class="nf-accomp-card nf-accomp-card--filled">
					<h3 class="nf-accomp-card__title">Troubles hormonaux</h3>
					<p class="nf-accomp-card__description">Soulager tes simptômes et rééquilibrer les troubles hormonaux : SPM, endométriose, SOPK,...</p>
				</article>
				<article class="nf-accomp-card nf-accomp-card--filled">
					<h3 class="nf-accomp-card__title">Nutrition & performances sportives</h3>
					<p class="nf-accomp-card__description">Améliorer tes performances et mieux comprendre l'impact de ton alimentation sur tes résultats.</p>
				</article>
				<article class="nf-accomp-card nf-accomp-card--filled">
					<h3 class="nf-accomp-card__title">Troubles digestifs</h3>
					<p class="nf-accomp-card__description">Prendre soin de ton microbiote mais également pour soulager les troubles digestifs chroniques (SII, MICI, SIBO,..).</p>
				</article>
			</div>
		</section>

		<!-- Comment Section - Pricing -->
		<section class="nf-pricing">
			<h2 class="nf-pricing__title">Comment ?</h2>
			<div class="nf-pricing__grid">
				<article class="nf-pricing-card">
					<h3 class="nf-pricing-card__title">Première consultation de +- 1h15</h3>
					<p class="nf-pricing-card__price">- 70 euros -</p>
					<ul class="nf-pricing-card__list">
						<li><strong>Questionnaire</strong> préparatoire</li>
						<li><strong>Analyse</strong> des 3 piliers : alimentation, hygiène de vie et supplémentation</li>
						<li>Etablissement des <strong>objectifs</strong></li>
						<li><strong>Premier bilan</strong> nutritionnel et conseils adaptés</li>
					</ul>
				</article>
				<article class="nf-pricing-card">
					<h3 class="nf-pricing-card__title">Consultation de suivi de 30 à 45 minutes</h3>
					<p class="nf-pricing-card__price">- 40 euros -</p>
					<ul class="nf-pricing-card__list">
						<li>Premier retour d'expérience et <strong>questions</strong> - réponses</li>
						<li>Evaluation des <strong>résultats et adaptation</strong> de l'accompagnement si nécessaire</li>
					</ul>
				</article>
				<article class="nf-pricing-card">
					<h3 class="nf-pricing-card__title">Pack 'Accompagnement sur 3 mois'</h3>
					<p class="nf-pricing-card__price"><del>-190</del> 175 euros -</p>
					<ul class="nf-pricing-card__list">
						<li>Total de <strong>4 consultations</strong></li>
						<li>Pour un <strong>changement ancré</strong> sur du long terme</li>
						<li>Payable en plusieurs fois</li>
						<li>Echanges sur whatsapp entre les consultations</li>
					</ul>
				</article>
			</div>
		</section>

		<!-- Nutrition du Sportif Section -->
		<section class="nf-sportif">
			<h2 class="nf-sportif__title">Tu souhaites un accompagnement spécial " Nutrition du sportif " ?</h2>
			<div class="nf-sportif__wrapper">
				<article class="nf-sportif__card">
					<h3 class="nf-sportif__card-title">Accompagnement sur mesure</h3>
					<ul class="nf-sportif__list">
						<li>Pour t'aider à y voir plus clair dans les <strong>besoins spécifiques</strong> d'un sportif et adapter ton alimentation</li>
						<li>Pour te <strong>préparer à un challenge sportif</strong> ou <strong>compétition</strong></li>
						<li><strong>Bilan nutritionnel</strong> sur mesure</li>
					</ul>
				</article>
				<div class="nf-sportif__circles">
					<span class="nf-sportif__circle nf-sportif__circle--white"></span>
					<span class="nf-sportif__circle nf-sportif__circle--green"></span>
				</div>
			</div>
		</section>

		<!-- Contact/Location Section -->
		<section class="nf-location">
			<div class="nf-location__wrapper">
				<div class="nf-location__content">
					<h2 class="nf-location__title">Où se passent mes consultations ?</h2>
					<div class="nf-location__info">
						<p>A <strong>Ixelles</strong> ou en visio</p>
						<p>Le <strong>vendredi</strong> de 8h à 18h30 et le <strong>samedi</strong> de 10h à 18h30</p>
						<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
						<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
					</div>
					<div class="nf-location__social">
						<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" aria-label="Instagram">
							<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
						</a>
						<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" aria-label="LinkedIn">
							<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
						</a>
					</div>
				</div>
				<div class="nf-location__image">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/location/cooking.jpg" alt="Préparation culinaire" />
				</div>
			</div>
		</section>
	<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
