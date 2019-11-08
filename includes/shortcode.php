<?php
/**
 * Shortcode: Display the Upvoty widget
 *
 * Parameters:
 *
 * - board - Name of board
 */

add_shortcode('upvoty-wp', function( $atts = [] ) use ( $framework, $upvoty ) {
  return $upvoty->widget( $atts, true );
});
