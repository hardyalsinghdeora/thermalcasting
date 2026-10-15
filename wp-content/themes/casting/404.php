<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Stepnie_theme
 * @since Stepnie theme 1.0
 */
get_header(); ?>
    <div class="slogan">
        <div class="col-sm-12">
			<h1><span><?php _e( 'Nothing Found', 'casting' ); ?></span></h1>
			<p>
				<?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'casting' ); ?>
				
			</p>
			<div class="col-sm-3">
				<?php get_search_form(); ?>
			</div>
		</div><!-- #content -->
	</div><!-- #primary -->
<?php get_footer(); ?>