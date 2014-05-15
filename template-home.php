<?php

/*
Template Name: Portfolder - Featured On Home
*/

?>

<?php get_header(); ?>

<?php if(!is_front_page()) { ?>

	<div id="subhead_container">

		<div class="row">

            <div class="twelve columns">

                <h1><?php if ( is_category() ) {
                
                    single_cat_title();
            
                    } elseif (is_tag() ) {
            
                    echo (__( 'Archives for ', 'dreamz' )); single_tag_title();
            
                } elseif (is_archive() ) {
            
                    echo (__( 'Archives for ', 'dreamz' )); single_month_title();
            
                } else {
            
                    wp_title('',true);
            
                } ?></h1>
        
            </div>	
    
        </div>
    
    </div>

<?php } ?>


<!-- slider -->

<?php if(is_front_page()) { ?>

<!-- Begin PortFOLDER Check -->

	<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); ?>


	<?php 
 
	$plugin_var = "portfolder"; // check for PortFOLDER plugin
  	
	if (in_array($plugin_var.'/'.$plugin_var.'.php', apply_filters('active_plugins', get_option('active_plugins' )))) { 
	
		$portfolder->DisplayPortfolderHome();
	
	} ?> <!-- portfolder end -->

<?php } ?> <!-- slider end -->


</div><!--content end-->

<?php get_footer(); ?>

