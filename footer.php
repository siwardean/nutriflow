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
		const elements = document.querySelectorAll('.nf-animate-on-scroll:not(.nf-testimonials__slide)');
		
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
	
	// Carousel de témoignages - Simple et propre
	function initTestimonialsSlider() {
		const slider = document.querySelector('.nf-testimonials__slider');
		if (!slider) return;
		
		const slides = slider.querySelectorAll('.nf-testimonials__slide');
		if (slides.length === 0) return;
		
		const prevBtn = document.querySelector('.nf-testimonials__prev');
		const nextBtn = document.querySelector('.nf-testimonials__next');
		
		if (slides.length === 1) {
			slides[0].classList.add('active');
			if (prevBtn) prevBtn.style.display = 'none';
			if (nextBtn) nextBtn.style.display = 'none';
			return;
		}
		
		let currentIndex = 0;
		let timer = null;
		
		function moveToSlide(index, direction) {
			if (index < 0) index = slides.length - 1;
			if (index >= slides.length) index = 0;
			if (index === currentIndex) return;
			
			// Si direction n'est pas spécifiée, utiliser 'forward' par défaut
			if (!direction) direction = 'forward';
			
			const oldSlide = slides[currentIndex];
			const newSlide = slides[index];
			
			// Déterminer les positions selon la direction
			const isBackward = direction === 'backward';
			
			// Pour backward (flèche gauche) :
			// - oldSlide sort par la droite (translateX(100%))
			// - newSlide entre depuis la gauche (commence à translateX(-100%), puis va à 0)
			// Pour forward (flèche droite) :
			// - oldSlide sort par la gauche (translateX(-100%))
			// - newSlide entre depuis la droite (commence à translateX(100%), puis va à 0)
			
			const newSlideStart = isBackward ? '-100%' : '100%';
			const oldSlideEnd = isBackward ? '100%' : '-100%';
			
			// Nettoyer complètement le nouveau slide
			newSlide.classList.remove('active');
			newSlide.classList.remove('exiting');
			
			// Désactiver temporairement la transition pour positionner le slide
			newSlide.style.transition = 'none';
			
			// Positionner le nouveau slide hors écran à la position initiale
			newSlide.style.position = 'absolute';
			newSlide.style.top = '0';
			newSlide.style.left = '0';
			newSlide.style.width = '100%';
			newSlide.style.transform = 'translateX(' + newSlideStart + ')';
			newSlide.style.opacity = '0';
			newSlide.style.display = 'block';
			
			// Forcer le navigateur à appliquer les styles immédiatement
			newSlide.offsetHeight;
			
			// Réactiver la transition et démarrer l'animation
			requestAnimationFrame(function() {
				// Réactiver la transition
				newSlide.style.transition = 'transform 0.5s ease-in-out, opacity 0.5s ease-in-out';
				
				// Forcer un nouveau reflow
				newSlide.offsetHeight;
				
				// Dans le frame suivant, démarrer l'animation
				requestAnimationFrame(function() {
					// Faire sortir l'ancien slide
					oldSlide.classList.remove('active');
					oldSlide.style.position = 'absolute';
					oldSlide.style.transition = 'transform 0.5s ease-in-out, opacity 0.5s ease-in-out';
					oldSlide.style.transform = 'translateX(' + oldSlideEnd + ')';
					oldSlide.style.opacity = '0';
					oldSlide.classList.add('exiting');
					
					// Faire entrer le nouveau slide - IL GLISSE DE GAUCHE VERS DROITE
					newSlide.classList.add('active');
					newSlide.style.transform = 'translateX(0)';
					newSlide.style.opacity = '1';
				});
			});
			
			// Nettoyer après l'animation
			setTimeout(function() {
				oldSlide.classList.remove('exiting');
				oldSlide.style.transform = '';
				oldSlide.style.opacity = '';
				oldSlide.style.display = '';
				oldSlide.style.position = '';
				oldSlide.style.top = '';
				oldSlide.style.left = '';
				oldSlide.style.width = '';
				oldSlide.style.transition = '';
				
				// Après l'animation, changer en relative pour le nouveau slide actif
				if (newSlide.classList.contains('active')) {
					newSlide.style.position = 'relative';
					newSlide.style.top = '';
					newSlide.style.left = '';
					newSlide.style.width = '';
					newSlide.style.transform = '';
				}
				newSlide.style.opacity = '';
				newSlide.style.transition = '';
			}, 500);
			
			currentIndex = index;
		}
		
		function startTimer() {
			clearInterval(timer);
			timer = setInterval(function() {
				moveToSlide(currentIndex + 1, 'forward');
			}, 5000);
		}
		
		if (prevBtn) {
			prevBtn.onclick = function() {
				clearInterval(timer);
				moveToSlide(currentIndex - 1, 'backward');
				startTimer();
			};
		}
		
		if (nextBtn) {
			nextBtn.onclick = function() {
				clearInterval(timer);
				moveToSlide(currentIndex + 1, 'forward');
				startTimer();
			};
		}
		
		// Initialiser le premier slide
		if (slides.length > 0) {
			slides[0].classList.add('active');
			slides[0].style.position = 'relative';
			slides[0].style.transform = 'translateX(0)';
			slides[0].style.opacity = '1';
		}
		
		startTimer();
	}
	
	// Initialize slider when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initTestimonialsSlider);
	} else {
		initTestimonialsSlider();
	}
})();
</script>

</body>
</html>
