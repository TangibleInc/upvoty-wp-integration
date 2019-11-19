<?php
/**
 * Shortcode: Display the Upvoty widget
 */

add_shortcode('upvoty-wp', function( $atts = [] ) use ( $upvoty ) {
  return $upvoty->widget( $atts, true );
});
