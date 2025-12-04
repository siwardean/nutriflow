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
					<h1 class="nf-apropos-intro__title">Qui suis-je ?</h1>
				</div>
				<div class="nf-apropos-intro__right">
					<p class="nf-apropos-intro__text">Je suis Florence, nutrithérapeute passionnée par l'impact de la nutrition sur notre <strong>santé globale.</strong> Mon <strong>approche</strong> est douce, basée sur la science et centrée sur l'<strong>écoute du corps.</strong></p>
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
				<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/about/gallery-1.jpg" alt="Préparation culinaire" />
				<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/about/gallery-2.jpg" alt="Préparation culinaire" />
				<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/about/gallery-3.jpg" alt="Préparation culinaire" />
				<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/about/gallery-4.jpg" alt="Préparation culinaire" />
			</div>
		</section>

		<!-- Mon Parcours Section -->
		<section class="nf-apropos-story">
			<div class="nf-apropos-story__wrapper">
				<div class="nf-apropos-story__content">
					<p>Mon corps a été mon <strong>premier guide.</strong> Dès l'adolescence, j'ai été confrontée à des <strong>déséquilibres</strong> (eczéma, douleurs <strong>chroniques</strong>, troubles <strong>digestifs</strong>, acné) que la médecine moderne n'expliquait pas. En cherchant à comprendre les <strong>causes</strong> de mes maux et à trouver des solutions, j'ai découvert la <strong>puissance</strong> de la <strong>nutrition</strong> dans mon processus de guérison. Je me suis donc formée en nutrithérapie pendant plusieurs années.</p>
					
					<p>Aujourd'hui, je me sens alignée, ancrée, et <strong>connectée</strong> à mes rythmes.</p>
					
					<p>Ce chemin personnel m'a donné des <strong>clés précieuses</strong>, que je transmets avec nuance et bienveillance. J'aide les personnes à se <strong>reconnecter</strong> à leur corps, à comprendre leurs <strong>symptômes</strong> et à retrouver leur <strong>vitalité</strong> via leur alimentation, en écoutant ce que leur corps exprime.</p>
				</div>
				
				<div class="nf-apropos-formations">
					<h2 class="nf-apropos-formations__title">Mes formations</h2>
					<ul class="nf-apropos-formations__list">
						<li><strong>CFNA</strong> (2022-2024) : Conseiller en nutrithérapie</li>
						<li><strong>Oreka Formation</strong> (2025) : Nutrition et complémentation du sportif</li>
					</ul>
				</div>
			</div>
		</section>

		<!-- Le Sport Section -->
		<section class="nf-apropos-sport">
			<div class="nf-apropos-sport__wrapper">
				<h2 class="nf-apropos-sport__title">Le sport comme source de bien-être</h2>
				<div class="nf-apropos-sport__content">
					<p>Je fais du sport depuis toute petite : <strong>danse</strong>, tennis, <strong>natation</strong>,... Puis jeune adulte je mords très vite à la <strong>course à pied</strong> dont je ne peux aujourd'hui plus me passer, mais je découvre également le <strong>yoga</strong>, le <strong>vélo</strong> et bien d'autres activités sportives. J'obtiens mon <strong>Yoga Teacher Training Certificate</strong> en 2023 au Portugal lors d'une pause professionnelle.</p>
					
					<p>En 2025, je deviens <strong>triathlète</strong> avec mon tout premier triathlon olympique.</p>
					
					<p>La pratique sportive est pour moi la recherche d'un <strong>bien-être général</strong> de mon corps et la <strong>recherche de l'équilibre</strong>.</p>
				</div>
			</div>
		</section>

		<!-- Blue Bar -->
		<section class="nf-apropos-bar"></section>
	<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
