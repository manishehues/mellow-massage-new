<?php
/**
 * The template used for 404 pages.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<?php get_header(); ?>
<section id="content" class="full-width">
	<div id="post-404page">
		<div class="post-content">
			<?php
			// Render the page titles.
			//$subtitle = esc_html__( 'Oops, This Page Could Not Be Found!', 'Avada' );
			//Avada()->template->title_template( $subtitle );
			?>
			<div class="fusion-clearfix"></div>
			<div class="error-page">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/not-found.svg" width="450" alt="">				
			</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>
