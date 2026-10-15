<?php
/**
 * The header for our theme
 *
 * @subpackage Product Industry
 * @since 1.0
 * @version 0.1
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
} else {
    do_action( 'wp_body_open' );
}?>

<a class="screen-reader-text skip-link" href="#skip-content"><?php esc_html_e( 'Skip to content', 'product-industry' ); ?></a>

<div id="header">
	<div class="container">
		<div class="main-header">
			<div class="row mx-0">
				<div class="col-lg-3 col-md-12 align-self-center">
					<div class="logo text-lg-left text-center">
						<?php if ( has_custom_logo() ) : ?>
		            		<?php the_custom_logo(); ?>
			            <?php endif; ?>
		             	<?php if (get_theme_mod('product_industry_show_site_title',true)) {?>
		          			<?php $blog_info = get_bloginfo( 'name' ); ?>
			                <?php if ( ! empty( $blog_info ) ) : ?>
			                  	<?php if ( is_front_page() && is_home() ) : ?>
			                    	<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			                  	<?php else : ?>
		                      		<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		                  		<?php endif; ?>
			                <?php endif; ?>
			            <?php }?>
			            <?php if (get_theme_mod('product_industry_show_tagline',true)) {?>
			                <?php $description = get_bloginfo( 'description', 'display' );
		                  	if ( $description || is_customize_preview() ) : ?>
			                  	<p class="site-description"><?php echo esc_html($description); ?></p>
		              		<?php endif; ?>
		          		<?php }?>
					</div>
				</div>
				<div class="<?php if(get_theme_mod('product_industry_phone_text') != '' || get_theme_mod('product_industry_phone_number') != ''){ ?> col-lg-6 col-md-8 <?php } else {?> col-lg-9 col-md-12 <?php }?> align-self-center">
					<?php if(get_theme_mod('product_industry_topbar_text') != ''){ ?>
						<p class="topbar-text text-md-left text-center"><?php echo esc_html(get_theme_mod('product_industry_topbar_text')); ?></p>
					<?php }?>
					<div class="menu-bar">
						<div class="toggle-menu responsive-menu text-md-left text-center">
							<?php if(has_nav_menu('primary')){ ?>
				            	<button onclick="product_industry_open()" role="tab" class="mobile-menu"><i class="fas fa-bars"></i><span class="screen-reader-text"><?php esc_html_e('Open Menu','product-industry'); ?></span></button>
				            <?php }?>
				        </div>
						<div id="sidelong-menu" class="nav sidenav">
			                <nav id="primary-site-navigation" class="nav-menu" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'product-industry' ); ?>">
			                  	<?php if(has_nav_menu('primary')){
				                    wp_nav_menu( array( 
										'theme_location' => 'primary',
										'container_class' => 'main-menu-navigation clearfix' ,
										'menu_class' => 'clearfix',
										'items_wrap' => '<ul id="%1$s" class="%2$s mobile_nav">%3$s</ul>',
										'fallback_cb' => 'wp_page_menu',
				                    ) ); 
			                  	} ?>
			                  	<a href="javascript:void(0)" class="closebtn responsive-menu" onclick="product_industry_close()"><i class="fas fa-times"></i><span class="screen-reader-text"><?php esc_html_e('Close Menu','product-industry'); ?></span></a>
			                </nav>
			            </div>
			        </div>
				</div>
				<?php if(get_theme_mod('product_industry_phone_text') != '' || get_theme_mod('product_industry_phone_number') != ''){ ?>
					<div class="col-lg-3 col-md-4 phone">
						<div class="row">
							<div class="col-lg-2 col-md-3 col-2 pl-0 align-self-center">
								<i class="fas fa-phone"></i>
							</div>
							<div class="col-lg-10 col-md-9 col-10 align-self-center">
								<span class="d-block"><?php echo esc_html(get_theme_mod('product_industry_phone_text')); ?></span>
								<a href="tel:<?php echo esc_attr(get_theme_mod('product_industry_phone_number')) ?>"><?php echo esc_html(get_theme_mod('product_industry_phone_number')); ?><span class="screen-reader-text"><?php echo esc_html(get_theme_mod('product_industry_phone_number')); ?></span></a>
							</div>
						</div>
					</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>

<?php if(is_singular()) {?>
	<div id="inner-pages-header">
		<div class="header-overlay"></div>
	    <div class="header-content">
		    <div class="container"> 
		      	<h1><?php single_post_title(); ?></h1><br>
		      	<div class="theme-breadcrumb mt-2">
					<?php product_industry_breadcrumb();?>
				</div>
		    </div>
		</div>
	</div>
<?php } ?>