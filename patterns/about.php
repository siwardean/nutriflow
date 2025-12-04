<?php
/**
 * Title: About Section (Qui suis-je)
 * Slug: nutriflow/about
 * Categories: nutriflow
 * Description: About section with image and text
 */
?>
<!-- wp:group {"className":"nf-about","backgroundColor":"dark-blue","layout":{"type":"constrained"}} -->
<div class="wp-block-group nf-about has-dark-blue-background-color has-background">
	<!-- wp:columns {"className":"nf-about__wrapper"} -->
	<div class="wp-block-columns nf-about__wrapper">
		<!-- wp:column {"className":"nf-about__image-col"} -->
		<div class="wp-block-column nf-about__image-col">
			<!-- wp:image {"className":"nf-about__image"} -->
			<figure class="wp-block-image nf-about__image"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/about/pool-woman.jpg" alt="Woman by pool"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"className":"nf-about__content"} -->
		<div class="wp-block-column nf-about__content">
			<!-- wp:heading {"className":"nf-about__title","textColor":"cream"} -->
			<h2 class="wp-block-heading nf-about__title has-cream-color has-text-color">Qui suis-je ?</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"nf-about__text--left","textColor":"cream"} -->
			<p class="nf-about__text--left has-cream-color has-text-color">Je suis Florence, <strong>nutrithérapeute passionnée</strong> par l'impact de la nutrition sur notre santé globale. Mon <strong>approche</strong> est douce, basée sur la <strong>science</strong> et centrée sur l'écoute du corps.</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"nf-about__text--right","textColor":"cream"} -->
			<p class="nf-about__text--right has-cream-color has-text-color">La <strong>nutrithérapie</strong> est, selon moi, un outil de <strong>prévention</strong>, mais aussi de transformation et d'autonomie : elle <strong>reconnecte</strong> à soi, à ses <strong>besoins</strong> et à son pouvoir d'agir.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->

