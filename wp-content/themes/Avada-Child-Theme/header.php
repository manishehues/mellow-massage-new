<?php
/**
 * Header template.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<!DOCTYPE html>
<html class="<?php avada_the_html_class(); ?>" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<?php Avada()->head->the_viewport(); ?>

	<?php wp_head(); ?>

	<?php
	/**
	 * The setting below is not sanitized.
	 * In order to be able to take advantage of this,
	 * a user would have to gain access to the database
	 * in which case this is the least of your worries.
	 */
	echo apply_filters( 'avada_space_head', Avada()->settings->get( 'space_head' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
	?>
	<style type="text/css">
		
	</style>
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-194593724-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-194593724-1');
</script>
	
	
	<script type="text/javascript">
    adroll_adv_id = "RDGKHFIPBZCNZLKW6SEVIN";
    adroll_pix_id = "NSD5NCE3BZH2VLS3DQAGMW";
    adroll_version = "2.0";
    (function(w, d, e, o, a) {
        w.__adroll_loaded = true;
        w.adroll = w.adroll || [];
        w.adroll.f = [ 'setProperties', 'identify', 'track' ];
        var roundtripUrl = "https://s.adroll.com/j/" + adroll_adv_id
                + "/roundtrip.js";
        for (a = 0; a < w.adroll.f.length; a++) {
            w.adroll[w.adroll.f[a]] = w.adroll[w.adroll.f[a]] || (function(n) {
                return function() {
                    w.adroll.push([ n, arguments ])
                }
            })(w.adroll.f[a])
        }
        e = d.createElement('script');
        o = d.getElementsByTagName('script')[0];
        e.async = 1;
        e.src = roundtripUrl;
        o.parentNode.insertBefore(e, o);
    })(window, document);
    adroll.track("pageView");
</script>
	
	<!-- Start of Woopra Code -->
<script>
  (function(){
    var t,i,e,n=window,o=document,a=arguments,s="script",r=["config","track","identify","visit","push","call","trackForm","trackClick"],c=function(){var t,i=this;for(i._e=[],t=0;r.length>t;t++)(function(t){i[t]=function(){return i._e.push([t].concat(Array.prototype.slice.call(arguments,0))),i}})(r[t])};for(n._w=n._w||{},t=0;a.length>t;t++)n._w[a[t]]=n[a[t]]=n[a[t]]||new c;i=o.createElement(s),i.async=1,i.src="//static.woopra.com/js/w.js",e=o.getElementsByTagName(s)[0],e.parentNode.insertBefore(i,e)
  })("woopra");
  woopra.config({
      domain: 'lifeismellow.com'
  });
  woopra.track();
</script>
<!-- End of Woopra Code -->
	
</head>

<?php
$object_id      = get_queried_object_id();
$c_page_id      = Avada()->fusion_library->get_page_id();
$wrapper_class  = 'fusion-wrapper';
$wrapper_class .= ( is_page_template( 'blank.php' ) ) ? ' wrapper_blank' : '';
?>




<body <?php body_class(); ?> <?php fusion_element_attributes( 'body' ); ?>>
<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'twentyseventeen' ); ?></a>


<?php
	$queried_object = get_queried_object();
	$headerMenu = get_header_menu_data($queried_object);
	// echo $queried_object->ID;
	


?>



	<div id="MobileMenu">
		<div class="overLay"></div>
			<div class="cus_menu">				
				<ul>
					<?php foreach ($headerMenu['menu'] as $menu): ?>
						<?php
							$active = "";
							if($queried_object->ID == $menu['id']){
									$active = "active";
							}

							$is_location_class ='';
							if(isset($menu['is_location_class'])){
								$is_location_class = $menu['is_location_class'];
							}

						?>
						<li id="menu-item-<?php echo $menu['id']?>"  class="menu-item <?php echo $has_child; ?> <?php echo $is_location_class; ?> <?php echo $active;?> <?php echo $menu['class']?>">
							<a class="menuLink" href="<?php echo $menu['page_link_link'];?>"><?php echo $menu['name'];?></a>
							<?php if(isset($menu['location_menu']) && !empty($menu['location_menu'])) : ?>
								<i class="far fa-long-arrow-down"></i>
								<ul class="subMenu submenus">
									<?php foreach ($menu['location_menu'] as $key => $location) { ?>
										<div class="singleSubLoc">
											<h2 class="locTitle"><?php echo $location['title']; ?></h2>
											<ul>														
												<?php if( isset($location['sub_menu']) ):
													foreach ($location['sub_menu'] as  $sub_menu) { ?>
														<li>
															<a class="select_loc" href="<?php echo $sub_menu['sub_page_link']; ?>" rel="<?php echo $sub_menu['id'] ?>"><?php echo $sub_menu['name']; ?></a>
														</li>
												<?php } endif; ?>
											</ul>																		
										</div>	
									<?php } ?>
								</ul>
							<?php endif; ?>
						</li>
					<?php endforeach;?>
				</ul>				

				<div class="mobile-menu-footer">

					<?php

					 if(!empty($headerMenu['social_links'])) :

							$link_data = get_single_social_link("yelp",$headerMenu['social_links']);
							if(!empty($link_data)) : ?>
								<a href="<?php echo $link_data['link']?>" target="_blank">
									<img src="<?php echo $link_data['icon']?>" class="yelp">
								</a>
								<div class="yelp-txt">
									<span>Rated 5 Stars on Yelp</span> 
									<div class="star"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
								</div>
							<?php endif;

								

						endif;
 					?>
				</div>
				<div class="phoneNumber">
					<h4><a href="tel:<?php echo $headerMenu['contact_no'];?>" ><span><?php echo $headerMenu['contact_no'];?></span></a></h4>
				</div>
				


		</div>
	</div>
	
	
	
	<div class="siteHeader full">					
		<div class="leftSide">						
			<div class="navBar">
				<div id="bars" class="mobile_toggle">
					<span class="bar one"></span>
					<span class="bar two"></span>
					<span class="bar three"></span>
				</div>
				<ul>
					<?php if(isset($headerMenu['menu']) && !empty($headerMenu['menu'])) : 
						foreach ($headerMenu['menu'] as $menu): 
							
							$active = ""; 
							if($menu['id'] == $c_page_id){
								$active = "active";
							}
							$has_child = ""; 
							if(isset($menu['location_menu'])){
								$has_child = "has_child";
							}

							$is_location_class ='';
							if(isset($menu['is_location_class'])){
								$is_location_class = $menu['is_location_class'];
							}


						?>
						<li id="menu-item-<?php echo $menu['id']?>"  class="menu-item <?php echo $menu['class']?> <?php echo $has_child; ?> <?php echo $is_location_class; ?> <?php echo $active;?>">
							<a class="menuLink" href="<?php echo $menu['page_link_link'];?>"><?php echo $menu['name'];?></a>
							<?php if(isset($menu['location_menu'])) : ?>
														
									<div class="MobileSubMenu">
										<?php foreach ($menu['location_menu'] as $key => $location) { ?>
											
											<div class="singleSubLoc">
												<h2 class="locTitle"><?php echo $location['title']; ?></h2>
												<ul>														
													<?php if( isset($location['sub_menu']) ):
														foreach ($location['sub_menu'] as  $sub_menu) { ?>
															<li>
																<a class="select_loc" href="<?php echo $sub_menu['sub_page_link']; ?>" rel="<?php echo $sub_menu['id'] ?>"><?php echo $sub_menu['name']; ?></a>
															</li>
													<?php } endif; ?>
												</ul>																		
											</div>																	
										<?php } ?>
										<?php /*if(!empty($menu['is_location_class']) && $menu['is_location_class'] != 'dropdown-menu'): ?>

											<a class="seeAll" href="<?php echo site_url('locations'); ?>">See All Locations</a>

										<?php endif;*/  ?>
									</div>
							<?php endif;?>

						</li>
					<?php endforeach; endif;?>
				</ul>				
			</div>
		</div>
		<div class="rightSide contactDetails">
			<div class="phoneNumber">
				<h4><a href="tel:<?php echo $headerMenu['contact_no'];?>" ><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/Images/phoneIcon.jpg" alt="Phone"> <span><?php echo $headerMenu['contact_no'];?></span></a></h4>
			</div>
		</div>
	</div>
</header>
<div class="sideBar-socialLinks">
	<div>
		<?php if(!empty($headerMenu['social_links'])) : ?>
			<div class="socialIcons">
				<ul>
					<?php foreach ($headerMenu['social_links'] as $social_link): ?>
						<li>
							<a href="<?php echo $social_link['link'];?>" target="_blank" aria-label="icons">
								<img src="<?php echo $social_link['icon'];?>" alt="<?php echo get_bloginfo( 'name' ); ?>" />
							</a>
						</li>
					<?php endforeach;?>
				</ul>					
			</div>
			<div class="scrollDown">
				<p>Scroll Down</p>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/Images/long-arrow-down.svg" alt="">
			</div>
		<?php endif;?>
	</div>	
</div>