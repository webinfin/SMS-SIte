<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
?>

<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url( '/' ); ?>">
	<input type="text" placeholder="<?php _e('Enter keywords', 'hbthemes'); ?>" name="s" id="s" autocomplete="off">
	<input type="submit" id="searchsubmit" class="no-three-d" value="">
</form>