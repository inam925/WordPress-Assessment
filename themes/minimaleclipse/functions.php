<?php

/* Theme Support */
add_theme_support('automatic-feed-links');
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
the_post_thumbnail('medium');

function min_ec_register_global_nav()
{
	register_nav_menu('min-ec-global-nav', 'Global Nav Menu');
}
add_action('after_setup_theme', 'min_ec_register_global_nav');

/* Enqueue scripts and styles. */
function min_ec_global_enqueue_scripts()
{
	wp_enqueue_script('jquery');
	wp_enqueue_style('min-ec-global-style', get_template_directory_uri() . '/assets/css/min-ec-style.css', '10000', 'all');
	wp_enqueue_script('min-ec-global-script-js', get_template_directory_uri() . '/assets/js/min-ec-script.js', array(), '1.0.0');

	wp_localize_script(
		'min-ec-global-script-js',
		'eclipseScriptData',
		array(
			'eclipseAjaxUrl'          => admin_url('admin-ajax.php'),
			'eclipseNonce'            => wp_create_nonce('min-ec-ajax-nonce'),
		),
	);

	wp_enqueue_style('min_ec_google_web_fonts', 'https://fonts.googleapis.com/css?family=Oxygen', array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'min_ec_global_enqueue_scripts');

add_filter('acf/format_value/name=min_ec_countries', 'do_shortcode');
load_theme_textdomain('min_ec_textdomain', get_template_directory() . '/languages');

/* Register Widget */
function min_ec_register_widget()
{
	register_sidebar(array(
		'name'          => __('Sidebar', 'min_ec_textdomain'),
		'id'            => 'min-ec-global-sidebar',
		'description'   => __('The main sidebar appears on the right on each page except the front page template.', 'min_ec_textdomain'),
		'before_widget' => '<div id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	));
	register_sidebar(array(
		'name'          => __('Footer', 'min_ec_textdomain'),
		'id'            => 'min-ec-global-footer',
		'description'   => __('The main footer appears on the bottom on each page except the front page template.', 'min_ec_textdomain'),
		'before_widget' => '<div id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	));
}
add_action('widgets_init', 'min_ec_register_widget');

/* Disable wp-emoji */
function min_ec_disable_emojis()
{
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'min_ec_disable_emojis');

/* Disable wp-embed */
function min_ec_deregister_scripts()
{
	wp_dequeue_script('wp-embed');
}
add_action('wp_footer', 'min_ec_deregister_scripts');

if (!function_exists('min_ec_ip_redirect')) :
	function min_ec_ip_redirect()
	{
		$user_ip = $_SERVER['REMOTE_ADDR'];

		$start_of_ip = substr($user_ip, 0, 5);

		if ($start_of_ip === '77.29') {
			wp_redirect('https://google.com/', 301);
			exit;
		}
	}
endif;

add_action('template_redirect', 'min_ec_ip_redirect');

function custom_register_projects_post_type()
{
	$labels = array(
		'name'               => _x('Projects', 'post type general name', 'min_ec_textdomain'),
		'singular_name'      => _x('Project', 'post type singular name', 'min_ec_textdomain'),
		'menu_name'          => _x('Projects', 'admin menu', 'min_ec_textdomain'),
		'add_new'            => _x('Add New', 'project', 'min_ec_textdomain'),
		'add_new_item'       => __('Add New Project', 'min_ec_textdomain'),
		'new_item'           => __('New Project', 'min_ec_textdomain'),
		'edit_item'          => __('Edit Project', 'min_ec_textdomain'),
		'view_item'          => __('View Project', 'min_ec_textdomain'),
		'all_items'          => __('All Projects', 'min_ec_textdomain'),
		'search_items'       => __('Search Projects', 'min_ec_textdomain'),
		'not_found'          => __('No projects found', 'min_ec_textdomain'),
		'not_found_in_trash' => __('No projects found in Trash', 'min_ec_textdomain'),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => array('slug' => 'projects'),
		'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
		'menu_icon'          => 'dashicons-portfolio',
	);

	register_post_type('project', $args);

	// Register Project Type Taxonomy
	register_taxonomy(
		'project_type',
		'project',
		array(
			'label'        => __('Project Type', 'min_ec_textdomain'),
			'rewrite'      => array('slug' => 'project-type'),
			'hierarchical' => true,
		)
	);
}

add_action('init', 'custom_register_projects_post_type');

function custom_ajax_projects()
{
	$number_of_projects = is_user_logged_in() ? 6 : 3;
	$args = array(
		'post_type'      => 'project',
		'posts_per_page' => $number_of_projects,
		'tax_query'      => array(
			array(
				'taxonomy' => 'project_type',
				'field'    => 'slug',
				'terms'    => 'architecture',
			),
		),
	);

	$projects_query = new WP_Query($args);

	if ($projects_query->have_posts()) {
		$response_data = array();
		while ($projects_query->have_posts()) {
			$projects_query->the_post();
			$response_data[] = array(
				'id'    => get_the_ID(),
				'title' => get_the_title(),
				'link'  => get_permalink(),
			);
		}
		wp_reset_postdata();

		wp_send_json_success(array('data' => $response_data));
	} else {
		wp_send_json_error(array('message' => 'No projects found.'));
	}
}

add_action('wp_ajax_custom_ajax_projects', 'custom_ajax_projects');
add_action('wp_ajax_nopriv_custom_ajax_projects', 'custom_ajax_projects');


function hs_give_me_coffee()
{
	$response = wp_remote_get('https://coffee.alexflipnote.dev/random.json', array('sslverify' => false));
	if (is_wp_error($response)) {
		return false;
	}

	$body = wp_remote_retrieve_body($response);
	$coffee_data = json_decode($body);
	if ($coffee_data && isset($coffee_data->file)) {
		return esc_url($coffee_data->file);
	}

	return false;
}
function hs_give_me_coffee_shortcode()
{
	$coffee_url = hs_give_me_coffee();


	if ($coffee_url) {
		return '<p class="coffee-link"><a href="' . esc_url($coffee_url) . '">Get Coffee</a></p>';
	} else {
		return 'Error fetching coffee.';
	}
}

add_shortcode('min_ec_get_coffee', 'hs_give_me_coffee_shortcode');
function display_kanye_quotes()
{
	$quotes = array();

	for ($i = 0; $i < 5; $i++) {
		$response = wp_remote_get('https://api.kanye.rest/', array('sslverify' => false));
		if (is_wp_error($response)) {
			echo 'Error fetching Kanye quotes.';
			return;
		}

		$body = wp_remote_retrieve_body($response);
		$quote_data = json_decode($body);

		if ($quote_data && isset($quote_data->quote)) {
			$quotes[] = $quote_data->quote;
		} else {
			echo 'No quotes found.';
			return;
		}
	}

	echo '<ul>';
	foreach ($quotes as $quote) {
		echo "<li>$quote</li>";
	}
	echo '</ul>';
}

// Add a shortcode to display Kanye quotes on a page
add_shortcode('min_ec_kanye_quotes', 'display_kanye_quotes');
