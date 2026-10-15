<?php get_header(); ?>
    <div class="slogan">
        <div class="col-sm-12">
            <h1><span><?php single_post_title(); ?></span></h1>
            <?php if ( have_posts() ) : ?>

                <?php /* Start the Loop */ ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; ?>
            <?php endif; // end have_posts() check ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php if ( is_front_page() ) : ?>
        <div class="services">
            <div class="col-sm-12">
                <div class="col-sm-4 service-div">
                    <div class="products"> 
                        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Home Product Area')) : ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-sm-8 service-div">
                    <div class="products"> 
                        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Home Phone Area')) : ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php get_footer(); ?>