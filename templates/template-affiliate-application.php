
<?php
   /**
    * Template Name: Affiliate Application
    * Template Post Type: post, page
    *
    * @package WordPress
    * @since 1.0
    */
   ?>
<?php 
  get_header();
  if(have_posts()) { 
   the_post();
  }
?>
  <main id="site-content" role="main">
  </main>
<?php get_footer();?>