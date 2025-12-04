<?php
/**
 * Title: Hero Section
 * Slug: nutriflow/hero
 * Categories: nutriflow
 * Description: Main hero section with title, subtitle, and CTA button
 */
?>
<!-- wp:group {"className":"nf-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group nf-hero">
	<!-- wp:columns {"className":"nf-hero__wrapper"} -->
	<div class="wp-block-columns nf-hero__wrapper">
		<!-- wp:column {"className":"nf-hero__content"} -->
		<div class="wp-block-column nf-hero__content">
			<!-- wp:heading {"level":1,"className":"nf-hero__title"} -->
			<h1 class="wp-block-heading nf-hero__title">NUTRIFLOW</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"nf-hero__subtitle"} -->
			<p class="nf-hero__subtitle">- NUTRITHÉRAPEUTE À BRUXELLES -</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"nf-hero__description"} -->
			<p class="nf-hero__description">Accompagnement en nutrithérapie: rééquilibrage alimentaire, nutrition sportive, troubles hormonaux et digestifs.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"nf-btn nf-btn--primary"} -->
				<div class="wp-block-button nf-btn nf-btn--primary"><a class="wp-block-button__link wp-element-button" href="https://calendly.com/fl-vanhecke/premiere-consultation" target="_blank">PRENDRE RDV</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"className":"nf-hero__media"} -->
		<div class="wp-block-column nf-hero__media">
			<!-- wp:image {"className":"nf-hero__image"} -->
			<figure class="wp-block-image nf-hero__image"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero/hero-kitchen.jpg" alt="Kitchen scene"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->

