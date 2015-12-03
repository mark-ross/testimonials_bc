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
      'has_archive' => true,
      'show_ui' => true,
      'show_in_nav_menus' => true,
      'show_in_menu' => true,
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
  global $wpdb; //set up the global database
  //This sets up the short code to accept the id variable only
  extract(shortcode_atts(array(
    "id" => 16,
    "name" => "mark-ross",
    "title" => "Mark Ross"
  ), $atts));

  //Query the post database for testimonials with the proper id
  $q = "SELECT * FROM " . $wpdb->posts . " WHERE post_type = 'testimonials' AND ID = " . $id;

  //r is the result of the query
  $r = $wpdb->get_row($q);
  //print $r->post_content;
  $post_name = $r->title;
  $to_post = substr($r->post_content, 80);
  $link = $r->guid;

  //Query to pull the primary image
  $q = "SELECT * FROM " . $wpdb->posts . " WHERE post_parent = " . $id;

  //get the row with the link to the image
  $p = $wpdb->get_row($q);

  $z = plugins_url( 'testimonial_style.css', __FILE__ );
  wp_register_style( 'testimonial_bc_style', $z );
  wp_enqueue_style('testimonial_bc_style');

  //print out the results of the search
  return '<div class="testimonial_bc"><img src=' . $p->guid . '>' . $r->post_content . ' </div>';
}
add_shortcode("testimonial", "testimonial");

?>
