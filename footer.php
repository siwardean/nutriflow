<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Nutriflow
 */

?>

	<?php
	// Get Customizer settings
	$calendly_url  = nutriflow_get_option( 'calendly_url', 'https://calendly.com/fl-vanhecke/premiere-consultation' );
	$instagram_url = nutriflow_get_option( 'instagram_url', 'https://www.instagram.com/nutriflow.florence/' );
	$linkedin_url  = nutriflow_get_option( 'linkedin_url', 'https://www.linkedin.com/in/florence-van-hecke-30386712b/' );
	$phone         = nutriflow_get_option( 'phone', '+32 486 920 962' );
	$email         = nutriflow_get_option( 'email', 'fl.vanhecke@gmail.com' );
	?>
	<footer class="site-footer">
		<div class="site-info">
			<div class="site-footer__brand">
				<div class="site-branding">
					<span class="site-title">Nutriflow</span>
				</div>
				<div class="nf-footer__contact">
					<p><strong>Florence Van Hecke</strong></p>
					<p>Nutrithérapeute à Flagey, Ixelles</p>
					<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
					<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
				</div>
			</div>

			<nav class="footer-navigation" aria-label="Footer">
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><strong>Accueil</strong></a></li>
					<li><a href="<?php echo esc_url( home_url( '/accompagnement/' ) ); ?>"><strong>Accompagnements</strong></a></li>
					<li><a href="<?php echo esc_url( home_url( '/a-propos/' ) ); ?>"><strong>À propos</strong></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><strong>Contact</strong></a></li>
				</ul>
			</nav>

			<div class="nf-footer__cta">
				<a class="nf-btn nf-btn--footer" href="<?php echo esc_url( $calendly_url ); ?>" target="_blank">PRENDRE RDV</a>
				<div class="nf-footer__social">
					<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" aria-label="Instagram">
						<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
					</a>
					<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" aria-label="LinkedIn">
						<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
					</a>
				</div>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

<script>
(function() {
	'use strict';
	
	// Simple scroll animation handler
	function initScrollAnimations() {
		const elements = document.querySelectorAll('.nf-animate-on-scroll');
		
		if (elements.length === 0) return;
		
		// Add ready class to prepare for animation
		elements.forEach(function(el) {
			el.classList.add('nf-animate-ready');
		});
		
		// Use Intersection Observer if available
		if ('IntersectionObserver' in window) {
			const observer = new IntersectionObserver(function(entries) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('visible');
						observer.unobserve(entry.target);
					}
				});
			}, {
				rootMargin: '0px 0px -50px 0px',
				threshold: 0.1
			});
			
			elements.forEach(function(el) {
				observer.observe(el);
			});
		} else {
			// Fallback: show all elements
			elements.forEach(function(el) {
				el.classList.add('visible');
			});
		}
		
		// Also check elements already in viewport
		setTimeout(function() {
			elements.forEach(function(el) {
				const rect = el.getBoundingClientRect();
				if (rect.top < window.innerHeight && rect.bottom > 0) {
					if (!el.classList.contains('visible')) {
						el.classList.add('visible');
					}
				}
			});
		}, 100);
	}
	
	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initScrollAnimations);
	} else {
		initScrollAnimations();
	}
})();
</script>

</body>
</html>
