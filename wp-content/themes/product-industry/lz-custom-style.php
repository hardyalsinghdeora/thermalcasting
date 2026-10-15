<?php 

	$product_industry_custom_style = '';

	// Logo Size
	$product_industry_logo_top_padding = get_theme_mod('product_industry_logo_top_padding');
	$product_industry_logo_bottom_padding = get_theme_mod('product_industry_logo_bottom_padding');
	$product_industry_logo_left_padding = get_theme_mod('product_industry_logo_left_padding');
	$product_industry_logo_right_padding = get_theme_mod('product_industry_logo_right_padding');

	if( $product_industry_logo_top_padding != '' || $product_industry_logo_bottom_padding != '' || $product_industry_logo_left_padding != '' || $product_industry_logo_right_padding != ''){
		$product_industry_custom_style .=' .logo {';
			$product_industry_custom_style .=' padding-top: '.esc_attr($product_industry_logo_top_padding).'px; padding-bottom: '.esc_attr($product_industry_logo_bottom_padding).'px; padding-left: '.esc_attr($product_industry_logo_left_padding).'px; padding-right: '.esc_attr($product_industry_logo_right_padding).'px;';
		$product_industry_custom_style .=' }';
	}

	// Header Image
	$header_image_url = product_industry_banner_image( $image_url = '' );
	if( $header_image_url != ''){
		$product_industry_custom_style .=' #inner-pages-header {';
			$product_industry_custom_style .=' background-image: url('. esc_url( $header_image_url ).'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed;';
		$product_industry_custom_style .=' }';
	} else {
		$product_industry_custom_style .=' #inner-pages-header {';
			$product_industry_custom_style .=' background: radial-gradient(circle at center, rgba(0,0,0,0) 0%, #000000 100%); ';
		$product_industry_custom_style .=' }';
	}

	$product_industry_slider_hide_show = get_theme_mod('product_industry_slider_hide_show',false);
	if( $product_industry_slider_hide_show == true){
		$product_industry_custom_style .=' .page-template-custom-home-page #inner-pages-header {';
			$product_industry_custom_style .=' display:none;';
		$product_industry_custom_style .=' }';
	}