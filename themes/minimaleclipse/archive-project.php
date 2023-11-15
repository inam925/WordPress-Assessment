<?php
get_header();

$custom_query = new WP_Query(array(
    'post_type'      => 'project',
    'posts_per_page' => 6,
    'paged'          => get_query_var('paged') ? get_query_var('paged') : 1,
));

if ($custom_query->have_posts()) :
echo do_shortcode('[min_ec_get_coffee]');
?><button class="min-ec-ajax-button">Ajax Endpoint</button>
<div class="grid-container"><?php
    while ($custom_query->have_posts()) : $custom_query->the_post();
?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h2 class="entry-title"><?php the_title(); ?></h2>

            <?php
            if (has_post_thumbnail()) {
                the_post_thumbnail('thumbnail', array('class' => 'project-thumbnail'));
            }
            ?>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <?php
            $categories = get_the_category();
            if (!empty($categories)) {
                echo '<p class="project-category">Category: ' . esc_html($categories[0]->name) . '</p>';
            }
            ?>
        </article>
<?php
    endwhile;
    ?>
  </div>

    <?php
    the_posts_pagination(array('prev_text' => 'Prev', 'next_text' => 'Next', 'total' => $custom_query->max_num_pages));
    wp_reset_postdata();
else :
    echo 'No projects found.';
endif;

get_footer();
?>