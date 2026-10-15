<?php
/**
 *
 * Template Name: Infrastructure Template
 *
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage TEPL_theme
 * @since TEPL theme 1.0
 */

get_header(); ?>
    <div class="slogan">
        <div class="col-sm-3">
    		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Infrastructure Area')) : ?>
			<?php endif; ?>
        </div>
        <div class="col-sm-9">
            <h3><span><?php single_post_title(); ?></span></h3>
            <?php if ( have_posts() ) : ?>

                <?php /* Start the Loop */ ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; ?>
            <?php endif; // end have_posts() check ?>
        </div>
        <div class="clearfix"></div>
    </div>
<?php get_footer(); ?>
