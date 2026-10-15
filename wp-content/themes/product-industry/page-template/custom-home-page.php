<?php
/**
 * Template Name: Custom Home
 */

get_header(); ?>

<main id="skip-content" role="main">

	<?php do_action( 'product_industry_above_slider' ); ?>

	<?php if( get_theme_mod('product_industry_slider_hide_show') != ''){ ?>
		<section id="slider">
			<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
			    <?php $product_industry_slider_pages = array();
			    for ( $count = 1; $count <= 4; $count++ ) {
			        $mod = intval( get_theme_mod( 'product_industry_slider'. $count ));
			        if ( 'page-none-selected' != $mod ) {
			          $product_industry_slider_pages[] = $mod;
			        }
			    }
		      	if( !empty($product_industry_slider_pages) ) :
			        $args = array(
			          	'post_type' => 'page',
			          	'post__in' => $product_industry_slider_pages,
			          	'orderby' => 'post__in'
			        );
		        	$query = new WP_Query( $args );
		        if ( $query->have_posts() ) :
		          	$i = 1;
		    	?>     
				    <div class="carousel-inner" role="listbox">
				      	<?php  while ( $query->have_posts() ) : $query->the_post(); ?>
					        <div <?php if($i == 1){echo 'class="carousel-item fade-in-image active"';} else{ echo 'class="carousel-item fade-in-image"';}?>>
					        	<div class="sliderimg">
		            				<img src="<?php esc_url(the_post_thumbnail_url('full')); ?>" alt="<?php the_title_attribute(); ?> "/>
							    </div>
			        			<div class="carousel-caption">
						            <div class="inner-carousel">
						              	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						              	<?php 
						                  $product_industry_slider_excerpt_length = get_theme_mod('product_industry_slider_excerpt_length');
						                
						                  if( $product_industry_slider_excerpt_length != ''){?>
						          	     <p class="mb-0"><?php $product_industry_excerpt = get_the_excerpt(); echo esc_html( product_industry_string_limit_words( $product_industry_excerpt, esc_attr(get_theme_mod('product_industry_slider_excerpt_length','15') ) )); ?></p>
						              	<?php } ?>
						              	<a href="<?php the_permalink(); ?>" class="read-btn"><span><?php echo esc_html('Read More','product-industry'); ?></span><i class="fas fa-chevron-right ml-2"></i><span class="screen-reader-text"><?php echo esc_html('Read More','product-industry'); ?></span></a>
				            		</div>
				            	</div>
					        </div>
				      	<?php $i++; endwhile; 
				      	wp_reset_postdata();?>
				    </div>
			    <?php else : ?>
			    	<div class="no-postfound"></div>
	      		<?php endif;
			    endif;?>
			    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
			      	<span class="carousel-control-prev-icon" aria-hidden="true"><i class="fas fa-angle-left"></i></span>
			      	<span class="screen-reader-text"><?php esc_html_e( 'Prev','product-industry' );?></span>
			    </a>
			    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
			      	<span class="carousel-control-next-icon" aria-hidden="true"><i class="fas fa-angle-right"></i></span>
			      	<span class="screen-reader-text"><?php esc_html_e( 'Next','product-industry' );?></span>
			    </a>
			</div>
		  	<div class="clearfix"></div>
		</section>
	<?php }?>
	
	<?php do_action('product_industry_below_slider'); ?>

	<?php if( get_theme_mod('product_industry_features_category_setting') != '' ){?>
		<section id="feature-section" class="py-5">
			<div class="container"> 
	        	<div class="row ">  
	        		<?php $product_industry_catData1 =  get_theme_mod('product_industry_features_category_setting');
					if($product_industry_catData1){ 
						$args = array(
							'post_type' => 'post',
							'category_name' => $product_industry_catData1,
							'posts_per_page' => get_theme_mod('product_industry_features_number',3)
				        );
				        $i=1; ?>
		        		<?php $query = new WP_Query( $args );
			          	if ( $query->have_posts() ) :
			        		while( $query->have_posts() ) : $query->the_post(); ?>
			        			<div class="col-lg-4 col-md-6 align-self-center">
			          				<div class="feature-box mb-3">
			          					<div class="row">
			          						<div class="col-lg-2 col-md-2 col-2 align-self-center position-relative">
			          							<div class="feature-icon">
			          								<i class="<?php echo esc_attr(get_theme_mod('product_industry_feature_icon' . $i, 'fas fa-chart-line')); ?>"></i>
			          							</div>
			          						</div>
			          						<div class="col-lg-10 col-md-10 col-10 align-self-center pl-0">
			          							<div class="feature-content">
						            				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									              	<p class="mb-0">
									              		<?php $product_industry_excerpt = get_the_excerpt(); echo esc_html( product_industry_string_limit_words( $product_industry_excerpt,8 ) ); ?> <?php echo esc_html('...', 'product-industry'); ?>
									              		<a href="<?php the_permalink(); ?>"><?php echo esc_html('Read More', 'product-industry'); ?><span class="screen-reader-text"><?php echo esc_html('Read More', 'product-industry'); ?></span></a>
									              	</p>
					            				</div>
			          						</div>
			          					</div>
			          				</div>
			          			</div>
			          		<?php $i++; endwhile; 
			          		wp_reset_postdata(); ?>
			          	<?php else : ?>
			              	<div class="no-postfound"></div>
			            <?php endif; ?>
		      		<?php }?>
	          	</div>
			</div>
		</section>
	<?php }?>

	<?php do_action('product_industry_below_features_section'); ?>

	<?php if( get_theme_mod('product_industry_service_category_setting') != '' || get_theme_mod('product_industry_section_title') != '' ){?>
		<section id="service-section" class="py-5">
			<div class="container"> 
				<?php if(get_theme_mod('product_industry_section_title') != ''){ ?>
					<h3 class="text-center mb-5"><?php echo esc_html(get_theme_mod('product_industry_section_title')); ?></h3>
				<?php }?>
	        	<div class="row ">  
	        		<?php $product_industry_catData1 =  get_theme_mod('product_industry_service_category_setting');
					if($product_industry_catData1){ 
						$args = array(
							'post_type' => 'post',
							'category_name' => $product_industry_catData1,
				        );
				        $i=1; ?>
		        		<?php $query = new WP_Query( $args );
			          	if ( $query->have_posts() ) :
			        		while( $query->have_posts() ) : $query->the_post(); ?>
			        			<div class="col-lg-4 col-md-6">
			          				<div class="service-box mb-5">
			          					<?php if(has_post_thumbnail()) { ?>
			          						<div class="service-img">
			          							<?php the_post_thumbnail(); ?>
			          						</div>
			          					<?php }?>
	          							<div class="service-content">
				            				<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
							              	<p class="mb-2"><?php $product_industry_excerpt = get_the_excerpt(); echo esc_html( product_industry_string_limit_words( $product_industry_excerpt,8 ) ); ?></p>
							              	<a href="<?php the_permalink(); ?>" class="service-btn"><?php echo esc_html('Read More', 'product-industry'); ?><i class="fas fa-chevron-right ml-2"></i><span class="screen-reader-text"><?php echo esc_html('Read More', 'product-industry'); ?></span></a>
			            				</div>
			          				</div>
			          			</div>
			          		<?php $i++; endwhile; 
			          		wp_reset_postdata(); ?>
			          	<?php else : ?>
			              	<div class="no-postfound"></div>
			            <?php endif; ?>
		      		<?php }?>
	          	</div>
			</div>
		</section>
	<?php }?>

	<?php do_action('product_industry_below_service_section'); ?>

	<div class="container">
	  	<?php while ( have_posts() ) : the_post(); ?>
	  		<div class="lz-content">
	        	<?php the_content(); ?>
	        </div>
	    <?php endwhile; // end of the loop. ?>
	</div>
</main>

<?php get_footer(); ?>