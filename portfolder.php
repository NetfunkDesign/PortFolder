<?php

/**

 * @package PortFOLDER

 */

/*

Plugin Name: PortFOLDER

Plugin URI: http://www.netfunkdesign.com

Description: The Portfolio Maker // Featured Pages Plugin for Wordpress

Version: 1.0

Author: Phil Sanders

Author URI: http://www.netfunkdesign.com

License: Copyright NetFunkDesign 2013

*/

class PortFolder {

	var $pluginUrl;
	var $optionPage;
	var $action;
	var $sid;

    public function __construct() {

		$this->pluginUrl = WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__));

		// register scripts

        $this->LoadTextDomain();
        add_action('admin_menu', array(&$this,'MenuPagesInit'));
        add_action('admin_init',array(&$this,'Attachments'));
		add_action('admin_head', array(&$this,'draggable_script_addon'));
		
		// portfolder display shortcode
		add_shortcode('portfolder',array(&$this,'portfolder_main_code'));

    }


	public function draggable_script_addon() {

        wp_register_style('portfolder-plugin', $this->pluginUrl.'/css/portfolder.css');
		wp_enqueue_style('portfolder-plugin');
		
		//wp_register_script('portfolder-jquery-form', '/wp-includes/js/jquery/jquery.form.min.js', array('jquery-form'),true,'',false);
		//wp_enqueue_script('portfolder-jquery-form');
		
		wp_register_script('jquery-ui-sortable', '/wp-includes/js/jquery/ui/jquery.ui.sortable.min.js', array('jquery-ui-sortable'),true,'',false);
		wp_enqueue_script('jquery-ui-sortable');

		//wp_register_script('portfolder-plugin', $this->pluginUrl.'/js/portfolder.js', array('jquery-form'));

		?>
        
		<script language="javascript">
		<!--
		
			jQuery(function() {
				
				jQuery( "#sortable-categories" ).sortable({
					
					start: function (e, ui) {
						
						//alert("started");
					
					},
					
					update: function (e, ui) {
						
						//alert("updated");

						
					}
				});
				
				jQuery( "#sortable-categories" ).disableSelection();
				
				
				
				/* scroll to ADD NEEW CATEGORY form */
				
				jQuery("a[href='#new-category']").click(function() {
				
				jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, "slow");
				
				return false;
				
				});
				
			});
			
		-->
		</script>
        
        <?php	
		
	}

    public function MenuPagesInit() {

		//add_theme_page( 'portfolder', 'PortFOLDER', 'administrator', 'portfolder', array(&$this,'PortFolder_Settings_Page'));

		add_options_page('Settings Admin', 'PortFOLDER', 'manage_options', 'portfolder', array(&$this, 'PortFolder_Settings_Page'));
	
    }


    public function Attachments() {

		// HOME PAGE SETTINGS ----------------------------------------------------------------------------

		
		// PortFOLDER Home Title
		add_settings_section('portfolder-settings', 'Home Page Settings',array($this, 'print_hometitle_info'), 'portfolder_hometitle_options');	
		add_settings_field('home-text-portfolder', '<b>Title Text:</b>', array($this, 'create_hometitle_field'), 'portfolder_hometitle_options','portfolder-settings',array('label_for'=>'hometitle-portfolder'));

		// PortFOLDER Home Text Blurb
		add_settings_section('portfolder-settings', '', array($this, 'print_homeblurb_info'), 'portfolder_homeblurb_options');	
		add_settings_field('home-blurb-portfolder', '<b>Blurb Text:</b>', array($this, 'create_homeblurb_field'), 'portfolder_homeblurb_options','portfolder-settings',array('label_for'=>'homeblurb-portfolder'));

		// Home Image Selection / Featured Slides
		add_settings_section('portfolder-settings', 'Home Page Image',array($this, 'print_homeimage_info'), 'portfolder_homeimage_options');	
		add_settings_field('home-image-portfolder', '<b>Home Image Path:</b> <br> Recommended: 637x298 px', array($this, 'create_homeimage_field'), 'portfolder_homeimage_options','portfolder-settings',array('label_for'=>'homeimage-portfolder'));
		
		// PortFOLDER Home Image Caption
		add_settings_section('portfolder-settings', 'Image Caption', array($this, 'print_homeimagecaption_info'), 'portfolder_homeimagecap_options');	
		add_settings_field('home-imagecaption-portfolder', '<b>Home Image Caption:</b>', array($this, 'create_homeimagecaption_field'), 'portfolder_homeimagecap_options','portfolder-settings',array('label_for'=>'homeimagecaption-portfolder'));

		// Force PortFOLDER on home settings options
		add_settings_section('portfolder-settings','Advanced Settings',array($this, 'print_force_section_info'),'portfolder_force_options');	
		add_settings_field('force-portfolder', '<b>Force to Home:<b>', array($this, 'create_force_checkbox'), 'portfolder_force_options','portfolder-settings',array('label_for'=>'force-portfolder'));

		//-------------------------------------------------------------------------------------------------


		// FEATURED ON HOME

		// featured category title
		add_settings_section('portfolder-featured-settings','Add A New Featured Category',array($this, 'print_featured_cattitle_info'),'portfolder_featured_options');	
		add_settings_field('cattitle-featured-portfolder', '<b>Add New Category:<b>', array($this, 'create_featured_cattitle_field'), 'portfolder_featured_options','portfolder-featured-settings',array('label_for'=>'title-category-featured'));

		// featured category description
		add_settings_section('portfolder-featured-settings','Add A New Featured Category',array($this, 'print_featured_cattitle_info'),'portfolder_featured_options');	
		add_settings_field('catdesc-featured-portfolder', '<b>Category Description:<b>', array($this, 'create_featured_catdesc_textarea'), 'portfolder_featured_options','portfolder-featured-settings',array('label_for'=>'desc-category-featured'));
		
		// featured  category image
		add_settings_section('portfolder-featured-settings','Add A New Featured Category',array($this, 'print_featured_cattitle_info'),'portfolder_featured_options');	
		add_settings_field('catimage-featured-portfolder', '<b>Category Image:<b>', array($this, 'create_featured_catimage_field'), 'portfolder_featured_options','portfolder-featured-settings',array('label_for'=>'image-category-featured'));
		
		// featured  category image
		add_settings_section('portfolder-featured-settings','Add A New Featured Category',array($this, 'print_featured_cattitle_info'),'portfolder_featured_options');	
		add_settings_field('catlink-featured-portfolder', '<b>Category Link:<b>', array($this, 'create_featured_catlink_field'), 'portfolder_featured_options','portfolder-featured-settings',array('label_for'=>'link-category-featured'));
		
		
		//-----------------
		
		// Featured  category  - remove option
		add_settings_section('portfolder-featured-settings','Remove A Featured Category',array($this, 'print_featured_remove_info'),'portfolder_featureremove_options');	
		add_settings_field('remove-featured-portfolder', '', array($this, 'create_featured_remove_field'), 'portfolder_featureremove_options','portfolder-featured-settings');
		


		//-------------------------------------------------------------------------------------------------


		// PORTFOLIO SETUP

		// Portfolio category title
		add_settings_section('portfolder-portfolio-settings','Add A New Portfolio Category',array($this, 'print_portfolio_cattitle_info'),'portfolder_portfolio_options');	
		add_settings_field('cattitle-portfolio-portfolder', '<b>Add New Category:<b>', array($this, 'create_portfolio_cattitle_field'), 'portfolder_portfolio_options','portfolder-portfolio-settings',array('label_for'=>'title-category-portfolio'));

		// Portfolio category description
		add_settings_section('portfolder-portfolio-settings','Add A New Portfolio Category',array($this, 'print_portfolio_catdesc_info'),'print_portfolio_cattitle_info');	
		add_settings_field('catdesc-portfolio-portfolder', '<b>Category Description:<b>', array($this, 'create_portfolio_catdesc_textarea'), 'portfolder_portfolio_options','portfolder-portfolio-settings',array('label_for'=>'desc-category-portfolio'));
		
		// Portfolio  category image
		add_settings_section('portfolder-portfolio-settings','Add A New Portfolio Category',array($this, 'print_portfolio_cattitle_info'),'portfolder_portfolio_options');	
		add_settings_field('catimage-portfolio-portfolder', '<b>Category Image:<b>', array($this, 'create_portfolio_catimage_field'), 'portfolder_portfolio_options','portfolder-portfolio-settings',array('label_for'=>'image-category-portfolio'));
		
		// Portfolio  category link
		add_settings_section('portfolder-portfolio-settings','Add A New Portfolio Category',array($this, 'print_portfolio_catlink_info'),'portfolder_portfolio_options');	
		add_settings_field('catlink-portfolio-portfolder', '<b>Category Link:<b>', array($this, 'create_portfolio_catlink_field'), 'portfolder_portfolio_options','portfolder-portfolio-settings',array('label_for'=>'link-category-portfolio'));

		
		//-----------------
		
		// Portfolio category - remove option
		add_settings_section('portfolder-portfolio-settings','Remove A Portfolio Category',array($this, 'print_portfolio_remove_info'),'portfolder_portfolioremove_options');	
		add_settings_field('remove-portfolio-portfolder', '', array($this, 'create_portfolio_remove_field'), 'portfolder_portfolioremove_options','portfolder-portfolio-settings');
		
		
		//-------------------------------------------------------------------------------------------------


		// REGISTER PORTFOLDER SETTINGS


		// home page setup - options
		register_setting('portfolder_home_options', 'portfolder_options', 
			array(&$this, 'plugin_options_home_validate')); // <-- change 'check_portfolder_force' to 'options_input_validate' to debug
			
		// home page setup - options
		register_setting('portfolder_force_options', 'portfolder_options', 
			array(&$this, 'plugin_options_home_validate')); 
		
		// featurd on home setup - options
		register_setting('portfolder_featured_options', 'portfolder_featured_options', 
			array(&$this, 'plugin_featured_options_validate'));

		// feature remove - options
		register_setting('portfolder_featureremove_options', 'portfolder_featured_options', 
			array(&$this, 'plugin_featured_options_validate'));
		

		// portfolio setup - options
		register_setting('portfolder_portfolio_options', 'portfolder_portfolio_options', 
			array(&$this, 'plugin_portfolio_options_validate'));


		// feature remove - options
		register_setting('portfolder_portfolioremove_options', 'portfolder_portfolio_options', 
			array(&$this, 'plugin_portfolio_options_validate'));



		//-------------------------------------------------------------------------------------------------

	}
	
	public function options_input_validate($input){
	   
	    wp_die( '<pre>' . var_export($input,true) . '</pre>' );
	
	}
	
	// home page options validations
	
	public function plugin_options_home_validate($input) {
	
		// ALL FIELDS REQUIRED ( except PortFolder Force )
		
		if (is_array($input)){
		
			// home title text
			$options['portfolder-hometitle'] = trim($input['hometitle-portfolder']);
			
			// home blurb text
			$options['portfolder-homeblurb'] = trim($input['homeblurb-portfolder']);
			
			// home image patch
			$options['portfolder-homeimage'] = trim($input['homeimage-portfolder']);

			// home image caption
			$options['portfolder-homeimagecaption'] = trim($input['homeimagecaption-portfolder']);

			// ----------------------------------------------------------------------------------

			// force on home
			if (isset($input['force-portfolder']) && ($input['force-portfolder'] === '1')){
				 
				$options['portfolder-force'] = trim($input['force-portfolder']);
			
			} else { 
			
				$options['portfolder-force'] = '';
			
			}


			if (is_array($options) or (!empty($options) or $options !== ''))
	
				return $options;


		} else 
			
			return false;
		
	}




	// featured category settings option

	public function plugin_featured_options_validate($input) {
		
		// DEBUG LINE
		//die(var_export($input,true));
		
		// get Options if we have them
		$portfolder_options = get_option("portfolder_featured_options");


		if (isset($input['remove-category-featured']) && is_numeric($input['remove-category-featured'])){
		
			$input = $input['remove-category-featured'];
			
			foreach ($portfolder_options as $key => $option){
				
				$options[$key] = $option;
	
				unset($options[$key][$input]);
			
			}
				
		}
		

		elseif ((trim($input['title-category-featured']) !== '') && (trim($input['desc-category-featured']) !== '') && (trim($input['image-category-featured']) !== '') && (trim($input['link-category-featured']) !== '')) {

			
			// debug line
			//die("NEW ARRAY");

			
			add_option("portfolder_featured_options","");
			
			
			// check for options array
			if (isset($portfolder_options['featured-category-title']) && isset($portfolder_options['featured-category-desc']) && isset($portfolder_options['featured-category-image']) && isset($portfolder_options['featured-category-link'])){

				// we have options - get the array
				foreach ($portfolder_options as $key => $option)
						$options[$key] = $option;


				// PORTFOLIO CATEGORY TITLES
				
				$oldarray1 = (isset($options['featured-category-title']) ? $options['featured-category-title'] : array());
				
				$newinput1 = trim($input['title-category-featured']);
	
				array_push($oldarray1,$newinput1);
	
				$options['featured-category-title'] = $oldarray1;
	
	
	
				// PORTFOLIO CATEGORY DESCRIPTION
				
				$oldarray2 = (isset($options['featured-category-desc']) ? $options['featured-category-desc'] : array());
				
				$newinput2 = trim($input['desc-category-featured']);
				
				array_push($oldarray2,$newinput2);
				
				$options['featured-category-desc'] = $oldarray2;
				
				
				
				
				// PORTFOLIO CATEGORY IMAGE
				
				$oldarray3 = (isset($options['featured-category-image']) ? $options['featured-category-image'] : array());
				
				$newinput3 = trim($input['image-category-featured']);
	
				array_push($oldarray3,$newinput3);
	
				$options['featured-category-image'] = $oldarray3;
				
				
				
				// PORTFOLIO CATEGORY LINK
				
				$oldarray4 = (isset($options['featured-category-link']) ? $options['featured-category-link'] : array());
				
				$newinput4 = trim($input['link-category-featured']);
	
				array_push($oldarray4,$newinput4);
	
				$options['featured-category-link'] = $oldarray4;


			} else {


				// debug line
				//die("NEW");


				$option1 = trim($input['title-category-featured']);
				$option2 = trim($input['desc-category-featured']);
				$option3 = trim($input['image-category-featured']);
				$option4 = trim($input['link-category-featured']);

				$options = array('featured-category-title' => array( '0' => $option1) , 'featured-category-desc' => array( '0' => $option2), 'featured-category-image' => array( '0' => $option3), 'featured-category-link' => array( '0' => $option4) );
				
				
			}


		} else {

			$options = $portfolder_options;
		
			// debug line
			//die(var_export($options,true));
			
		}
		
		return $options;

	
	}


	// portfolio category settings option

	public function plugin_portfolio_options_validate($input) {

		// get Options if we have them
		$portfolder_options = get_option("portfolder_portfolio_options");
		
		if (isset($input['remove-category-portfolio']) && is_numeric($input['remove-category-portfolio'])){

			$input = $input['remove-category-portfolio'];
			
			foreach ($portfolder_options as $key => $option){
				
				$options[$key] = $option;
	
				unset($options[$key][$input]);
			
			}
  
		
		}
		
		else if (($input['title-category-portfolio'] !== "") && ($input['desc-category-portfolio'] !== "") && ($input['image-category-portfolio'] !== "") && ($input['link-category-portfolio']) !== "") {
		
			add_option("portfolder_portfolio_options","");
		
			// check for options array
			if (isset($portfolder_options['portfolio-category-title']) && isset($portfolder_options['portfolio-category-desc']) && isset($portfolder_options['portfolio-category-image']) && isset($portfolder_options['portfolio-category-link'])){
		
				
				// we have options - get the array
				foreach ($portfolder_options as $key => $option)
					$options[$key] = $option;
	
	
				// PORTFOLIO CATEGORY TITLES
				
				$oldarray1 = (isset($options['portfolio-category-title']) ? $options['portfolio-category-title'] : array());
				
				$newinput1 = trim($input['title-category-portfolio']);
				
				array_push($oldarray1,$newinput1);
				
				$options['portfolio-category-title'] = $oldarray1;
	
	
	
	
				// PORTFOLIO CATEGORY DESCRIPTION
				
				$oldarray2 = (isset($options['portfolio-category-desc']) ? $options['portfolio-category-desc'] : array());
				
				$newinput2 = trim($input['desc-category-portfolio']);
				
				array_push($oldarray2,$newinput2);
				
				$options['portfolio-category-desc'] = $oldarray2;
				
				
				
				// PORTFOLIO CATEGORY IMAGE
				
				$oldarray3 = (isset($options['portfolio-category-image']) ? $options['portfolio-category-image'] : array());
				
				$newinput3 = trim($input['image-category-portfolio']);
				
				array_push($oldarray3,$newinput3);
				
				$options['portfolio-category-image'] = $oldarray3;
				
				
				
				
				// PORTFOLIO CATEGORY IMAGE
				
				$oldarray4 = (isset($options['portfolio-category-link']) ? $options['portfolio-category-link'] : array());
				
				$newinput4 = trim($input['link-category-portfolio']);
				
				array_push($oldarray4,$newinput4);
				
				$options['portfolio-category-link'] = $oldarray4;



			} 
			
			else {


				$option1 = trim($input['title-category-portfolio']);
				$option2 = trim($input['desc-category-portfolio']);
				$option3 = trim($input['image-category-portfolio']);
				$option4 = trim($input['link-category-portfolio']);

				$options = array('portfolio-category-title' => array( '0' => $option1) , 'portfolio-category-desc' => array( '0' => $option2), 'portfolio-category-image' => array( '0' => $option3), 'portfolio-category-link' => array( '0' => $option4) );
				
				
			}


		}
		
		else {

			$options = $portfolder_options;
		
			// debug line
			//die(var_export($options,true));
			
		}
		
		return $options;
	
	}
	
	
	
	public  function print_hometitle_info(){ 
	
		echo 'The home page title and text blurb for your site.'; 
	
	}

	public   function create_hometitle_field(){ 
	
		$portfolder_options = get_option("portfolder_options");
	
		if (!empty($portfolder_options)) {
			foreach ($portfolder_options as $key => $option)
				$options[$key] = $option;
		}
	
		echo '<input type="text" id="hometitle-portfolder" name="portfolder_options[hometitle-portfolder]" size="70" value="' . (isset($options['portfolder-hometitle']) ? $options['portfolder-hometitle'] : '') . '" />';
	}


	public function print_homeblurb_info(){ 
		
		echo ''; 
		
	}

	public function create_homeblurb_field(){ 
		
		$portfolder_options = get_option("portfolder_options");
	
		if (!empty($portfolder_options)) {
			foreach ($portfolder_options as $key => $option)
				$options[$key] = $option;
		}
		
		?> 
        <textarea name="portfolder_options[homeblurb-portfolder]" cols="100" rows="9" id="homeblurb-portfolder"/><?php echo (isset($options['portfolder-homeblurb']) ? $options['portfolder-homeblurb'] : '') ?></textarea>
		<?php 
		
	
	}


	public function print_homeimage_info(){ 
	
		echo 'The home page featured image (set path for single image only)'; 
	
	}

	public function create_homeimage_field(){ 
	
		$portfolder_options = get_option("portfolder_options");
	
		if (!empty($portfolder_options)) {
			foreach ($portfolder_options as $key => $option)
				$options[$key] = $option;
		}
		
		?> 
      <?php  if (isset($options['portfolder-homeimage']))
			echo ($options['portfolder-homeimage'] ? '<img src="'.$options['portfolder-homeimage'] . '" alt="" />' : '' ) 
	?>
        	
            <br />
            
       		<input type="text" id="homeimage-portfolder" name="portfolder_options[homeimage-portfolder]" size="80" value="<?php echo (isset($options['portfolder-homeimage']) ? $options['portfolder-homeimage'] : '') ?>" />
	
		<?php 
	
	}
	
	public function print_homeimagecaption_info(){ 
	
		echo 'Home page featured image caption text'; 
	
	}

	public function create_homeimagecaption_field(){ 
	
		$portfolder_options = get_option("portfolder_options");
	
		if (!empty($portfolder_options)) {
			foreach ($portfolder_options as $key => $option)
				$options[$key] = $option;
		}
		
		?> 
       		<input type="text" id="homeimagecaption-portfolder" name="portfolder_options[homeimagecaption-portfolder]" size="80" value="<?php echo (isset($options['portfolder-homeimagecaption']) ? $options['portfolder-homeimagecaption'] : '') ?>" />
	
		<?php 
	
	}


    public function print_force_section_info(){ 
	
		echo 'If you are not using one of <a href="http://www.netfunkdesign.com" target="_blank">Netfunk\'s</a> themes you may try and attempt to force <b>PortFOLDER</b> onto your home page (no theme editing required)'; 
	
	}
	
    public function create_force_checkbox(){ 
	
		$portfolder_options = get_option("portfolder_options");
	
		if (!empty($portfolder_options)) {
			foreach ($portfolder_options as $key => $option)
				$options[$key] = $option;
		}
	
		echo '<input type="checkbox" id="force-portfolder" name="portfolder_options[force-portfolder]" value="1" '. checked( 1, (isset($options['portfolder-force']) ? $options['portfolder-force'] : ''), false ) .' />' ;
	
	}

	
	/*//////////////////////////////////////////////////////////////////////////*/


	// portfolder - featured category info and fields

	public function print_featured_cattitle_info(){ 

		echo 'Create a featured category group. You may have up to 4 categories.<br />Provide a description for this featured category.<br />Provide an image for this category (215x130)'; 
	
	}
	
    public function create_featured_cattitle_field(){ 

		echo '<input type="text" id="title-category-featured" name="portfolder_featured_options[title-category-featured]" size="40" value=""/>' ;
	
	}
	
	public function print_featured_catdesc_info(){ 

		echo '<br />Provide a description for this featured category'; 
	
	}
	
	 public function create_featured_catdesc_textarea(){ 

		echo '<textarea name="portfolder_featured_options[desc-category-featured]" cols="80" rows="7" id="desc-category-featured"/></textarea>' ;
	
	}
	
	
	public function print_featured_catimage_info(){ 

		echo '<br />Provide an image for this category (215x130)'; 
	
	}
	
	 public function create_featured_catimage_field(){ 

		echo '<input type="text" name="portfolder_featured_options[image-category-featured]" size="90" id="image-category-featured"/>' ;
	
	}
	
	
	public function print_featured_catlink_info(){ 

		echo '<br />Provide the link to your featured area'; 
	
	}
	
	public function create_featured_catlink_field(){ 

		echo '<input type="text" name="portfolder_featured_options[link-category-featured]" size="90" id="link-category-featured"/>' ;
	
	}


	// REMOVE freatured category
	
	public function print_featured_remove_info(){ 
	
		echo "<br /><span class=\"option_warning\"><strong>Warning:</strong> Are you sure you want to remove this featured category?</span>";
	
	}
	
	 public function  create_featured_remove_field(){ 
	
		echo '<input type="hidden" name="portfolder_featured_options[remove-category-featured]" id="remove-featured-portfolder" value="'.$this->getID().'">';
	

	}


	/*//////////////////////////////////////////////////////////////////////////*/



	// portfolder - portfilo setting info info and fields

	public function print_portfolio_cattitle_info(){ 
	
		echo 'Create a portfolio category group. You may have up to 4 categories.<br />Provide a description for this portfolio category.<br />Provide an image for this category (215x130)'; 
	
	}
	
    public function create_portfolio_cattitle_field(){ 
	
		echo '<input type="text" id="title-category-portfolio" name="portfolder_portfolio_options[title-category-portfolio]" size="40" value=""/>' ;
	
	}
	
	public function print_portfolio_catdesc_info(){ 
	
		echo '<br />Provide a description for this portfolio category'; 
	
	}
	
	 public function create_portfolio_catdesc_textarea(){ 
	
		echo '<textarea name="portfolder_portfolio_options[desc-category-portfolio]" cols="80" rows="7" id="desc-category-portfolio"/></textarea>' ;
	
	}
	
	
	public function print_portfolio_catimage_info(){ 
	
		echo '<br />Provide an image for this category (215x130)'; 
	
	}
	
	 public function create_portfolio_catimage_field(){ 
	
		echo '<input type="text" name="portfolder_portfolio_options[image-category-portfolio]" size="90" id="image-category-portfolio"/>' ;
	
	}
	
	
	public function print_portfolio_catlink_info(){ 
	
		echo '<br />Provide an image for this category (215x130)'; 
	
	}
	
	 public function create_portfolio_catlink_field(){ 
	
		echo '<input type="text" name="portfolder_portfolio_options[link-category-portfolio]" size="90" id="link-category-portfolio"/>' ;
	
	}


	// REMOVE portfolio category
	
	public function print_portfolio_remove_info(){ 
	
		echo "<br /><span class=\"option_warning\"><strong>Warning:</strong> Are you sure you want to remove this portfolio category?</span>";
	
	}
	
	 public function  create_portfolio_remove_field(){ 
	
		echo '<input type="hidden" name="portfolder_portfolio_options[remove-category-portfolio]" id="remove-portfolio-portfolder" value="'.$this->getID().'">';
	

	}
	

	/*//////////////////////////////////////////////////////////////////////////*/


    public function LoadTextDomain() {
	
        $currentLocale = get_locale();

        if(!empty($currentLocale)) {

            $moFile = $this->pluginUrl."/lang/".$currentLocale.".mo";

            if(@file_exists($moFile) && is_readable($moFile))

                load_textdomain('portfolder', $moFile);

        }

    }
	

	public function portfolder_nav_menu() {
	
		?>
        
        <div class="portfolder-nav">
            
                <ul>
                
                    <li> <a href="<?php echo $_SERVER['PHP_SELF']. "?page=".$_REQUEST['page'] ?>"<?php echo (!isset($_REQUEST['option_page']) ? ' class="portfolder_settings_current"' : '')?>> Home Page Setup </a> </li>
                    
                    <li> <a href="<?php echo $_SERVER['PHP_SELF']. "?page=".$_REQUEST['page']."&option_page=featured" ?>"<?php echo (isset($_REQUEST['option_page']) ? ($_REQUEST['option_page'] == 'featured' ? ' class="portfolder_settings_current"' : '') : '')?>> Featured On Home </a> </li>
                    
                    <li> <a href="<?php echo $_SERVER['PHP_SELF']. "?page=".$_REQUEST['page']."&option_page=portfolio" ?>"<?php echo (isset($_REQUEST['option_page']) ? ($_REQUEST['option_page'] == 'portfolio' ? ' class="portfolder_settings_current"' : '') : '')?>> Portfolio Setup </a> </li>
                    
                    
                    <li> <a href="<?php echo $_SERVER['PHP_SELF']. "?page=".$_REQUEST['page']."&option_page=customize" ?>"> Custom CSS </a> </li>
                    
                    <li> <a href="<?php echo $_SERVER['PHP_SELF']. "?page=".$_REQUEST['page']."&option_page=help" ?>"> Herlp!  </a> </li>
                
                </ul>
            
            </div>
            
       <?php	
		
		
	}
	
	
	
	// Get Page Option frmo URi

	public function getOptionPage(){
	
		if (isset($_REQUEST['option_page']) && !empty($_REQUEST['option_page'])){
			$this->optionPage = $_REQUEST['option_page'];
			return $_REQUEST['option_page'];
		} 
		
		else
			return false;
	}
	
	
	// Get Page Option frmo URi

	public function getAction(){
	
		if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
			$this->action = $_REQUEST['action'];
			return $_REQUEST['action'];
		} 
		
		else
			return false;
	}
	
	public function getID(){
	
		if (isset($_REQUEST['sid']) && !empty($_REQUEST['sid'])){
			$this->sid = $_REQUEST['sid'];
			return $_REQUEST['sid'];
		} 
		
		else
			return false;
	}
	
	
	// remove featured category - function
	
	public function deleteFromArray($input){ 														// $sid  =  admin page option group [ option id ] ( array( array( 'sid' => 'option' ) )

			
		// loop through the featured-options
	
		settings_fields('portfolder_featureremove_options');	
		do_settings_sections('portfolder_featureremove_options');
		

	}



	// remove portfolio category - function
	
	public function delete_portfolio_category($input){ 

			
		// loop through the featured-options
	
		settings_fields('portfolder_portfolioremove_options');	
		do_settings_sections('portfolder_portfolioremove_options');
		

	}


	
	
	// PortFOLDER - Homepage Setup Page

    public function PortFolder_Settings_Page() {


?>

    <div class="wrap">
    
    <div class="updated" id="portfolder-update"></div>
    
        <div id="icon-tools" class="icon32"><br /></div>
    
        <h2><?php _e('PortFolder'); ?></h2>

    		<?php $this->portfolder_nav_menu() ?>
    
            <div class="portfolder-admin-content">
    
                <form id="portfolder-form" action="options.php" method="post">
    
                <ul class="portfolder-form-controlls">


					<?php // BEGIN HOMEPAGE SETTING PAGE
					
						if (!$this->getOptionPage()):

					?>

						<p style="padding: 20px; color: #777; border: 1px #09C solid; border-radius: 4px; font-size: 14px; width: 1040px;">
                                    
                            <strong style="color: #09C; margin-right: 10px;">Tip:</strong> Setup your home page welcome messaage and image slider (<span style="color: #444;">Both optional and <b><u>Off</u></b> by default</span>)
                        
                        </p>

					 <li>

						<?php 

						// This prints out all hidden setting fields
						
						settings_fields('portfolder_hometitle_options');	
						do_settings_sections('portfolder_hometitle_options');
						
						
						settings_fields('portfolder_homeblurb_options');	
						do_settings_sections('portfolder_homeblurb_options');
						
						?>
                        
                    </li>
                        
                        
                    <li>
                        
                        <?php 
						
						settings_fields('portfolder_homeimage_options');	
						do_settings_sections('portfolder_homeimage_options');
						
						settings_fields('portfolder_homeimagecap_options');	
						do_settings_sections('portfolder_homeimagecap_options');
					
						?>

                    </li>
				
                <br />
                
                    <li>

					<?php // This prints out all hidden setting fields
						
						settings_fields('portfolder_force_options');	
						do_settings_sections('portfolder_force_options');
					?>
					
                    </li>
                    
                    <?php // BEGIN PORTFOLIO SETTING PAGE

						elseif ($this->getOptionPage() && $this->getOptionPage() == "featured"):

                    ?>
                    
                     <li>

                        <h3>Featured Categories</h3>

                    	<?php 
								
							$portfolder_options = get_option("portfolder_featured_options");
		
							if (!empty($portfolder_options)) {
                    
									if (!empty($portfolder_options)) {
										foreach ($portfolder_options as $key => $option)
											$options[$key] = $option;
								
									} 
								
								
							?>
                                
                                <p style="padding: 20px; color: #777; border: 1px #09C solid; border-radius: 4px; font-size: 14px; width: 1040px;">
                                    
                                    <strong style="color: #09C; margin-right: 10px;">Tip:</strong> Sort featured categories by dragging them to a new position.
                                
                                </p>
                                
                                <div class="row twelve" id="features_container">
                    
                    			<h3>Preview Categories: <span> <a href="#new-category" class="button button-primary">Add New Category</a> </span></h3>
                            
                            	<ul id="sortable-categories">
                            
                            <?php
								
								$count = count($options['featured-category-image']);
								
								$n = 0;

								for ($i = 1; $i <= $count; $i++) { ?>
				
                					
                                    
                                        <li>
                                        
                                        	<input type="hidden" name="featured-category-id" id="featured-category-id" value="<?php echo $n ?>" />
                    
                                            <div class="columns admin-edit">
                                            
                                                <div class="admin-edit-panel"><a href="#">Modify</a> | <a href="<?php echo $_SERVER['PHP_SELF']."?page=".$_REQUEST['page']."&option_page=remove-feature-category&sid=".$n."";  ?>">Remove</a></div>
                                    
                                                <div class="feature-head">
                                    
                                                <img src="<?php echo ($options['featured-category-image'][$n] ? $options['featured-category-image'][$n] : $this->pluginUrl . '/img/feature-bg.png') ?>" alt="" border="0" />
                                    
                                                </div> <!--box-head close-->
                                    
                                    
                                            <div class="title-box">						
                                    
                                            <div class="title-head"><h1><?php echo ($options['featured-category-title'][$n] ? $options['featured-category-title'][$n] : 'Featured Title') ?></h1></div></div>
                                    
                                    
                                                <div class="feature-content">
                                    
                                                <?php echo ($options['featured-category-title'][$n] ? $options['featured-category-desc'][$n] : 'Nullam posuere felis a lacus tempor eget dignissim arcu adipiscing. Donec est est, rutrum vitae bibendum vel, suscipit non metus.') ?>
                                    
                                    
                                                </div> <!--box-content close-->
                                    
                                            <span class="read-more"><a href="<?php echo ($options['featured-category-link'][$n] ? $options['featured-category-link'][$n] : '#') ?>" target="_blank"><?php _e('Read More'); ?></a></span>
                                
                                
                                        </div><!--boxes  end-->
    
    
                                        </li>
                                    
                                   
							
								<?php 
								
								 $n++;
								
								} 
								
								?>
                                
                                 </ul>
                            
                                <div class="clear"></div>
                            
                            </div>
  
                    
						  <?php 
							
								
							} else {
								
								echo '<p style="padding: 5px; color: #F20"> <strong>No featured categories have been created yet!</strong> <a href="#new-category" class="button button-primary" style="margin-left: 80px;">Add New Category</a></p>';
								
								$this->pluginUrl = WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__));
								
								 $img = $this->pluginUrl."/img/help1.jpg";
								
									echo '<img src="'.$img.'" title="The category editor window sample view">';
								
								?>
                                
                                	<p style="padding: 10px; color: #555; border: 1px #09C solid; border-radius: 4px; font-size: 12px; width: 480px;">
                                    
                                    <strong style="color: #09C; margin-right: 10px;">Tip:</strong> Create a featured cateogry to point to any section in your website. Ater adding a new cateroy you will be promted with the category editor window.
                                    
                                    </p>

								
								<?php
								
							}
						
						?>

                    </li>

                    
                    <li>
					
                    	<a name="new-category"></a>
                    
                    <?php
                    
                    	// This prints out all hidden setting fields
						
						settings_fields('portfolder_featured_options');	
						do_settings_sections('portfolder_featured_options');

					?>

					 </li>


					<?php // BEGIN PORTFOLIO SETTING PAGE

						elseif ($this->getOptionPage() && $this->getOptionPage() == "portfolio"):

                    ?>
                    
                     <li>

                        <h3>Portfolio Categories</h3>


						<?php 

							$portfolder_options = get_option("portfolder_portfolio_options");
		
							if (!empty($portfolder_options)) {
								
								foreach ($portfolder_options as $key => $option){
								
									$options[$key] = $option;
								
								}
								
							?>
                            
                            	<p style="padding: 20px; color: #777; border: 1px #09C solid; border-radius: 4px; font-size: 14px; width: 1040px;">
                                    
                                    <strong style="color: #09C; margin-right: 10px;">Tip:</strong> Sort portfolio categories by dragging them to a new position.
                                
                               </p>
                        
                        		<div class="row twelve" id="features_container">
                    
                    			<h3>Preview Categories: <span> <a href="#new-category" class="button button-primary">Add New Category</a> </span> </h3>
                            
                            
                            	<ul id="sortable-categories">
                            
                            <?php
								
								$count = count($options['portfolio-category-image']);
								
								$n = 0;

								for ($i = 1; $i <= $count; $i++) { ?>
				
                					<li>
                                    
                                    <input type="hidden" name="featured-portfolio-id" id="featured-category-id" value="<?php echo $n ?>" />
                
									<div class="columns admin-edit">
                                    
                                    	<div class="admin-edit-panel"><a href="#">Modify</a> | <a href="<?php echo $_SERVER['PHP_SELF']."?page=".$_REQUEST['page']."&option_page=remove-portfolio-category&sid=".$n."";  ?>">Remove</a></div>
							
										<div class="feature-head">
							
										<img src="<?php echo ($options['portfolio-category-image'][$n] ? $options['portfolio-category-image'][$n] : $this->pluginUrl . '/img/feature-bg.png') ?>" alt="" border="0" />
							
										</div> <!--box-head close-->
							
							
									<div class="title-box">						
							
									<div class="title-head"><h1><?php echo ($options['portfolio-category-title'][$n] ? $options['portfolio-category-title'][$n] : 'Portfolio Title') ?></h1></div></div>
							
							
										<div class="feature-content">
							
										<?php echo ($options['portfolio-category-title'][$n] ? $options['portfolio-category-desc'][$n] : 'Nullam posuere felis a lacus tempor eget dignissim arcu adipiscing. Donec est est, rutrum vitae bibendum vel, suscipit non metus.') ?>
							
							
										</div> <!--box-content close-->
							
									<span class="read-more"><a href="<?php echo ($options['portfolio-category-link'][$n] ? $options['portfolio-category-link'][$n] : '#') ?>" target="_blank"><?php _e('Read More'); ?></a></span>
							
							
									</div><!--boxes  end-->


									</li>
							
								<?php 
								
								 $n++;
								
								} 
								
								?>
                            
                            	</ul>
                            
                                <div class="clear"></div>
                            
                            </div>
								
							  <?php	
                                
							} else {
								
								echo 'No portfolio categories have been created yet';
								
							}
						
						?>
                    
                    </li>
                    
                    <li>
						
                        <a name="new-category"></a>
                        					
                    <?php
                    
                    	// This prints out all hidden setting fields
						
						settings_fields('portfolder_portfolio_options');	
						do_settings_sections('portfolder_portfolio_options');

					?>

					 </li>

					
                    <?php 
					
						// REMOVE A CATEGORY ITEM - deiplay notice // click back
					
						elseif ($this->getOptionPage() == "remove-feature-category" && $this->getID() !== false): 
                    
                    ?>
                    
                   <li>
    
                        <?php  $this->deleteFromArray($this->getID());  ?>


					</li>
                    
                    
                    <?php 
					
						// REMOVE A CATEGORY ITEM - deiplay notice // click back
					
						elseif ($this->getOptionPage() == "remove-portfolio-category" && $this->getID() !== false): 
                    
                    ?>
                    
                   <li>

                        <?php  $this->delete_portfolio_category($this->getID());  ?>


					</li>
                    
                    
					<?php endif; ?>

                </ul>

				<?php 
				
				/* submit_button( $text, $type, $name, $wrap, $other_attributes ) */
				
				if ($this->getID())
					
					$text = "Remove Category";
				
				else if ($this->getOptionPage() == 'featured' or $this->getOptionPage() == 'portfolio')
					
					$text = "Add Category";

				else 
					
					$text = "Save Changes";
					
				
				submit_button($text); 
				
				
				
				/* add a cancel button for category - remove pages */
				
				
				//submit_button('cancel'); 
				
				
				
				
				?>
                
                 <!--input type="hidden" name="action" value="update_form" /-->

            </form>

        </div>

	</div>

<?php

    }

    // DisplayPortfolderHome short code display type 
    function portfolder_main_code($atts){
		//Display Portfolder Content
		return $this->DisplayPortfolderHome();
	}

	function DisplayPortfolderHome(){ ?>
    
		<div id="portfolder_container">
    
        <div class="row">
    
            <div class="four columns">
    
    			<?php $portfolder_options = get_option("portfolder_options");
	
					if (!empty($portfolder_options)) {
						foreach ($portfolder_options as $key => $option)
							$options[$key] = $option;
				
					} 
				?>
    
                <h1><?php echo ($options['portfolder-hometitle'] ? $options['portfolder-hometitle'] : "please setup now") ?></h1>
    
                <p><?php echo ($options['portfolder-homeblurb'] ? $options['portfolder-homeblurb'] : "please setup now" ) ?></p>
    
            </div>	
        
            <div class="eight columns">

                <!--slideshow-->

                <ul data-orbit>
                
                <?php $portfolder_options = get_option("portfolder_options");
                
                        if (!empty($portfolder_options)) {
                            foreach ($portfolder_options as $key => $option)
                                $options[$key] = $option;
                    
                        }
                
                ?>
                
                 <?php for ($i = 1; $i <= 1; $i++) { ?>

                <li>
    
                    <a href=""><img src="<?php echo ($options['portfolder-homeimage'] ? $options['portfolder-homeimage'] : $this->pluginUrl . "/img/portfold1.png" ) ?>" alt="" /></a>

                    <div class="orbit-caption"><?php echo ($options['portfolder-homeimagecaption'] ? $options['portfolder-homeimagecaption'] : "please setup now" ) ?></div>

                </li>

                <?php } ?>
                
                </ul>
                

                <div class="flexslider">
                
            
                    <ul class="slides">
            

                    <?php $portfolder_options = get_option("portfolder_options");
            
                            if (!empty($portfolder_options)) {
                                foreach ($portfolder_options as $key => $option)
                                    $options[$key] = $option;
                        
                            } 
                     ?>

            
                    <?php for ($i = 1; $i <= 1; $i++) { ?>

                        <li>
            
                            <a href=""><img src="<?php echo ($options['portfolder-homeimage'] ? $options['portfolder-homeimage'] : $this->pluginUrl . "/img/portfold1.png" ) ?>" alt="" /></a>
            
                         
            
                            <p class="flexslider-caption"><?php echo ($options['portfolder-homeimagecaption'] ? $options['portfolder-homeimagecaption'] : "please setup now" ) ?></p>
            
                         
            
                        </li>
            
                        
            
                        <?php } ?>

            
                    </ul>
                        
                
               </div><!--portfolder container end-->
                
                
                  
            <div class="clear"></div>	
    
    
                
              </div>
            
    
        </div>
    
    </div>
    
    
    <?php 
				
	/*BEGIN FEATURED ON HOME BOXES*/ 
	
	?>
   
		<!-- home boxes -->
		

		<?php if(is_front_page()) { ?>
		
        <!--#e3e7e8-->
        
		<div id="features_container">
			
			<div class="row">
            
            <?php $portfolder_options = get_option("portfolder_featured_options");
                    
					if (!empty($portfolder_options)) {
						foreach ($portfolder_options as $key => $option)
							$options[$key] = $option;
				
					} 
				
				
				$n = 0;
				
				
				for ($i = 1; $i <= 4; $i++) { ?>

					<div class="three columns">
			
						<div class="feature-head">
			
						<a href="<?php echo ($options['featured-category-link'][$n] ? $options['featured-category-link'][$n] : '#') ?>"><img src="<?php echo ($options['featured-category-image'][$n] ? $options['featured-category-image'][$n] : $this->pluginUrl . '/img/feature-bg.png') ?>" alt="" /></a>
			
						</div> <!--box-head close-->
			
			
					<div class="title-box">						
			
					<div class="title-head"><h1><?php echo ($options['featured-category-title'][$n] ? $options['featured-category-title'][$n] : 'Featured Title') ?></h1></div></div>
			
			
						<div class="feature-content">
			
            			<?php echo ($options['featured-category-title'][$n] ? $options['featured-category-desc'][$n] : 'Nullam posuere felis a lacus tempor eget dignissim arcu adipiscing. Donec est est, rutrum vitae bibendum vel, suscipit non metus.') ?>
            
			
						</div> <!--box-content close-->
			
					<span class="read-more"><a href="<?php echo ($options['featured-category-link'][$n] ? $options['featured-category-link'][$n] : '#') ?>"><?php _e('Read More'); ?></a></span>
			
			
					</div><!--boxes  end-->
			
			
				<?php 
				
				 $n++;
				
				} 
				
				?>


			</div>

			<!-- home boxes end -->

		</div>
            
            
	<?php } ?> 


	<?php 
	
	/*END FEATURED ON HOME BOXES*/ 
	
	?>
    
    
	
<?php }

function DisplayPortfolderPortfolio(){ 

				
	/*BEGIN PORTFOLIO BOXES*/ 


?>
   
		<!-- home boxes -->

		
        <!--#e3e7e8-->
        
		<div id="features_container">
			
			
            <div class="row">
            
            
            <?php $portfolder_options = get_option("portfolder_portfolio_options");
                    
					if (!empty($portfolder_options)) {
						foreach ($portfolder_options as $key => $option)
							$options[$key] = $option;
				
					} 
				
				
				$n = 0;
				
				$count = count($options['portfolio-category-image']);
				
				for ($i = 1; $i <= $count; $i++) { ?>

					<div class="three columns">
			
						<div class="feature-head">
			
						<a href="<?php echo ($options['portfolio-category-link'][$n] ? $options['portfolio-category-link'][$n] : '#') ?>"><img src="<?php echo ($options['portfolio-category-image'][$n] ? $options['portfolio-category-image'][$n] : $this->pluginUrl . '/img/feature-bg.png') ?>" alt="" /></a>
			
						</div> <!--box-head close-->
			
			
					<div class="title-box">						
			
					<div class="title-head"><h1><?php echo ($options['portfolio-category-title'][$n] ? $options['portfolio-category-title'][$n] : 'Portfolio Title') ?></h1></div></div>
			
			
						<div class="feature-content">
			
            			<?php echo ($options['portfolio-category-title'][$n] ? $options['portfolio-category-desc'][$n] : 'Nullam posuere felis a lacus tempor eget dignissim arcu adipiscing. Donec est est, rutrum vitae bibendum vel, suscipit non metus.') ?>
            
			
						</div> <!--box-content close-->
			
					<span class="read-more"><a href="<?php echo ($options['portfolio-category-link'][$n] ? $options['portfolio-category-link'][$n] : '#') ?>"><?php _e('Read More'); ?></a></span>
			
			
					</div><!--boxes  end-->
			
			
				<?php 
				
				 $n++;
				
				} 
				
				?>


		</div>
				

	</div>

	<!-- home boxes end -->
			
			

	<?php 
	
	/*END PORTFOLIO BOXES*/ 
	
	}

}

$portfolder = new PortFolder();

