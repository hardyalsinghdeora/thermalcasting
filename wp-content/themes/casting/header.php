<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
<title><?php bloginfo('name'); ?> | <?php wp_title(); ?></title>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen,projection" />
 
<?php wp_head(); ?>
 
</head>  
<body>
<!-- A grey horizontal navbar that becomes vertical on small screens -->
<div class="container main-area">
    <div class="col-sm-12">
        <div class="header">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="row">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <div class="pull-left">
                                    <h1 class="main-title"> 
                                        <img src="<?php header_image(); ?>" width="80px" alt="<?php bloginfo('name'); ?> <?php bloginfo('description'); ?>">
                                        <?php bloginfo('name'); ?>
                                        <span><?php bloginfo('description'); ?></span>
                                    </h1>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-3 nav">
                        <div class="head_data">
                            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Header Widget Area')) : ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-12">
                <div class="row">
                    <nav class="navbar">
                        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => 'div','container_class' => 'collapse navbar-collapse in', 'container_id' => 'navbar', 'menu_class' => 'nav navbar-nav', 'items_wrap' => '<ul class="nav navbar-nav">%3$s</ul>', ) ); ?>
                    </nav>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div>
