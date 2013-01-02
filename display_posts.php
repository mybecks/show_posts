<?php
/*
Plugin Name: Show Posts
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Display Posts of diffrent categories on a specific page
Version: 0.1 alpha
Author: Andre Becker
Author URI: la.ffbs.de
License: GPL2
*/

/**
 * Display posts from categories using [show_posts] shortcode
 * 
 * @author Andre Becker
 **/
function show_posts_handler( $atts, $content=null, $code="" ) {
	
	//code 4 displaying 
   if(is_page('26'))
   {
   	$extra_posts = new WP_Query( 'cat=2,6,9,13&showposts=-1&orderby=date' );
	if ( $extra_posts->have_posts() )
	{
	    while( $extra_posts->have_posts() )
	    {
	        $extra_posts->the_post();
	        get_template_part( 'content', get_post_format() );
	    }
	    wp_reset_postdata();
	}
   }
}

add_shortcode( 'show_posts', 'show_posts_handler' );

?>