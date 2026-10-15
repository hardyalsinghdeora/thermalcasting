<?php
/**
 * @package rename-default-post
 * @version 1.1.1
 */
/*
Plugin Name: Rename Default Post
Plugin URI: http://wordpress.org/plugins/rename-default-post
Description: Rename your Default Post name to custom name in admin menu.
Version: 1.1.1
Author: AboZain, Mohammed J. AlBanna
Author URI: https://profiles.wordpress.org/abozain
*/
add_action( 'admin_menu', 'rdp_reg_menu' );

function rdp_reg_menu(){
	add_options_page( __('Rename Default Post', 'RenameDefaultPost'), __('Rename Default Post', 'RenameDefaultPost'), 'administrator', 'rename-default-post', 'rdp_RenameDefaultPost'); 
}

//////////////////////////
# Load plugin text domain
add_action( 'init', 'rdp_plugin_textdomain' );
# Text domain for translations
function rdp_plugin_textdomain() {
    $domain = 'RenameDefaultPost';
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
    load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
    load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
//////////////////////////


function rdp_RenameDefaultPost(){

	//print_r($_POST);
	if(isset($_POST['name']) && ($_POST['name']) ){
		//print_r($_POST);
				$data['name'] = sanitize_text_field( $_POST['name'] );
                $data['singular_name'] = sanitize_text_field( $_POST['singular_name'] );
                $data['add_new'] = sanitize_text_field( $_POST['add_new'] );
                $data['add_new_item'] = sanitize_text_field( $_POST['add_new_item'] );
				$data['edit_item'] = sanitize_text_field( $_POST['edit_item'] );
				$data['new_item'] = sanitize_text_field( $_POST['new_item'] );
				$data['view_item'] = sanitize_text_field( $_POST['view_item'] );
				$data['search_items'] = sanitize_text_field( $_POST['search_items'] );
				$data['not_found'] = sanitize_text_field( $_POST['not_found'] );
				$data['not_found_in_trash'] = sanitize_text_field( $_POST['not_found_in_trash'] );

                update_option('RenameDefaultPost', $data);		
		echo '<br /> <br /> <h2 style="
  color: green;
  background-color: white;
  height: 15px;
  width: 95%;
  padding: 20px;">Saved Successfully</h2>';
  echo("<meta http-equiv='refresh' content='0'>"); //Refresh by HTTP META
	}else{
		$data =  get_option('RenameDefaultPost'); 
		//print_r($data);
	}
	$name = (isset($data['name']))? esc_html($data['name']) : __('News', 'RenameDefaultPost');
	$singular_name = (isset($data['singular_name']))? esc_html($data['singular_name']) : __('News', 'RenameDefaultPost') ;
	$add_new = (isset($data['add_new']))? esc_html($data['add_new']) : __('Add News', 'RenameDefaultPost');
	$add_new_item = (isset($data['add_new_item']))? esc_html($data['add_new_item']) : __('Add News', 'RenameDefaultPost');
	$edit_item = (isset($data['edit_item']))? esc_html($data['edit_item']) : __('Edit News', 'RenameDefaultPost');
	$new_item = (isset($data['new_item']))? esc_html($data['new_item']) : __('News', 'RenameDefaultPost');
	$view_item = (isset($data['view_item']))? esc_html($data['view_item']) : __('View News', 'RenameDefaultPost');
	$search_items = (isset($data['search_items']))? esc_html($data['search_items']) : __('Search News', 'RenameDefaultPost');
	$not_found = (isset($data['not_found']))? esc_html($data['not_found']) : __('No News found', 'RenameDefaultPost');
	$not_found_in_trash = (isset($data['not_found_in_trash']))? esc_html($data['not_found_in_trash']) : __('No News found in Trash', 'RenameDefaultPost');
	?>
        <div class="wrap">
            <?php screen_icon('edit-pages'); ?>
			<h2><?php _e('Rename Default Post', 'RenameDefaultPost') ?></h2>
            <h4><?php _e('Rename your Default Post name to custom name in admin menu', 'RenameDefaultPost') ?></h4>
            <form method="post" action="">
				<?php settings_fields( 'disable-settings-group' ); ?>
            	<?php do_settings_sections( 'disable-settings-group' ); ?>
			<br/>
			<table>	
			<tr>
				<td><label> <?php _e('Posts') ?> </label></td>
				<td><input type="text" size="30" name="name" value="<?php echo $name ?>" maxlength="20" /></td>
				<td> </td>
			</tr>
			<tr>
				<td><label> <?php _e('Post') ?> </label></td>
				<td><input type="text" size="30" name="singular_name" value="<?php echo $singular_name ?>" maxlength="20" /> </td>
				<td> </td>
			</tr>
			<tr>
				<td><label> <?php _e('Add New') ?> </label></td>
				<td><input id="width" type="text" size="30" name="add_new" value="<?php echo $add_new ?>" maxlength="20" /> </td>
				<td> </td>
			</tr>
			<tr>
				<td><label> <?php _e('Add New Post') ?> </label></td>
				<td><input id="title" type="text" size="30" name="add_new_item" value="<?php echo $add_new_item ?>" maxlength="50" /> </td>
				<td> </td>
			</tr>
			<tr>
				<td><label> <?php _e('Edit Post') ?> </label></td>
				<td><input type="text" size="30" name="edit_item" value="<?php echo $edit_item ?>" maxlength="50" /> </td>
				<td> </td>
			</tr>
			<tr>
				<td><label> <?php _e('New Post') ?> </label></td>
				<td><input type="text" size="30" name="new_item" value="<?php echo $new_item ?>" maxlength="50" /> </td>
				<td> </td>
			</tr>
			<tr>
				<td><label> <?php _e('View Post') ?> </label></td>
				<td><input type="text" size="30" name="view_item" value="<?php echo $view_item ?>" maxlength="50" /> </td>
				<td> </td>
			</tr>
			<tr>
				<td><label> <?php _e('Search Posts') ?> </label></td>
				<td><input type="text" size="30" name="search_items" value="<?php echo $search_items ?>" maxlength="50" /> </td>
				<td> </td>
			</tr>
			<tr>
				<td><label> <?php _e('No posts found.') ?> </label></td>
				<td><input type="text" size="30" name="not_found" value="<?php echo $not_found ?>" maxlength="50" /> </td>
				<td> </td>
			</tr>
			<tr>
				<td><label> <?php _e('No posts found in Trash.') ?> </label></td>
				<td><input type="text" size="30" name="not_found_in_trash" value="<?php echo $not_found_in_trash ?>" maxlength="50" /> </td>
				<td> </td>
			</tr>
			<table>
                <?php submit_button(); ?>
            </form>
        </div>	
		

		

		
		<?php
}

//////////////////////////
function rdp_change_post_menu_label() {
    global $menu;
    global $submenu;
	
	$data =  get_option('RenameDefaultPost'); 
	if(current_user_can( 'edit_posts' )){	
		if($data){
			$menu[5][0] = $data['name'];
			$submenu['edit.php'][5][0] = $data['name'];
			$submenu['edit.php'][10][0] = $data['add_new'];
			$submenu['edit.php'][16][0] = __('Tags');
			echo '';
		}
	}
}

add_action( 'admin_menu', 'rdp_change_post_menu_label' );

// Function to change post object labels to "news"
function rdp_change_post_object_label() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
	
	$data =  get_option('RenameDefaultPost'); 
	if($data){
		$labels->name = $data['name'];
		$labels->singular_name =  $data['singular_name'];
		$labels->add_new =  $data['add_new'];
		$labels->add_new_item =  $data['add_new_item'];
		$labels->edit_item =  $data['edit_item'];
		$labels->new_item =  $data['new_item'];
		$labels->view_item =  $data['view_item'];
		$labels->search_items =  $data['search_items'];
		$labels->not_found =  $data['not_found'];
		$labels->not_found_in_trash =  $data['not_found_in_trash'];
	}
}
add_action( 'init', 'rdp_change_post_object_label' );

///////////////////////////
?>
