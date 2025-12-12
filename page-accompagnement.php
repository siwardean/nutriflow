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
		<?php 
		$hero_bg = function_exists('get_field') ? get_field('hero_background') : false;
		$hero_bg_url = $hero_bg ? $hero_bg['url'] : get_template_directory_uri() . '/assets/images/services/peppers-bg.jpg';
		?>
		<section class="nf-accomp-hero" style="background-image: url('<?php echo esc_url( $hero_bg_url ); ?>');">
			<h1 class="nf-accomp-hero__title nf-animate-on-scroll nf-fade-in">
				<?php 
				if ( function_exists('get_field') ) {
					echo get_field('hero_title') ?: 'Je t\'accompagne pour';
				} else {
					echo 'Je t\'accompagne pour';
				}
				?>
			</h1>
			<div class="nf-accomp-hero__grid">
				<?php 
				// Carte 1
				$card_1_title = function_exists('get_field') ? get_field('hero_card_1_title') : false;
				$card_1_content = function_exists('get_field') ? get_field('hero_card_1_content') : false;
				if ( $card_1_title || $card_1_content ) : ?>
					<article class="nf-accomp-card nf-accomp-card--filled nf-animate-on-scroll nf-slide-in-up nf-animate-delay-1">
						<?php if ( $card_1_title ) : ?>
							<h3 class="nf-accomp-card__title"><?php echo esc_html( $card_1_title ); ?></h3>
						<?php endif; ?>
						<?php if ( $card_1_content ) : ?>
							<div class="nf-accomp-card__description"><?php echo wp_kses_post( $card_1_content ); ?></div>
						<?php endif; ?>
					</article>
				<?php else : ?>
					<article class="nf-accomp-card nf-accomp-card--filled nf-animate-on-scroll nf-slide-in-up nf-animate-delay-1">
						<h3 class="nf-accomp-card__title">Rééquilibrage alimentaire</h3>
						<p class="nf-accomp-card__description">Rééquilibrer ton alimentation et/ou perdre du poids.</p>
					</article>
				<?php endif; ?>
				
				<?php 
				// Carte 2
				$card_2_title = function_exists('get_field') ? get_field('hero_card_2_title') : false;
				$card_2_content = function_exists('get_field') ? get_field('hero_card_2_content') : false;
				if ( $card_2_title || $card_2_content ) : ?>
					<article class="nf-accomp-card nf-accomp-card--filled nf-animate-on-scroll nf-slide-in-up nf-animate-delay-2">
						<?php if ( $card_2_title ) : ?>
							<h3 class="nf-accomp-card__title"><?php echo esc_html( $card_2_title ); ?></h3>
						<?php endif; ?>
						<?php if ( $card_2_content ) : ?>
							<div class="nf-accomp-card__description"><?php echo wp_kses_post( $card_2_content ); ?></div>
						<?php endif; ?>
					</article>
				<?php else : ?>
					<article class="nf-accomp-card nf-accomp-card--filled nf-animate-on-scroll nf-slide-in-up nf-animate-delay-2">
						<h3 class="nf-accomp-card__title">Troubles hormonaux</h3>
						<p class="nf-accomp-card__description">Soulager tes simptômes et rééquilibrer les troubles hormonaux : SPM, endométriose, SOPK,...</p>
					</article>
				<?php endif; ?>
				
				<?php 
				// Carte 3
				$card_3_title = function_exists('get_field') ? get_field('hero_card_3_title') : false;
				$card_3_content = function_exists('get_field') ? get_field('hero_card_3_content') : false;
				if ( $card_3_title || $card_3_content ) : ?>
					<article class="nf-accomp-card nf-accomp-card--filled nf-animate-on-scroll nf-slide-in-up nf-animate-delay-3">
						<?php if ( $card_3_title ) : ?>
							<h3 class="nf-accomp-card__title"><?php echo esc_html( $card_3_title ); ?></h3>
						<?php endif; ?>
						<?php if ( $card_3_content ) : ?>
							<div class="nf-accomp-card__description"><?php echo wp_kses_post( $card_3_content ); ?></div>
						<?php endif; ?>
					</article>
				<?php else : ?>
					<article class="nf-accomp-card nf-accomp-card--filled nf-animate-on-scroll nf-slide-in-up nf-animate-delay-3">
						<h3 class="nf-accomp-card__title">Nutrition & performances sportif·ves</h3>
						<p class="nf-accomp-card__description">Améliorer tes performances et mieux comprendre l'impact de ton alimentation sur tes résultats.</p>
					</article>
				<?php endif; ?>
				
				<?php 
				// Carte 4
				$card_4_title = function_exists('get_field') ? get_field('hero_card_4_title') : false;
				$card_4_content = function_exists('get_field') ? get_field('hero_card_4_content') : false;
				if ( $card_4_title || $card_4_content ) : ?>
					<article class="nf-accomp-card nf-accomp-card--filled nf-animate-on-scroll nf-slide-in-up nf-animate-delay-4">
						<?php if ( $card_4_title ) : ?>
							<h3 class="nf-accomp-card__title"><?php echo esc_html( $card_4_title ); ?></h3>
						<?php endif; ?>
						<?php if ( $card_4_content ) : ?>
							<div class="nf-accomp-card__description"><?php echo wp_kses_post( $card_4_content ); ?></div>
						<?php endif; ?>
					</article>
				<?php else : ?>
					<article class="nf-accomp-card nf-accomp-card--filled nf-animate-on-scroll nf-slide-in-up nf-animate-delay-4">
						<h3 class="nf-accomp-card__title">Troubles digestifs</h3>
						<p class="nf-accomp-card__description">Prendre soin de ton microbiote mais également pour soulager les troubles digestifs chroniques (SII, MICI, SIBO,..).</p>
					</article>
				<?php endif; ?>
			</div>
		</section>

		<!-- Comment Section - Pricing -->
		<section class="nf-pricing">
			<h2 class="nf-pricing__title nf-animate-on-scroll nf-fade-in">
				<?php 
				if ( function_exists('get_field') ) {
					echo get_field('pricing_title') ?: 'Comment ?';
				} else {
					echo 'Comment ?';
				}
				?>
			</h2>
			<div class="nf-pricing__grid">
				<?php 
				// Vérifier d'abord si les nouveaux champs ACF Free existent
				$card_1_title = function_exists('get_field') ? get_field('pricing_card_1_title') : false;
				
				if ( $card_1_title || function_exists('have_rows') && have_rows('pricing_cards') ) :
					// Nouveau système avec champs fixes (ACF Free)
					if ( $card_1_title ) {
						$pricing_cards = array();
						for ( $i = 1; $i <= 3; $i++ ) {
							$title = get_field( "pricing_card_{$i}_title" );
							$price = get_field( "pricing_card_{$i}_price" );
							$items = get_field( "pricing_card_{$i}_items" );
							
							if ( $title ) {
								$pricing_cards[] = array(
									'title' => $title,
									'price' => $price,
									'items' => $items,
								);
							}
						}
						
						$total_rows = count( $pricing_cards );
						foreach ( $pricing_cards as $index => $card ) :
							$card_index = $index + 1;
							$delay = 'nf-animate-delay-' . ($card_index % 3 + 1);
							$flip_class = ($total_rows > 1 && $card_index > ($total_rows - 2)) ? 'nf-pricing-card--flip-reverse' : '';
						?>
							<article class="nf-pricing-card nf-animate-on-scroll nf-slide-in-up <?php echo esc_attr( $delay ); ?> <?php echo esc_attr( $flip_class ); ?>">
								<h3 class="nf-pricing-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
								<p class="nf-pricing-card__price"><?php echo wp_kses_post( $card['price'] ); ?></p>
								<?php if ( $card['items'] ) : 
									$items_content = $card['items'];
									// Si le contenu contient déjà des <ul> ou <li>, on l'utilise tel quel
									if ( strpos( $items_content, '<ul' ) !== false || strpos( $items_content, '<li' ) !== false ) {
										// Extraire juste le contenu de la liste si elle est wrappée
										if ( preg_match( '/<ul[^>]*>(.*?)<\/ul>/is', $items_content, $matches ) ) {
											echo '<ul class="nf-pricing-card__list">' . wp_kses_post( $matches[1] ) . '</ul>';
										} else {
											echo wp_kses_post( $items_content );
										}
									} else {
										// Sinon, créer une liste à partir du contenu
										echo '<ul class="nf-pricing-card__list">';
										// Diviser par les balises <p> ou les sauts de ligne
										$items_array = preg_split( '/<p[^>]*>|<\/p>|\r\n|\r|\n/', $items_content );
										foreach ( $items_array as $item ) {
											$item = trim( strip_tags( $item, '<strong><em><a><del>' ) );
											if ( ! empty( $item ) ) {
												echo '<li>' . wp_kses_post( $item ) . '</li>';
											}
										}
										echo '</ul>';
									}
								endif; ?>
							</article>
						<?php endforeach;
					} else {
						// Ancien système avec repeaters (ACF Pro)
						$pricing_cards = get_field('pricing_cards');
						$total_rows = $pricing_cards ? count( $pricing_cards ) : 0;
						$index = 0;
						while ( have_rows('pricing_cards') ) : the_row(); 
							$index++;
							$delay = 'nf-animate-delay-' . ($index % 3 + 1);
							$flip_class = ($total_rows > 1 && $index > ($total_rows - 2)) ? 'nf-pricing-card--flip-reverse' : '';
						?>
							<article class="nf-pricing-card nf-animate-on-scroll nf-slide-in-up <?php echo esc_attr( $delay ); ?> <?php echo esc_attr( $flip_class ); ?>">
								<h3 class="nf-pricing-card__title"><?php echo esc_html( get_sub_field('pricing_card_title') ); ?></h3>
								<p class="nf-pricing-card__price"><?php echo wp_kses_post( get_sub_field('pricing_card_price') ); ?></p>
								<ul class="nf-pricing-card__list">
									<?php 
									if ( have_rows('pricing_card_items') ) :
										while ( have_rows('pricing_card_items') ) : the_row(); ?>
											<li><?php echo wp_kses_post( get_sub_field('pricing_item') ); ?></li>
										<?php endwhile;
									endif; ?>
								</ul>
							</article>
						<?php endwhile;
					}
				else : ?>
					<article class="nf-pricing-card nf-animate-on-scroll nf-slide-in-up nf-animate-delay-1">
						<h3 class="nf-pricing-card__title">Première consultation de +- 1h15</h3>
						<p class="nf-pricing-card__price">- 70 euros -</p>
						<ul class="nf-pricing-card__list">
							<li><strong>Questionnaire</strong> préparatoire</li>
							<li><strong>Analyse</strong> des 3 piliers : alimentation, hygiène de vie et supplémentation</li>
							<li>Etablissement des <strong>objectifs</strong></li>
							<li><strong>Premier bilan</strong> nutritionnel et conseils adaptés</li>
						</ul>
					</article>
					<article class="nf-pricing-card nf-animate-on-scroll nf-slide-in-up nf-animate-delay-2 nf-pricing-card--flip-reverse">
						<h3 class="nf-pricing-card__title">Consultation de suivi de 30 à 45 minutes</h3>
						<p class="nf-pricing-card__price">- 40 euros -</p>
						<ul class="nf-pricing-card__list">
							<li>Premier retour d'expérience et <strong>questions</strong> - réponses</li>
							<li>Evaluation des <strong>résultats et adaptation</strong> de l'accompagnement si nécessaire</li>
						</ul>
					</article>
					<article class="nf-pricing-card nf-animate-on-scroll nf-slide-in-up nf-animate-delay-3 nf-pricing-card--flip-reverse">
						<h3 class="nf-pricing-card__title">Pack 'Accompagnement sur 3 mois'</h3>
						<p class="nf-pricing-card__price"><del>-190</del> 175 euros -</p>
						<ul class="nf-pricing-card__list">
							<li>Total de <strong>4 consultations</strong></li>
							<li>Pour un <strong>changement ancré</strong> sur du long terme</li>
							<li>Payable en plusieurs fois</li>
							<li>Echanges sur whatsapp entre les consultations</li>
						</ul>
					</article>
				<?php endif; ?>
			</div>
		</section>

		<!-- Nutrition du Sportif Section -->
		<section class="nf-sportif">
			<h2 class="nf-sportif__title nf-animate-on-scroll nf-fade-in">
				<?php 
				if ( function_exists('get_field') ) {
					echo get_field('sportif_title') ?: 'Tu souhaites un accompagnement spécial " Nutrition du sportif·ve " ?';
				} else {
					echo 'Tu souhaites un accompagnement spécial " Nutrition du sportif·ve " ?';
				}
				?>
			</h2>
			<div class="nf-sportif__wrapper">
				<article class="nf-sportif__card nf-animate-on-scroll nf-fade-in nf-animate-delay-1">
					<h3 class="nf-sportif__card-title">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('sportif_card_title') ?: 'Accompagnement sur mesure';
						} else {
							echo 'Accompagnement sur mesure';
						}
						?>
					</h3>
					<?php 
					// Contenu Sportif (fusionné : contenu + liste)
					$sportif_content = function_exists('nutriflow_get_field') ? nutriflow_get_field('sportif_content') : ( function_exists('get_field') ? get_field('sportif_content') : false );
					if ( $sportif_content ) : ?>
						<div class="nf-sportif__content">
							<?php echo wp_kses_post( $sportif_content ); ?>
						</div>
					<?php else : ?>
						<div class="nf-sportif__content">
							<p>Pour t'aider à y voir plus clair dans les <strong>besoins spécifiques</strong> d'un·e sportif·ve et adapter ton alimentation.</p>
							<ul class="nf-sportif__list">
								<li>Pour comprendre l'impact de l'alimentation sur tes performances</li>
								<li>Pour optimiser ton alimentation avant, pendant et après l'effort</li>
								<li>Pour gérer ton poids de façon adaptée à ta pratique</li>
								<li>Pour optimiser ta récupération</li>
							</ul>
						</div>
					<?php endif; ?>
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
					<h2 class="nf-location__title nf-animate-on-scroll nf-fade-in">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('location_title') ?: 'Où se passent mes consultations ?';
						} else {
							echo 'Où se passent mes consultations ?';
						}
						?>
					</h2>
					<div class="nf-location__info nf-animate-on-scroll nf-fade-in nf-animate-delay-1">
						<?php 
						$location_info = function_exists('get_field') ? get_field('location_info') : false;
						if ( $location_info ) {
							echo wp_kses_post( $location_info );
						} else {
							echo '<p>A <strong>Ixelles</strong> ou en visio</p>';
							echo '<p>Le <strong>vendredi</strong> de 8h à 18h30 et le <strong>samedi</strong> de 10h à 18h30</p>';
							echo '<p><a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a></p>';
							echo '<p><a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a></p>';
						}
						?>
					</div>
					<div class="nf-location__social nf-animate-on-scroll nf-fade-in nf-animate-delay-2">
						<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" aria-label="Instagram">
							<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
						</a>
						<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" aria-label="LinkedIn">
							<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
						</a>
					</div>
				</div>
				<div class="nf-location__image">
					<?php 
					$location_image = function_exists('get_field') ? get_field('location_image') : false;
					if ( $location_image ) : ?>
						<img src="<?php echo esc_url( $location_image['url'] ); ?>" alt="<?php echo esc_attr( $location_image['alt'] ); ?>" class="nf-animate-on-scroll nf-slide-in-right" />
					<?php else : ?>
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/location/cooking.jpg" alt="Préparation culinaire" class="nf-animate-on-scroll nf-slide-in-right" />
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
