<?php
/**
 *
 * Template Name: Product Template
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
    		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Product Area')) : ?>
			<?php endif; ?>
        </div>
        <div class="col-sm-9">
            <h3><span><?php single_cat_title(); ?></span></h3>
            <div class="row products-list">
                <?php $i=0; while ( have_posts() ) : the_post(); ?>
                    <?php echo ($i % 3 == 0) ? '<div class="clearfix"></div><br />' : ''; ?>
                    <div class="col-sm-4">
                        <div class="view view-first">
                            <?php the_post_thumbnail();?>
                            <div class="mask">
                                <h2><?php the_title(); ?></h2>
                            </div>
                        </div>
                        <!-- <div class="view">
                            <h5><?php // the_title(); ?></h5>
                        </div> -->
                    </div>
                <?php $i++; endwhile; // end of the loop. ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
<?php get_footer(); ?>
