<?php
/**
 * Template part for displaying posts
 *
 * @subpackage Product Industry
 * @since 1.0
 * @version 1.4
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('inner-service'); ?>>
  <?php if(has_post_thumbnail()) { ?>
    <?php the_post_thumbnail(); ?>
  <?php }?>
  <div class="article_content">
    <h3><?php the_title(); ?></h3>
    <p class="mb-0">
      <?php $product_industry_excerpt = get_the_excerpt(); echo esc_html( product_industry_string_limit_words( $product_industry_excerpt,30 ) ); ?> <?php echo esc_html('...', 'product-industry'); ?>
      <a href="<?php the_permalink(); ?>" class="read-btn"><?php echo esc_html('Read More', 'product-industry'); ?><span class="screen-reader-text"><?php echo esc_html('Read More', 'product-industry'); ?></span></a>
    </p>
    <div class="clearfix"></div>
  </div>
  <div class="metabox"> 
    <span class="entry-comments"><i class="fas fa-comments"></i><?php comments_number( __('0 Comments','product-industry'), __('0 Comments','product-industry'), __('% Comments','product-industry') ); ?></span>
    <span class="entry-date"><span><i class="fas fa-calendar-alt"></i><?php echo esc_html( get_the_date()); ?></span></span>
  </div>
</article>