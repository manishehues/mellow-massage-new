<?php
/**
 * The footer template.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<?php
	$queried_object = get_queried_object();
	$headerMenu = get_header_menu_data($queried_object);
	// echo $queried_object->ID;
	


?>
						<?php do_action( 'avada_after_main_content' ); ?>
					</div>  <!-- fusion-row -->
				</main>  <!-- #main -->
				<div class="site-footer footer_bender">
					<div class="fusion-builder-row fusion-row">
						<div class="footerNav">
							<div class="footerMenu">							
								<?php
									$queried_object = get_queried_object();
									//$headerMenu = getMenuData($queried_object);
									$footerContent = getfooterMenuData($queried_object);
								?>
								
									<?php foreach ($footerContent['menu'] as $menu) { ?>
										<div class="snglLocation">
											<h4><?php echo $menu['name']; ?></h4>
											<div class="conDetails"><?php echo $menu['contact_detail']; ?></div>
										</div>										

									<?php } ?>							
								
							</div>


							<div class="footer-nav">
								<?php if($footerContent['footer_nav']){ ?>
									<ul class="footernav">
										<?php foreach($footerContent['footer_nav'] as $footernav){ ?>

											<li><a href="<?php echo  $footernav['page_link_link'];?>"?><?php echo  $footernav['menu_name'];?></a></li>

										<?php } ?>
									</ul>


								<?php } ?>
							</div>
							<!-- <div class="col-6 footerMenu newsletter">
								<?php dynamic_sidebar('avada-custom-sidebar-footersocaillinks'); ?>
							</div> -->
						</div>
						<div class="copyright">
							<p>© 2022 Mellow Massage. All Rights Reserved</p>
						</div>						
					</div>			
				</div>

		<div class="avada-footer-scripts">
			<?php wp_footer(); ?>
		</div>
<script>
// jQuery(document).ready(function(){
// 		jQuery(".mobile_toggle").click(function(){
// 			jQuery("#MobileMenu").toggleClass("openMenu");
// 		});

// 		jQuery(".overLay").click(function(){
// 			jQuery("#MobileMenu").removeClass("openMenu");
// 			jQuery("#locations").removeClass("openLocation");
// 		});

// 		jQuery(".closeMenu").click(function(){
// 			jQuery("#MobileMenu").removeClass("openMenu");
// 		});

// 		});

		jQuery(document).ready(function(){

			var acc = document.getElementsByClassName("snglLocation");
			var panel = document.getElementsByClassName('conDetails');

			for (var i = 0; i < acc.length; i++) {
			    acc[i].onclick = function() {
			    	var setClasses = !this.classList.contains('open-location');
			        setClass(acc, 'open-location', 'remove');
			        
			       	if (setClasses) {
			            this.classList.toggle("open-location");
			            //this.nextElementSibling.classList.toggle("show");
			        }
			    }
			}

			function setClass(els, className, fnName) {
			    for (var i = 0; i < els.length; i++) {
			        els[i].classList[fnName](className);
			    }
			}

			jQuery(".selectLocation").change(function(){
			    var selected_location = jQuery(this).val();
			    window.location.href = site_url+'/'+selected_location;

			});

			jQuery(".mobile_toggle").click(function(){			
				if( jQuery(window).width() < 1024){

					jQuery(".siteHeader .navBar ul").slideToggle();
				}
			});	
			

		});
</script>

<script type="text/javascript">
				jQuery( document ).ready( function() {


					var ajaxurl = 'https://lifeismellow.creativehausdev.com/wp-admin/admin-ajax.php';
					if ( 0 < jQuery( '.fusion-login-nonce' ).length ) {
						jQuery.get( ajaxurl, { 'action': 'fusion_login_nonce' }, function( response ) {
							jQuery( '.fusion-login-nonce' ).html( response );
						});
					}
				});
				</script>



					


<!--  -->


<!--  -->

		<?php get_template_part( 'templates/to-top' ); ?>
	</body>

<!--  Clickcease.com tracking-->
<script type='text/javascript'>var script = document.createElement('script');
script.async = true; script.type = 'text/javascript';
var target = 'https://www.clickcease.com/monitor/stat.js';
script.src = target;var elem = document.head;elem.appendChild(script);
</script>
<noscript>
<a href='https://www.clickcease.com' rel='nofollow'><img src='https://monitor.clickcease.com/stats/stats.aspx' alt='ClickCease'/></a>
</noscript>
<!--  Clickcease.com tracking-->


</html>