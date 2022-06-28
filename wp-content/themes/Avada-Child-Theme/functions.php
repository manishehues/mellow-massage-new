<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css' );
    wp_enqueue_style( 'slick-style', get_stylesheet_directory_uri() . '/assets/css/slick.css' );
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/assets/css/custom.css', array( 'fusion-dynamic-css' ) );
    wp_enqueue_style( 'responsive-style', get_stylesheet_directory_uri() . '/assets/css/responsive.css', array( 'fusion-dynamic-css' ) );
    wp_enqueue_style( 'fontawesome-style', 'https://pro.fontawesome.com/releases/v5.10.0/css/all.css' );

  


}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



function my_custom_scripts() {
    wp_enqueue_script( 'slick-js', get_stylesheet_directory_uri() . '/assets/js/slide.js', array( 'jquery' ),'',true );
    wp_enqueue_script( 'global-js', get_stylesheet_directory_uri() . '/assets/js/global.js', array( 'jquery' ),'',true );

	wp_localize_script( 'global-js', 'site_url', site_url( '/' ) );
	wp_enqueue_script( 'global-js' );
}
add_action( 'wp_enqueue_scripts', 'my_custom_scripts' );



function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );







/*================ Locations =================*/


if( function_exists('acf_add_options_page') ) {
	
	// acf_add_options_sub_page(array(
	// 	'page_title' 	=> 'Header ',
	// 	'menu_title'	=> 'Header',
	// 	'parent_slug'	=> 'acf-options',
	// ));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'New Header ',
		'menu_title'	=> 'New Header',
		'parent_slug'	=> 'acf-options',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Footer',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'acf-options',
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Map Locations',
		'menu_title'	=> 'Map Locations',
		'parent_slug'	=> 'acf-options',
	));
	
}



/*================ Location Menus =================*/
/*================ Location Menus =================*/

function getAllLocations(){
	$locations = [];
	if( have_rows('new_location', 'options') ):
		while ( have_rows('new_location', 'options') ) :
			$post = the_row();
			$location_page_obj = get_sub_field('location_url');
			$location_page_name = get_sub_field('location_name');
			$location_page_lnk = get_permalink($location_page_obj->ID);
			$location_page_id =  $location_page_obj->ID;
			$locations[] = array(
							'name' => $location_page_name,
							'location_page_lnk' => $location_page_lnk,
							'location_page_id' => $location_page_id,
						);
		endwhile;
	endif;
	return $locations;
}

function get_header_menu_data($queried_object){

	$default_menu = 70;
	$requested_page_id = $queried_object->ID;
	$requested_post_parent = $queried_object->post_parent;
	$mobile_call_icon = "";

	while ( have_rows('all_locations', 'options') ) : the_row(); 
		while ( have_rows('sub_locations') ) :  $sublocations = the_row();
			$menu = [];
			$logo = get_sub_field('sub_location_logo');

			$location_name = $signup_link_obj = $location_page_lnk= $location_page_id ="";

			$location_name		= get_sub_field('sub_location_name');
			$sub_location_menu 	= get_sub_field('sub_location_menu');
			$logo_custom_url 	= get_sub_field('logo-url');
 			$contact_no 	= get_sub_field('phone_number');
 			
			$book_now_text 	= get_sub_field('book_now_text');
			$book_now_link 	= get_sub_field('book_now_link');
			


			$location_page_lnk 	= 'javascript:void(0);';
			$location_page_id 	=  '';


			if(!empty($sub_location_menu)){
				$location_page_lnk 	= get_permalink($sub_location_menu->ID);
				$location_page_id 	=  $sub_location_menu->ID;
			}

			
			$logo = get_sub_field('sub_location_logo');


			$signup_link = get_sub_field('sign_up');
			$social_links = getContactData();
			

			//===getting submenus udner sub locations
			if( have_rows('menu') ):

				$i=0;
				while ( have_rows('menu') ) : the_row();
					$page_link_link = "";
					$menu_obj = "";
					$name = get_sub_field('page_name');
					$is_location_dropdown = get_sub_field('is_location_dropdown');

                    $class 	= get_sub_field('class');

					//print_r($is_location_dropdown);
					$is_location_class = 'dropdown-menu';
					if(!empty($is_location_dropdown) && $is_location_dropdown == 'yes'){
						$is_location_class = 'dropdown-menu dropdown-location-menu';
					}




					$menu_obj = get_sub_field('page_link');

					$page_custom_link = get_sub_field('page_custom_link');
					$menu[] = getMenuArr($menu_obj, $name, $page_custom_link);

					// if($page_custom_link !="") {
					// 	unset($menu['page_link_link']);

					// 	$menu['page_link_link'] = $page_custom_link;
					// }

					$location_menu = [];

				
					
					/* fetch sub menu  */
					while ( have_rows('sub_menu') ) : $post = the_row();
						$location_title = get_sub_field('title');

						$sub_menu = [];

						while ( have_rows('sub_menu_pages') ) : the_row();

							$sub_menu_obj = get_sub_field('sub_page_link');
							$sub_page_name = get_sub_field('sub_page_name');
							
							if(isset($sub_menu_obj->ID)){

								$sub_page_link = get_permalink($sub_menu_obj->ID);

								$sub_menu[] = array('id' => $sub_menu_obj->ID, 
													'slug' => $sub_menu_obj->post_name, 
													'name' => $sub_page_name,
													'sub_page_link'=>$sub_page_link
												);
							}
						
						endwhile; /* END SUB PAGES WHILE LOOP */
						$menu[$i]['is_location_class'] = $is_location_class;
						$menu[$i]['location_menu'][] = ['title' => $location_title,'sub_menu' => $sub_menu];
					endwhile; /* END SUB PAGES WHILE LOOP */
					$i++;
				
				endwhile;
			endif;

			if( $logo_custom_url != ""){
				$location_page_lnk = $logo_custom_url;
			}
			
			$arrayName[$location_page_id] = array(
													'location_name'  		=> $location_name,
													'social_links' 			=> $social_links,
													'book_now_text' 		=> $book_now_text,
													'book_now_link' 		=> $book_now_link,
													'contact_no' 			=> $contact_no,
													'menu' 					=> $menu,
													'logo_url' 				=> $location_page_lnk,
													'logo'					=> $logo
												);



		endwhile;
	endwhile;
	//getUserLocation();
	if(array_key_exists($requested_page_id, $arrayName)){
		$default_menu = $requested_page_id;
	}
	if($requested_post_parent !=0 && array_key_exists($requested_post_parent, $arrayName)){
		$default_menu = $requested_post_parent;
	}

	if(is_404()){
		$default_menu = 70;

	}

	//print_r($arrayName[$default_menu]['menu']);


	return $arrayName[$default_menu];
}


function getMenuArr($menu_obj,$name,$page_custom_link) {

	$page_link = "javascript:void(0)";
	$slug = "";
	$menu_id = 0;
	if(isset($menu_obj) && isset($menu_obj->ID)){

		$page_link = get_permalink($menu_obj->ID);
		$slug = $menu_obj->post_name;
		$menu_id = $menu_obj->ID;

	}  
	$class111 	= get_sub_field('class');

	if($page_custom_link !="") {

		$page_link = $page_custom_link;
	}

	return array('id' => $menu_id, 
					'slug' => $slug, 
					'name' => $name,
					'class' => $class111,
					'page_link_link' => $page_link
				);

}




function getContactData(){
	$contact = [];
	if( have_rows('social_link') ):
		while ( have_rows('social_link') ) : the_row();
			$icon = get_sub_field('icon');
			$link = get_sub_field('url');

			$social_link_type = get_sub_field('social_link_type');
			$contact[$social_link_type] = array("icon"=>$icon, "link" =>$link );
		endwhile;
	endif;
	return $contact;
}

function getFooterMenuData($queried_object){

	$default_menu = 70;
	$requested_page_id = $queried_object->ID;
	$requested_post_parent = $queried_object->post_parent;

	$arrayName = [];
	$menu = [];
	if( have_rows('all_location', 'option') ):

    	while ( have_rows('all_location', 'option') ) : $post = the_row();
			$locations = "";
			$main_location_name = get_sub_field('location_name');
			$contact = get_sub_field('contact');

			$address = get_sub_field('address');



			$location_page_obj 	= get_sub_field('location_url');
			$location_page_lnk 	= get_permalink($location_page_obj->ID);
			$location_page_id 	=  $location_page_obj->ID;

			$menu = getFooterMenus();

			$footer_nav = getFooterNavigation();

			
			$arrayName[$location_page_id] = array(
						'location_name'  	=> $main_location_name,
						'contact' 			=> $contact,
						'address' 			=> $address,
						'location_page_lnk' => $location_page_lnk,
						'menu' 				=> $menu,
						'footer_nav'		=> $footer_nav
						
					);

		endwhile;
	endif;

	if(array_key_exists($requested_page_id, $arrayName)){

		$default_menu = $requested_page_id;
	}

	if($requested_post_parent !=0 && array_key_exists($requested_post_parent, $arrayName)){

		$default_menu = $requested_post_parent;
	}


	return $arrayName[$default_menu];
}

function getFooterMenus() {

	$menu = [];
	$i= 0;
	if( have_rows('footer_menu') ):
		while ( have_rows('footer_menu') ) : the_row();
			$page_link_link = "";					
			$contact_detail = get_sub_field('contact_detail');
			 $name = get_sub_field('page_name');

			// $page_link_link = get_permalink($menu_obj->ID);
			
			// if(!empty($menu_obj)){
			// 	$page_link_link = get_permalink($menu_obj->ID);
			// }
					
			$menu[] = array('contact_detail' => $contact_detail,'name' => $name );
						
		endwhile; // END PAGES WHILE LOOP 
	endif;

	return $menu; 

}


function getFooterNavigation() {

	$menu = [];
	$i= 0;
	if( have_rows('footer_navigation') ):
		while ( have_rows('footer_navigation') ) : the_row();
			$page_link_link = "";					
			$contact_detail = get_sub_field('contact_detail');
			$menu_name = get_sub_field('menu_name');
			$menu_obj = get_sub_field('menu_link');

			$page_link_link = get_permalink($menu_obj->ID);
			
			if(!empty($menu_obj)){
				$page_link_link = get_permalink($menu_obj->ID);
			}
					
			$menu[] = array('menu_name' => $menu_name,'page_link_link' => $page_link_link,'id' =>$menu_obj->ID );
						
		endwhile; // END PAGES WHILE LOOP 
	endif;

	return $menu; 

}




function get_single_social_link($social_link_type,$social_links_array){
	$link = "#";

	if(isset($social_links_array[$social_link_type]) && !empty($social_links_array[$social_link_type])){

		$link = $social_links_array[$social_link_type];
	}

	return $link;


}

add_action('template_redirect', 'redirect_if_404');
function redirect_if_404() {
	
    if ( is_404() ) {
        // Remember to change the /path-to-go with the URL you like to redirect the users.
        // 301 is permanent redirect. 302 is Temporary redirect.
        wp_redirect(esc_url(home_url('/')), 301);
        // And here will stop the file execution.
        exit();
    }
}


// add_action('init','check_location_redirect');

// function check_location_redirect(){

// $cookie_name = "mello_locatioin";
// $cookie_value = "161.149.146.201";
// $is_redirect_cookie = 'is_redirect_mellow';
// $user_ip  = $_SERVER['REMOTE_ADDR'];

// $url = "https://ipinfo.io/".$user_ip."?token=e1e16e3ee4b24c";

// //$url = "https://ipinfo.io/161.149.146.201?token=e1e16e3ee4b24c";

// if(!isset($_COOKIE[$cookie_name])) {
	
// 	$geoloaction = json_decode(file_get_contents($url));

// 	if(isset($geoloaction->city) && $geoloaction->city == 'Los Angeles'){
// 		setcookie($cookie_name, $cookie_value, time() + (86400), "/");
		
// 		if(!isset($_COOKIE[$is_redirect_cookie])) {
// 			setcookie($is_redirect_cookie, 'yes', time() + (86400), "/"); 
// 			wp_redirect(site_url('/hollywood'));
// 		}
		
// 	}
// }

//}


// add_action('init','check_location_redirect');
// function check_location_redirect(){

// 	$cookie_name = "mello_locatioin";
// 	$cookie_value = "";
// 	$is_redirect_cookie = 'is_redirect_mellow';
// 	$user_ip  = $_SERVER['REMOTE_ADDR'];

// 	$locations = [
// 				['location' => 'college-area', 'lat' => 32.7657612, 'long' => -117.0611822],
// 				['location' => 'hillcrest','lat' => 32.7487114, 'long' => -117.1564724],
// 				['location' => 'pacific-beach', 'lat'=> 32.7850126,'long' =>-117.2929761],
// 				['location' => 'hollywood', 'lat'=> 34.089348,'long' =>-118.3322413]
// 			];

// 	if(!isset($_COOKIE[$cookie_name])) {

// 		$url = "https://ipinfo.io/".$user_ip."?token=e1e16e3ee4b24c";

// 		$url = "https://ipinfo.io/192.150.86.225?token=e1e16e3ee4b24c";
// 		$geoloaction = json_decode(file_get_contents($url));
// 		list($lat,$long) = explode(',', $geoloaction->loc);

// 		$less_distance = [];
// 		foreach ($locations as $key => $location) {
// 			$less_distance[ $location['location'] ] = distance($lat, $long, $location['lat'], $location['long'], "K");
// 			distance($lat, $long, $location['lat'], $location['long'], "K");
// 		}

// 		$cookie_value = array_search(min($less_distance), $less_distance);
// 		setcookie($cookie_name, $cookie_value, time() + (86400), "/");
// 		if(!isset($_COOKIE[$is_redirect_cookie])) {

// 			setcookie($is_redirect_cookie, 'yes','','/');
// 			wp_redirect(site_url('/'.$cookie_value));exit();

// 		}
// 	} else {

// 		if(!isset($_COOKIE[$is_redirect_cookie])) {
// 			setcookie($is_redirect_cookie, 'yes','','/');
// 			wp_redirect(site_url('/'.$_COOKIE[$cookie_name]));
// 			exit();
// 		}
// 	}
// }


function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
      return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
  } else {
      return $miles;
  }
}

