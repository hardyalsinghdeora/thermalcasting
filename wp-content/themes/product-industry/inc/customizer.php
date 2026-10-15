<?php
/**
 * Product Industry: Customizer
 *
 * @subpackage Product Industry
 * @since 1.0
 */

use WPTRT\Customize\Section\Product_Industry_Button;

add_action( 'customize_register', function( $manager ) {

	$manager->register_section_type( Product_Industry_Button::class );

	$manager->add_section(
		new Product_Industry_Button( $manager, 'product_industry_pro', [
			'title' => __( 'Product Industry Pro', 'product-industry' ),
			'priority' => 0,
			'button_text' => __( 'Go Pro', 'product-industry' ),
			'button_url'  => esc_url( 'https://www.luzuk.com/product/industrial-product-wordpress-theme/', 'product-industry')
		] )
	);

} );

// Load the JS and CSS.
add_action( 'customize_controls_enqueue_scripts', function() {

	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_script(
		'product-industry-customize-section-button',
		get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/js/customize-controls.js' ),
		[ 'customize-controls' ],
		$version,
		true
	);

	wp_enqueue_style(
		'product-industry-customize-section-button',
		get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/css/customize-controls.css' ),
		[ 'customize-controls' ],
 		$version
	);

} );

function product_industry_customize_register( $wp_customize ) {

	$wp_customize->add_setting('product_industry_logo_padding',array(
		'sanitize_callback'	=> 'esc_html'
	));
	$wp_customize->add_control('product_industry_logo_padding',array(
		'label' => __('Logo Margin','product-industry'),
		'section' => 'title_tagline'
	));

	$wp_customize->add_setting('product_industry_logo_top_padding',array(
		'default' => '',
		'sanitize_callback'	=> 'product_industry_sanitize_float'
	));
	$wp_customize->add_control('product_industry_logo_top_padding',array(
		'type' => 'number',
		'description' => __('Top','product-industry'),
		'section' => 'title_tagline',
	));

	$wp_customize->add_setting('product_industry_logo_bottom_padding',array(
		'default' => '',
		'sanitize_callback'	=> 'product_industry_sanitize_float'
	));
	$wp_customize->add_control('product_industry_logo_bottom_padding',array(
		'type' => 'number',
		'description' => __('Bottom','product-industry'),
		'section' => 'title_tagline',
	));

	$wp_customize->add_setting('product_industry_logo_left_padding',array(
		'default' => '',
		'sanitize_callback'	=> 'product_industry_sanitize_float'
	));
	$wp_customize->add_control('product_industry_logo_left_padding',array(
		'type' => 'number',
		'description' => __('Left','product-industry'),
		'section' => 'title_tagline',
	));

	$wp_customize->add_setting('product_industry_logo_right_padding',array(
		'default' => '',
		'sanitize_callback'	=> 'product_industry_sanitize_float'
 	));
 	$wp_customize->add_control('product_industry_logo_right_padding',array(
		'type' => 'number',
		'description' => __('Right','product-industry'),
		'section' => 'title_tagline',
    ));

	$wp_customize->add_setting('product_industry_show_site_title',array(
		'default' => true,
		'sanitize_callback'	=> 'product_industry_sanitize_checkbox'
	));
	$wp_customize->add_control('product_industry_show_site_title',array(
		'type' => 'checkbox',
		'label' => __('Show / Hide Site Title','product-industry'),
		'section' => 'title_tagline'
	));

	$wp_customize->add_setting('product_industry_show_tagline',array(
		'default' => true,
		'sanitize_callback'	=> 'product_industry_sanitize_checkbox'
	));
	$wp_customize->add_control('product_industry_show_tagline',array(
		'type' => 'checkbox',
		'label' => __('Show / Hide Site Tagline','product-industry'),
		'section' => 'title_tagline'
	));

	$wp_customize->add_panel( 'product_industry_panel_id', array(
		'priority' => 10,
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => __( 'Theme Settings', 'product-industry' ),
		'description' => __( 'Description of what this panel does.', 'product-industry' ),
	) );

	$wp_customize->add_section( 'product_industry_theme_options_section', array(
    	'title'      => __( 'General Settings', 'product-industry' ),
		'priority'   => 30,
		'panel' => 'product_industry_panel_id'
	) );

	$wp_customize->add_setting('product_industry_theme_options',array(
		'default' => 'One Column',
		'sanitize_callback' => 'product_industry_sanitize_choices'
	));
	$wp_customize->add_control('product_industry_theme_options',array(
		'type' => 'select',
		'label' => __('Blog Page Sidebar Layout','product-industry'),
		'section' => 'product_industry_theme_options_section',
		'choices' => array(
		   'Left Sidebar' => __('Left Sidebar','product-industry'),
		   'Right Sidebar' => __('Right Sidebar','product-industry'),
		   'One Column' => __('One Column','product-industry'),
		   'Grid Layout' => __('Grid Layout','product-industry')
		),
	));

	$wp_customize->add_setting('product_industry_single_post_sidebar',array(
		'default' => 'Right Sidebar',
		'sanitize_callback' => 'product_industry_sanitize_choices'
	));
	$wp_customize->add_control('product_industry_single_post_sidebar',array(
        'type' => 'select',
        'label' => __('Single Post Sidebar Layout','product-industry'),
        'section' => 'product_industry_theme_options_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','product-industry'),
            'Right Sidebar' => __('Right Sidebar','product-industry'),
            'One Column' => __('One Column','product-industry')
        ),
	));

	$wp_customize->add_setting('product_industry_page_sidebar',array(
		'default' => 'One Column',
		'sanitize_callback' => 'product_industry_sanitize_choices'
	));
	$wp_customize->add_control('product_industry_page_sidebar',array(
        'type' => 'select',
        'label' => __('Page Sidebar Layout','product-industry'),
        'section' => 'product_industry_theme_options_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','product-industry'),
            'Right Sidebar' => __('Right Sidebar','product-industry'),
            'One Column' => __('One Column','product-industry')
        ),
	));

	$wp_customize->add_setting('product_industry_archive_page_sidebar',array(
		'default' => 'One Column',
		'sanitize_callback' => 'product_industry_sanitize_choices'
	));
	$wp_customize->add_control('product_industry_archive_page_sidebar',array(
        'type' => 'select',
        'label' => __('Archive & Search Page Sidebar Layout','product-industry'),
        'section' => 'product_industry_theme_options_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','product-industry'),
            'Right Sidebar' => __('Right Sidebar','product-industry'),
            'One Column' => __('One Column','product-industry'),
            'Grid Layout' => __('Grid Layout','product-industry')
        ),
	));

	//Header
	$wp_customize->add_section( 'product_industry_header' , array(
    	'title'    => __( 'Header Section', 'product-industry' ),
		'priority' => null,
		'panel' => 'product_industry_panel_id'
	) );

	$wp_customize->add_setting('product_industry_topbar_text',array(
    	'default' => '',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('product_industry_topbar_text',array(
	   	'type' => 'text',
	   	'label' => __('Add Topbar Text','product-industry'),
	   	'section' => 'product_industry_header',
	));

	$wp_customize->add_setting('product_industry_phone_text',array(
    	'default' => '',
    	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('product_industry_phone_text',array(
	   	'type' => 'text',
	   	'label' => __('Add Phone Text','product-industry'),
	   	'section' => 'product_industry_header',
	));

	$wp_customize->add_setting('product_industry_phone_number',array(
    	'default' => '',
    	'sanitize_callback'	=> 'product_industry_sanitize_phone_number'
	));
	$wp_customize->add_control('product_industry_phone_number',array(
	   	'type' => 'url',
	   	'label' => __('Add Phone Number','product-industry'),
	   	'section' => 'product_industry_header',
	));

	//home page slider
	$wp_customize->add_section( 'product_industry_slider_section' , array(
    	'title'    => __( 'Slider Settings', 'product-industry' ),
		'priority' => null,
		'panel' => 'product_industry_panel_id'
	) );

	$wp_customize->add_setting('product_industry_slider_hide_show',array(
    	'default' => false,
    	'sanitize_callback'	=> 'product_industry_sanitize_checkbox'
	));
	$wp_customize->add_control('product_industry_slider_hide_show',array(
	   	'type' => 'checkbox',
	   	'label' => __('Show / Hide Slider','product-industry'),
	   	'section' => 'product_industry_slider_section',
	));

	for ( $count = 1; $count <= 4; $count++ ) {
		$wp_customize->add_setting( 'product_industry_slider' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'product_industry_sanitize_dropdown_pages'
		));
		$wp_customize->add_control( 'product_industry_slider' . $count, array(
			'label' => __('Select Slider Image Page', 'product-industry' ),
			'section' => 'product_industry_slider_section',
			'type' => 'dropdown-pages'
		));
	}


	$wp_customize->add_setting('product_industry_slider_excerpt_length',array(
		'default' => '15',
		'sanitize_callback'	=> 'product_industry_sanitize_float'
	));
	$wp_customize->add_control('product_industry_slider_excerpt_length',array(
		'type' => 'number',
		'label' => __('Description Excerpt Length','product-industry'),
		'section' => 'product_industry_slider_section',
	));


	//Features Section
	$wp_customize->add_section('product_industry_features_section',array(
		'title'	=> __('Features Section','product-industry'),
		'description'=> __('<b>Note :</b> This section will appear below the slider.','product-industry'),
		'panel' => 'product_industry_panel_id',
	));

	$categories = get_categories();
	$cats = array();
	$i = 0;
	$cat_pst[]= 'select';
	foreach($categories as $category){
		if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_pst[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('product_industry_features_category_setting',array(
		'default' => 'select',
		'sanitize_callback' => 'product_industry_sanitize_choices',
	));
	$wp_customize->add_control('product_industry_features_category_setting',array(
		'type' => 'select',
		'choices' => $cat_pst,
		'label' => __('Select Category To Display Post','product-industry'),
		'section' => 'product_industry_features_section',
	));

	$wp_customize->add_setting('product_industry_features_number',array(
		'default'	=> '3',
		'sanitize_callback'	=> 'sanitize_text_field',
	));
	$wp_customize->add_control('product_industry_features_number',array(
		'label'	=> __('Number Of Posts To Show In A Category','product-industry'),
		'section' => 'product_industry_features_section',
		'type'	  => 'number'
	));

	$product_industry_features_number = get_theme_mod('product_industry_features_number', 3);
	for ($i=1; $i <= $product_industry_features_number; $i++) { 
	   	$wp_customize->add_setting('product_industry_feature_icon' . $i, array(
	      	'default' => 'fas fa-chart-line',
	      	'sanitize_callback' => 'sanitize_text_field'
	   	));
	   	$wp_customize->add_control(new Product_Industry_Fontawesome_Icon_Chooser($wp_customize, 'product_industry_feature_icon' . $i, array(
	      	'section' => 'product_industry_features_section',
	      	'type' => 'icon',
	      	'label' => esc_html__('Feature Icon ', 'product-industry') . $i
	  	)));
	}

	//Service Section
	$wp_customize->add_section('product_industry_service_section',array(
		'title'	=> __('Service Section','product-industry'),
		'description'=> __('This section will appear below the features section.','product-industry'),
		'panel' => 'product_industry_panel_id',
	));

    $wp_customize->add_setting('product_industry_section_title',array(
		'default' => '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('product_industry_section_title',array(
		'label'	=> __('Section Title','product-industry'),
		'section' => 'product_industry_service_section',
		'type' => 'text'
	));

	$categories = get_categories();
	$cats = array();
	$i = 0;
	$cat_pst1[]= 'select';
	foreach($categories as $category){
		if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_pst1[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('product_industry_service_category_setting',array(
		'default' => 'select',
		'sanitize_callback' => 'product_industry_sanitize_choices',
	));
	$wp_customize->add_control('product_industry_service_category_setting',array(
		'type' => 'select',
		'choices' => $cat_pst1,
		'label' => __('Select Category to display Post','product-industry'),
		'section' => 'product_industry_service_section',
	));

	//Footer
    $wp_customize->add_section( 'product_industry_footer', array(
    	'title'  => __( 'Footer Text', 'product-industry' ),
		'priority' => null,
		'panel' => 'product_industry_panel_id'
	) );

	$wp_customize->add_setting('product_industry_show_back_totop',array(
       'default' => true,
       'sanitize_callback'	=> 'product_industry_sanitize_checkbox'
    ));
    $wp_customize->add_control('product_industry_show_back_totop',array(
       'type' => 'checkbox',
       'label' => __('Show / Hide Back to Top','product-industry'),
       'section' => 'product_industry_footer'
    ));

    $wp_customize->add_setting('product_industry_footer_copy',array(
		'default' => '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('product_industry_footer_copy',array(
		'label'	=> __('Footer Text','product-industry'),
		'section' => 'product_industry_footer',
		'setting' => 'product_industry_footer_copy',
		'type' => 'text'
	));

	$wp_customize->get_setting( 'blogname' )->transport          = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport   = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport  = 'postMessage';

	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.site-title a',
		'render_callback' => 'product_industry_customize_partial_blogname',
	) );
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => '.site-description',
		'render_callback' => 'product_industry_customize_partial_blogdescription',
	) );
}
add_action( 'customize_register', 'product_industry_customize_register' );

function product_industry_customize_partial_blogname() {
	bloginfo( 'name' );
}

function product_industry_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

if (class_exists('WP_Customize_Control')) {

   	class Product_Industry_Fontawesome_Icon_Chooser extends WP_Customize_Control {

      	public $type = 'icon';

      	public function render_content() { ?>
	     	<label>
	            <span class="customize-control-title">
	               <?php echo esc_html($this->label); ?>
	            </span>

	            <?php if ($this->description) { ?>
	                <span class="description customize-control-description">
	                   <?php echo wp_kses_post($this->description); ?>
	                </span>
	            <?php } ?>

	            <div class="product-industry-selected-icon">
	                <i class="fa <?php echo esc_attr($this->value()); ?>"></i>
	                <span><i class="fa fa-angle-down"></i></span>
	            </div>

	            <ul class="product-industry-icon-list clearfix">
	                <?php
	                $product_industry_font_awesome_icon_array = product_industry_font_awesome_icon_array();
	                foreach ($product_industry_font_awesome_icon_array as $product_industry_font_awesome_icon) {
	                   $icon_class = $this->value() == $product_industry_font_awesome_icon ? 'icon-active' : '';
	                   echo '<li class=' . esc_attr($icon_class) . '><i class="' . esc_attr($product_industry_font_awesome_icon) . '"></i></li>';
	                }
	                ?>
	            </ul>
	            <input type="hidden" value="<?php $this->value(); ?>" <?php $this->link(); ?> />
	        </label>
	        <?php
      	}
  	}
}
function product_industry_customizer_script() {
   wp_enqueue_style( 'font-awesome-1', esc_url(get_template_directory_uri()).'/assets/css/fontawesome-all.css');
}
add_action( 'customize_controls_enqueue_scripts', 'product_industry_customizer_script' );