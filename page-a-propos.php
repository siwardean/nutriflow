<?php
/**
 * Template Name: A propos
 * Template for the A propos (About) page
 *
 * @package Nutriflow
 */

get_header();

// Check if page has custom content from block editor
$has_content = get_the_content() && trim( get_the_content() ) !== '';
?>

<main id="primary" class="site-main nf-apropos-page">

	<?php if ( $has_content ) : ?>
		<?php
		// Display block editor content
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	<?php else : ?>
		<!-- Qui suis-je Section -->
		<section class="nf-apropos-intro">
			<div class="nf-apropos-intro__wrapper">
				<div class="nf-apropos-intro__left">
					<h1 class="nf-apropos-intro__title nf-animate-on-scroll nf-fade-in">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('intro_title') ?: 'Qui suis-je ?';
						} else {
							echo 'Qui suis-je ?';
						}
						?>
					</h1>
				</div>
				<div class="nf-apropos-intro__right">
					<p class="nf-apropos-intro__text nf-animate-on-scroll nf-fade-in nf-animate-delay-1">
						<?php 
						if ( function_exists('get_field') && get_field('intro_text') ) {
							echo get_field('intro_text');
						} else {
							echo 'Je suis Florence, nutrithérapeute passionnée par l\'impact de la nutrition sur notre <strong>santé globale.</strong> Mon <strong>approche</strong> est douce, basée sur la science et centrée sur l\'<strong>écoute du corps.</strong>';
						}
						?>
					</p>
				</div>
			</div>
			<div class="nf-apropos-intro__circles">
				<span class="nf-apropos-intro__circle nf-apropos-intro__circle--green"></span>
				<span class="nf-apropos-intro__circle nf-apropos-intro__circle--blue"></span>
			</div>
		</section>

		<!-- Image Gallery -->
		<section class="nf-apropos-gallery">
			<div class="nf-apropos-gallery__grid">
				<?php 
				$gallery = function_exists('get_field') ? get_field('gallery_images') : false;
				if ( $gallery ) :
					$index = 0;
					foreach ( $gallery as $image ) : 
						$index++;
						$delay = 'nf-animate-delay-' . ($index % 4 + 1);
					?>
						<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="nf-animate-on-scroll nf-slide-in-up <?php echo esc_attr( $delay ); ?>" />
					<?php endforeach;
				else : ?>
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/about/gallery-1.jpg" alt="Préparation culinaire" class="nf-animate-on-scroll nf-slide-in-up nf-animate-delay-1" />
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/about/gallery-2.jpg" alt="Préparation culinaire" class="nf-animate-on-scroll nf-slide-in-up nf-animate-delay-2" />
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/about/gallery-3.jpg" alt="Préparation culinaire" class="nf-animate-on-scroll nf-slide-in-up nf-animate-delay-3" />
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/about/gallery-4.jpg" alt="Préparation culinaire" class="nf-animate-on-scroll nf-slide-in-up nf-animate-delay-4" />
				<?php endif; ?>
			</div>
		</section>

		<!-- Mon Parcours Section -->
		<section class="nf-apropos-story">
			<div class="nf-apropos-story__wrapper">
				<div class="nf-apropos-story__content nf-animate-on-scroll nf-fade-in">
					<?php 
					$story_content = function_exists('get_field') ? get_field('story_content') : false;
					if ( $story_content ) {
						echo wp_kses_post( $story_content );
					} else {
						echo '<p>Mon corps a été mon <strong>premier guide.</strong> Dès l\'adolescence, j\'ai été confrontée à des <strong>déséquilibres</strong> (eczéma, douleurs <strong>chroniques</strong>, troubles <strong>digestifs</strong>, acné) que la médecine moderne n\'expliquait pas. En cherchant à comprendre les <strong>causes</strong> de mes maux et à trouver des solutions, j\'ai découvert la <strong>puissance</strong> de la <strong>nutrition</strong> dans mon processus de guérison. Je me suis donc formée en nutrithérapie pendant plusieurs années.</p>';
						echo '<p>Aujourd\'hui, je me sens alignée, ancrée, et <strong>connectée</strong> à mes rythmes.</p>';
						echo '<p>Ce chemin personnel m\'a donné des <strong>clés précieuses</strong>, que je transmets avec nuance et bienveillance. J\'aide les personnes à se <strong>reconnecter</strong> à leur corps, à comprendre leurs <strong>symptômes</strong> et à retrouver leur <strong>vitalité</strong> via leur alimentation, en écoutant ce que leur corps exprime.</p>';
					}
					?>
				</div>
				
				<div class="nf-apropos-formations nf-animate-on-scroll nf-fade-in nf-animate-delay-1">
					<h2 class="nf-apropos-formations__title">
						<?php 
						if ( function_exists('get_field') ) {
							echo get_field('formations_title') ?: 'Mes formations';
						} else {
							echo 'Mes formations';
						}
						?>
					</h2>
					<ul class="nf-apropos-formations__list">
						<?php 
						if ( function_exists('have_rows') && have_rows('formations_list') ) :
							while ( have_rows('formations_list') ) : the_row(); ?>
								<li><?php echo wp_kses_post( get_sub_field('formation_item') ); ?></li>
							<?php endwhile;
						else : ?>
							<li><strong>CFNA</strong> (2022-2024) : Conseiller en nutrithérapie</li>
							<li><strong>Oreka Formation</strong> (2025) : Nutrition et complémentation du sportif</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</section>

		<!-- Le Sport Section -->
		<section class="nf-apropos-sport">
			<div class="nf-apropos-sport__wrapper">
				<h2 class="nf-apropos-sport__title nf-animate-on-scroll nf-fade-in">
					<?php 
					if ( function_exists('get_field') ) {
						echo get_field('sport_title') ?: 'Le sport comme source de bien-être';
					} else {
						echo 'Le sport comme source de bien-être';
					}
					?>
				</h2>
				<div class="nf-apropos-sport__content nf-animate-on-scroll nf-fade-in nf-animate-delay-1">
					<?php 
					$sport_content = function_exists('get_field') ? get_field('sport_content') : false;
					if ( $sport_content ) {
						echo wp_kses_post( $sport_content );
					} else {
						echo '<p>Je fais du sport depuis toute petite : <strong>danse</strong>, tennis, <strong>natation</strong>,... Puis jeune adulte je mords très vite à la <strong>course à pied</strong> dont je ne peux aujourd\'hui plus me passer, mais je découvre également le <strong>yoga</strong>, le <strong>vélo</strong> et bien d\'autres activités sportives. J\'obtiens mon <strong>Yoga Teacher Training Certificate</strong> en 2023 au Portugal lors d\'une pause professionnelle.</p>';
						echo '<p>En 2025, je deviens <strong>triathlète</strong> avec mon tout premier triathlon olympique.</p>';
						echo '<p>La pratique sportive est pour moi la recherche d\'un <strong>bien-être général</strong> de mon corps et la <strong>recherche de l\'équilibre</strong>.</p>';
					}
					?>
				</div>
			</div>
		</section>

		<!-- Blue Bar -->
		<section class="nf-apropos-bar"></section>
	<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
