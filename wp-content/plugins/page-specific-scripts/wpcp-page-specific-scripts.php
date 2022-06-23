<?php
/*
Plugin Name: Page Specific Scripts
Plugin URI: https://justentrepreneurship.com/page-specific-scripts
Description: Adds Scripts Only to a specific page.
Version: 1.0
Author: Afnan Abbasi
Author URI: https://fiverr.com/wpcoderpro
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wpcp-page-specific-scripts
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$Page_Specific_Scripts = new Page_Specific_Scripts();
$Page_Specific_Scripts->run();

class Page_Specific_Scripts{

	private $plugin_name;
	private $meta_key;
	private $show_on_front;

	function __construct(){
		$this->plugin_name = 'wpcp-page-specific-scripts';
		$this->meta_key = '_wpcp_page_specific_scripts';

		// tells if a page has been selected as front page
		// possible values 'posts' and 'page'
		$this->show_on_front = get_option( 'show_on_front' );
	}

	function run(){
		if( is_admin() ){
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'add_meta_boxes', array( $this, 'script_meta_box') );
			add_action( 'save_post', array( $this, 'save_scripts' ) );
			if( $this->show_on_front == 'posts' ){
				add_action( 'admin_init', array( $this, 'setting_page' ) );
			}
		}
		else{
			remove_action( 'wp_head', 'rel_canonical' );
			add_action( 'wp_head', array( $this, 'insert_script_in_head' ) );
			add_action( 'wp_head', 'rel_canonical' );
		}
	}

	function setting_page(){

		$option_group = 'general';
		$option_name = 'page_specific_script_front';
		register_setting( $option_group, $option_name );

		$id = 'page_specific_script_front';
		$title = __( 'Front Page Scripts', 'wpcp-page-specific-scripts' );
		$callback = array( $this, 'front_descr_input' );
		$page = 'general';
		$section = 'default';
		$args = array( 'label_for' => 'page_specific_script_front' );
		add_settings_field( $id, $title, $callback, $page, $section, $args );
	}

	function load_plugin_textdomain(){
		load_plugin_textdomain( 'wpcp-page-specific-scripts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	function insert_script_in_head(){
		$code_snippet = '';
		if( ! is_paged() ){
			if( is_single() or is_page() ){
				global $post;
	//			print "<!-- Easy WP Comments is_single() or is_page() \nID: $post->ID\n-->";
				$code_snippet = get_post_meta( $post->ID, $this->meta_key, true );
			}
			elseif( is_tag() or is_category() or is_tax()){
				$remove = array( '<p>', '</p>' );
				$code_snippet = trim( str_replace( $remove, '', term_description() ) );
			}
			elseif( is_front_page() ){
				if( $this->show_on_front == 'posts' ){
					if( ! $code_snippet = get_option( 'page_specific_script_front' ) ){
						$code_snippet = get_bloginfo( 'description', 'display' );
					}
				}
				else{
					$post_id = get_option( 'page_on_front' );
					$code_snippet = get_post_meta( $post_id, $this->meta_key, true );
				}
			}
			elseif( is_home() ){
				$home_id = get_option( 'page_for_posts' );
	//			print "<!-- Easy WP Comments is_home()\nID: $post->ID\n\$home_id: $home_id\n -->";
				$code_snippet = get_post_meta( $home_id, $this->meta_key, true );
			}
		}
		if( $code_snippet ){
				?>

<!-- Page Specific Scripts -->
<script>
<?php print $code_snippet; ?>
</script>
<!-- /Page Specific Scripts -->

				<?php
		}
	}

	function script_meta_box(){
		$id = 'add_script';
		$title =  'Add Page Specific Scripts';
		$callback = array( $this, 'add_script_meta_box' );
		$context = 'normal';
		$priority = 'default';
		$callback_args = '';

		// get custom posttypes
		$args = array( 'public'   => true, '_builtin' => false );
		$output = 'names';
		$operator = 'and';
		$custom_posttypes = get_post_types( $args, $output, $operator );
		$builtin_posttypes = array( 'post', 'page' );
		$screens = array_merge( $builtin_posttypes, $custom_posttypes );
		foreach ( $screens as $screen ) {
			add_meta_box( $id, $title, $callback, $screen, $context,
				 $priority, $callback_args,
				 array( '__block_editor_compatible_meta_box' => false, ) );
		}
	}

	function front_descr_input(){
		$setting = get_option( 'page_specific_script_front' );
		?>
<input type="text" size="40" name="page_specific_script_front" id="page_specific_script_front"
	class="regular-text" value="<?php print $setting ?>">
		<?php
	}

	function add_script_meta_box(){
		wp_nonce_field( 'add_script_meta_box', 'add_script_meta_box_nonce' );
		$post_id = get_the_ID();
		$value = get_post_meta( $post_id, $this->meta_key, true );?>
<div class="wp-editor-container">
<textarea class="wp-editor-area" id="page_specific_scripts" name="add_script" cols="80" rows="5"><?php print $value; ?></textarea>
</div>
<p><?php
		_e( 'Add a jQuery/JS Script that should run only on this page', 'wpcp-page-specific-scripts' ); ?>.</p>
		<?php
	}

	function save_scripts( $post_id ){
		if( ! isset( $_POST['add_script_meta_box_nonce'] ) ){
			return;
		}
		if( ! wp_verify_nonce( $_POST['add_script_meta_box_nonce'], 'add_script_meta_box' ) ){
			return;
		}
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if( ! isset( $_POST['add_script'] ) ){
			return;
		}
		$data = wp_unslash($_POST['add_script'] );
		update_post_meta( $post_id, $this->meta_key, $data );
	}

}


?>
