<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width" />
	<meta name="google-site-verification" content="fN9pYp6C0mSy-0ChKXnAmCiGYu9tPKpfNUpaUdmPXYo" />
	<title>
		<?php
		if (is_home()) :
			bloginfo('name');
			?>
			|
		<?php
			echo bloginfo('description');
		endif;
		?>
		<?php wp_title('', true); ?>
	</title>
	<?php wp_head(); ?>
</head>

<body>
	<header>
		<nav class="min-ec-nav-container">
			<div>
			<?php
			wp_nav_menu(array(
				'theme_location' => 'min-ec-global-nav', // Use the registered menu location
				'container'      => false, // Remove the default container
				'menu_class'     => 'min-ec-nav-menu', // Add a CSS class for the menu
			));
			?>
			</div>
			<div id="min-ec-select-theme">
			<div onclick="setDarkMode(true)" id="min-ec-dark-button" class=""> 🌚 <span><?php echo esc_html__('Dark', 'min_ec_textdomain') ?></span></div>
			<div onclick="setDarkMode(false)" id="min-ec-light-button" class="min-ec-is-hidden"> 🌝 <span><?php echo esc_html__('Light', 'min_ec_textdomain') ?></span> </div>
			</div>
		</nav>
	</header>