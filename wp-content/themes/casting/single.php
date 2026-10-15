<?php get_header(); ?>
    <div class="slogan">
        <div class="col-sm-3">
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Product Area')) : ?>
            <?php endif; ?>
        </div>
        <div class="col-sm-9">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <h3><span><?php the_title(); ?></span></h3>
                <?php the_content(); ?>
            <?php endwhile; else: ?>
                <h1>Woops...</h1>
                <p>Sorry, no posts we're found.</p>
            <?php endif; ?>
            <!-- <p align="center"><?php posts_nav_link(); ?></p> -->
        </div>
    </div>
<?php get_footer(); ?>