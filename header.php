<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Nutriflow
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>

	<!-- Google Tag Manager GOES HERE -->

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Montserrat:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

	<?php wp_head(); ?>

	<!-- favicon -->
	<link rel="icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/nutriflow-favicon.png">
	<link rel="apple-touch-icon" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/nutriflow-favicon.png">
</head>

<body <?php body_class(); ?>>

<!-- Google Tag Manager (noscript) GOES HERE -->

<?php wp_body_open(); ?>

<div id="page" class="site">
	<header id="masthead" class="site-header">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'nutriflow' ); ?></a>

		<div class="header-container">
			<nav id="site-navigation" aria-label="Primary">
				<div class="site-logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/logo-nutriflow-blue-navy.png" alt="<?php bloginfo( 'name' ); ?>" />
					</a>
				</div>
				<ul id="primary-menu">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php if ( is_front_page() ) echo ' class="current-page"'; ?>>Nutriflow.florence</a></li>
					<li><a href="<?php echo esc_url( home_url( '/accompagnement/' ) ); ?>"<?php if ( is_page_template( 'page-accompagnement.php' ) ) echo ' class="current-page"'; ?>>Accompagnement</a></li>
					<li><a href="<?php echo esc_url( home_url( '/a-propos/' ) ); ?>"<?php if ( is_page_template( 'page-a-propos.php' ) ) echo ' class="current-page"'; ?>>A propos</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"<?php if ( is_page_template( 'page-contact.php' ) ) echo ' class="current-page"'; ?>>Contact</a></li>
				</ul>
			</nav><!-- #site-navigation -->
		</div>
	</header><!-- #masthead -->
