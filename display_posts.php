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

define ('CATEGORY', get_option("einsatzverwaltung_settings_option_category_id"));

/**
 * Display posts from categories using [show_posts] shortcode
 * Display sticky posts using [show_posts sticky="true"] shortcode
 * Display latests posts using [show_posts latest="true" count ="5"] shortcode
 * Display latests missions using [show_posts missions="true" count ="5"] shortcode
 * 
 * @author Andre Becker
 **/
function show_posts_handler( $atts, $content=null, $code="" ) {
	
	// wp_die("Count ".count($atts));
	//code 4 displaying 
   		extract( shortcode_atts( array(
      		'sticky' => 'false',
      		'latest' => 'false',
      		'missions' => 'false',
      		'count' => '0',
      	), $atts ) );

   		if($sticky != 'false')
   		{
   			show_sticky_posts();
   		} else if($latest != 'false'){
   			show_lastest_posts($count);
   		}else if($missions != 'false'){
   			show_lastest_missions($count);
   		}else{
   			show_all_posts_from_categories();
   		}
}

add_shortcode( 'show_posts', 'show_posts_handler' );

function show_all_posts_from_categories()
{
	// orig coding
	// if(is_page('26'))
 //   	{
	//    	$extra_posts = new WP_Query( 'cat=2,6,9,13&showposts=-1&orderby=date' );

	if(is_page('222'))
   	{
   		$args = array(
			'category__in'  => array(1,4,5),
			'ignore_sticky_posts' => 1,
			'orderby'	=> date, 
			'posts_per_page' => -1
		);		
	   	$extra_posts = new WP_Query( $args );	
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

function show_sticky_posts()
{
	$sticky = get_option( 'sticky_posts' );
	$args = array(
		'post__in'  => $sticky,
		'ignore_sticky_posts' => 1,
		'orderby'	=> date, 
		'posts_per_page' => 5
	);

	$show_sticky_posts = new WP_Query( $args );
	
	if ( $show_sticky_posts->have_posts() )
	{
	    while( $show_sticky_posts->have_posts() )
	    {
	        $show_sticky_posts->the_post();
	        get_template_part( 'content', get_post_format() );
	    }

	    wp_reset_postdata();
	}
}


function show_lastest_posts($count)
{
	if($count == 0 )
		$count = 5;
	
	$sticky = get_option( 'sticky_posts' );

	$args = array(
		'category__in' => array(1,4,5),
		'ignore_sticky_posts' => 1,
		'orderby'	=> date,
		'post__not_in' => $sticky, 
		'posts_per_page' => $count
	);

	$latest_posts = new WP_Query( $args );	
	if ( $latest_posts->have_posts() )
	{
	    while( $latest_posts->have_posts() )
	    {
	        $latest_posts->the_post();
	        get_template_part( 'content', get_post_format() );
	    }

	    wp_reset_postdata();
	}
}

function show_lastest_missions($count)
{
	if($count == 0 )
		$count = 5;

	$sticky = get_option( 'sticky_posts' );
	$args = array(
		'category__in' => CATEGORY,
		'ignore_sticky_posts' => 1,
		'orderby'	=> date,
		'post__not_in' => $sticky, 
		'posts_per_page' => $count
	);

	$latest_missions = new WP_Query( $args );	
	if ( $latest_missions->have_posts() )
	{
	    while( $latest_missions->have_posts() )
	    {
	        $latest_missions->the_post();
	        get_template_part( 'content', get_post_format() );
	    }

	    wp_reset_postdata();
	}
}
?>