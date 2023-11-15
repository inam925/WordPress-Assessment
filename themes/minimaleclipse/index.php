<?php get_header(); ?>
	<main>
		<div class="min-ec-landing-page">
			<div class="">
				<h1><?php echo esc_html__('Hello, I\'m Inam Ul Haq', 'min_ec_textdomain') ?></h1>
				<h2><?php echo esc_html__('A web developer currently specializing in the WordPress ecosystem, with expertise in crafting custom plugins and themes.', 'min_ec_textdomain') ?></h2>
				<h2>
					<a target="_blank" href='https://github.com/inam925'><?php echo esc_html__('Github ', 'min_ec_textdomain') ?> </a>/
					<a target="_blank" href='https://www.linkedin.com/in/inam-ul-haq-a69200272/'><?php echo esc_html__('LinkedIn ', 'min_ec_textdomain') ?> </a>
					<a target="_blank" href="mailto:inam925@gmail.com"><?php echo esc_html__('Email ', 'min_ec_textdomain') ?> </a>
				</h2>
			</div>
			<div class="is-gravatar">
				<?php 
				$admin_email = get_option('admin_email');
				echo get_avatar( $admin_email, 100 ); 
				?>
			</div>
		</div>
		<br><hr>
		<div class="min-ec-columns">
			<div class="min-ec-blog">
				<h3>Blog</h3>
				<ul>
				<?php 
				if ( is_home() || is_archive() ) {
					if ( have_posts() ) :
						while ( have_posts() ) :
							the_post(); 
							?>
					<li>
						<a href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a>
						<span style="color:grey;"> -  <?php echo get_the_date(); ?></span>
					</li>
					<?php 
						endwhile;
				endif; } 
				?>
				</ul>
			</div>

			<?php if ( is_active_sidebar( 'min-ec-global-sidebar' ) ) : ?>
			<div id="secondary" class="min-ec-widget" role="complementary">
				<?php dynamic_sidebar( 'min-ec-global-sidebar' ); ?>
			</div>
			<?php endif; ?>
		</div>

		<br><hr>
		<?php if ( is_active_sidebar( 'min-ec-global-footer' ) ) : ?>
		<div id="secondary" class="min-ec-footer-links" role="complementary">
			<?php dynamic_sidebar( 'min-ec-global-footer' ); ?>
		</div>
		<?php endif; ?>
	</main>
<?php get_footer(); ?>
