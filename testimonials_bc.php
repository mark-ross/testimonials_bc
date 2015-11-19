<?php

/*
Plugin Name: Testimonial BC
Plugin URI: http://www.berea.edu
Description: Plugin to store/display simple testimonials.
Author: Mark Ross
Version: 1.0
Author URI: http://markross.me
*/


function create_post_type() {
  register_post_type( 'testimonials',
    array(
      'labels' => array(
        'name' => __( 'Testimonials' ),
        'singular_name' => __( 'Testimonial' )
      ),
      'public' => true,
      'publicly_queryable' => true,
      'has_archive' => true,
      'show_ui' => true,
      'show_in_nav_menus' => true,
      'show_in_menu' => true,
      'capability_type'    => 'page',
      'edit_post'          => 'edit_testimonials',
      'read_post'          => 'read_testimonials',
      'delete_post'        => 'delete_testimonials',
      'edit_posts'         => 'edit_testimonials',
      'edit_others_posts'  => 'edit_others_testimonials',
      'publish_posts'      => 'publish_testimonials',
      'read_private_posts' => 'read_private_testimonials',
      'create_posts'       => 'edit_testimonials',
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail'),
    )
  );

}
add_action( 'init', 'create_post_type' );

function testimonial($atts, $content = null){

  //set up the global database
  global $wpdb;

  //This sets up the short code to accept the id variable only
  extract(shortcode_atts(array(
    "id" => 16,
  ), $atts));

  //Query the post database for testimonials with the proper id
  $q = "SELECT * FROM " . $wpdb->posts .
       " WHERE post_type = 'testimonials' AND ID = " . $id;

  //r is the result of the query
  $r = $wpdb->get_row($q);

  // get the name of the person
  //also assumes it's the post title
  $name = $r->post_title;

  //restricts the testimonials to 100 charachters long
  $to_post = substr($r->post_content,0,100);

  //return the guid of the page
  $link = $r->guid;

  //Query to pull the primary image
  $q = "SELECT * FROM " . $wpdb->posts . " WHERE post_parent = " . $id;

  //get the row with the link to the image
  $p = $wpdb->get_row($q);
  $p_link = $p->guid;

  //Queue up the styles for the plugin
  //Research this later to find out where this really needs to be placed!
  $z = plugins_url( 'testimonial_style.css', __FILE__ );
  wp_register_style( 'testimonial_bc_style', $z );
  wp_enqueue_style('testimonial_bc_style');

  //return the string of all the information
  return '<a href="' . $link . '"><div class="testimonial_bc"><img src=' . $p_link . '>'
        . $to_post . '... Read more. </div></a>';
}
add_shortcode("testimonial", "testimonial");

?>
