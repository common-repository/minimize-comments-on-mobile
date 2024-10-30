<?php
/* 
Plugin Name: Minimize Comments on Mobile 
Plugin URI: http://vileworks.com/minimize-comments-on-mobile
Description: Minimizes comments on mobile devices.
Version: 1.0 
Author: Stefan Matei
Author URI: http://vileworks.com 
*/  

// ------------------------------------------------------------------------
// PLUGIN PREFIX:                                                          
// ------------------------------------------------------------------------
// A PREFIX IS USED TO AVOID CONFLICTS WITH EXISTING PLUGIN FUNCTION NAMES.
// WHEN CREATING A NEW PLUGIN, CHANGE THE PREFIX AND USE YOUR TEXT EDITORS 
// SEARCH/REPLACE FUNCTION TO RENAME THEM ALL QUICKLY.
// ------------------------------------------------------------------------

// 'mom_' prefix is derived from [m]inimize [o]n [m]obile

// ------------------------------------------------------------------------
// REGISTER HOOKS & CALLBACK FUNCTIONS:
// ------------------------------------------------------------------------
// HOOKS TO SETUP DEFAULT PLUGIN OPTIONS, HANDLE CLEAN-UP OF OPTIONS WHEN
// PLUGIN IS DEACTIVATED AND DELETED, INITIALISE PLUGIN, ADD OPTIONS PAGE.
// ------------------------------------------------------------------------

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'mom_add_defaults');
register_uninstall_hook(__FILE__, 'mom_delete_plugin_options');
add_action('admin_init', 'mom_init' );
add_action('admin_menu', 'mom_add_options_page');
add_filter( 'plugin_action_links', 'mom_plugin_action_links', 10, 2 );

// --------------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_uninstall_hook(__FILE__, 'mom_delete_plugin_options')
// --------------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE USER DEACTIVATES AND DELETES THE PLUGIN. IT SIMPLY DELETES
// THE PLUGIN OPTIONS DB ENTRY (WHICH IS AN ARRAY STORING ALL THE PLUGIN OPTIONS).
// --------------------------------------------------------------------------------------

// Delete options table entries ONLY when plugin deactivated AND deleted
function mom_delete_plugin_options() {
	delete_option('mom_options');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_activation_hook(__FILE__, 'mom_add_defaults')
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE PLUGIN IS ACTIVATED. IF THERE ARE NO THEME OPTIONS
// CURRENTLY SET, OR THE USER HAS SELECTED THE CHECKBOX TO RESET OPTIONS TO THEIR
// DEFAULTS THEN THE OPTIONS ARE SET/RESET.
//
// OTHERWISE, THE PLUGIN OPTIONS REMAIN UNCHANGED.
// ------------------------------------------------------------------------------

// Define default option settings
function mom_add_defaults() {
	$tmp = get_option('mom_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('mom_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(	'txt_width' => '600',
						'txt_trigger' => '#comments-title',
						'txt_contents' => '.commentlist'
		);
		update_option('mom_options', $arr);
	}
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_init', 'mom_init' )
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_init' HOOK FIRES, AND REGISTERS YOUR PLUGIN
// SETTING WITH THE WORDPRESS SETTINGS API. YOU WON'T BE ABLE TO USE THE SETTINGS
// API UNTIL YOU DO.
// ------------------------------------------------------------------------------

// Init plugin options to white list our options
function mom_init(){
	register_setting( 'mom_plugin_options', 'mom_options', 'mom_validate_options' );
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_menu', 'mom_add_options_page');
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_menu' HOOK FIRES, AND ADDS A NEW OPTIONS
// PAGE FOR YOUR PLUGIN TO THE SETTINGS MENU.
// ------------------------------------------------------------------------------

// Add menu page
function mom_add_options_page() {
	add_options_page('Minimize Comments on Mobile Options', 'Minimize Comments', 'manage_options', __FILE__, 'mom_render_form');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION SPECIFIED IN: add_options_page()
// ------------------------------------------------------------------------------
// THIS FUNCTION IS SPECIFIED IN add_options_page() AS THE CALLBACK FUNCTION THAT
// ACTUALLY RENDER THE PLUGIN OPTIONS FORM AS A SUB-MENU UNDER THE EXISTING
// SETTINGS ADMIN MENU.
// ------------------------------------------------------------------------------

// Render the Plugin options form
function mom_render_form() {
	?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Minimize Comments on Mobile: Options</h2>
		<p>The <strong>Trigger</strong> and <strong>Contents</strong> values below should be CSS selectors for the comments title (the trigger) and a wrapper that holds the comments (the contents).</p>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('mom_plugin_options'); ?>
			<?php $options = get_option('mom_options'); ?>

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			<table class="form-table">
				<tr>
					<th scope="row">Trigger:</th>
					<td>
						<input type="text" size="57" name="mom_options[txt_trigger]" value="<?php echo $options['txt_trigger']; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Contents:</th>
					<td>
						<input type="text" size="57" name="mom_options[txt_contents]" value="<?php echo $options['txt_contents']; ?>" />
					</td>
				</tr>
				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Maximum mobile screen width:</th>
					<td>
						<input type="text" size="5" name="mom_options[txt_width]" value="<?php echo $options['txt_width']; ?>" /> pixels.
						<br /><span style="color:#666666;margin-left:2px;">Devices with widder screens will not be considered "mobile."</span>
					</td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>

			<p>The comments are usually inside a list with a class of ".commentlist" so you probably won't need to change the option for the "contents".</p>
			<p>The comments title however may be different on your theme so you may have to change the <strong>"trigger"</strong> option to something different than the "#comments-title" id. <br />Use "Inspect element" on your post's page to deterime the correct class or id of the element you want to be the trigger.</p>
		</form>

		<p style="margin-top:15px;">
			<p style="font-weight: bold;color: #26779a;">This plugin was developed by <a href="http://stefanmatei.com" title="Stefan Matei">Stefan Matei</a> for <a href="http://www.vileworks.com">VileWorks.com</a>.</p>
			<span><a href="http://fb.me/VileWorks" title="Our Facebook page" target="_blank"><img style="border:1px #ccc solid;" src="<?php echo plugins_url(); ?>/minimize-comments-on-mobile/images/facebook-icon.png" /></a></span>
			&nbsp;&nbsp;<span><a href="http://twitter.com/VileWorks" title="Follow on Twitter" target="_blank"><img style="border:1px #ccc solid;" src="<?php echo plugins_url(); ?>/minimize-comments-on-mobile/images/twitter-icon.png" /></a></span>
		</p>

	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function mom_validate_options($input) {
	 // strip html from textboxes
	$input['txt_trigger'] =  wp_filter_nohtml_kses($input['txt_trigger']); // Sanitize textbox input (strip html tags, and escape characters)
	$input['txt_contents'] =  wp_filter_nohtml_kses($input['txt_contents']);
	$input['txt_width'] =  wp_filter_nohtml_kses($input['txt_width']);
	return $input;
}

// Display a Settings link on the main Plugins page
function mom_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$mom_links = '<a href="'.get_admin_url().'options-general.php?page=minimize-comments-on-mobile/minimize-comments-on-mobile.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $mom_links );
	}

	return $links;
}

// ------------------------------------------------------------------------------
// USAGE FUNCTIONS:
// ------------------------------------------------------------------------------
// THE FOLLOWING FUNCTIONS USE THE PLUGINS OPTIONS DEFINED ABOVE.
// ------------------------------------------------------------------------------


function mom_plugin_init() {
	if (!is_admin()) {
		wp_enqueue_script('jquery');

		add_action('wp_footer', 'mom_print_my_script');
	}
}
add_action('init', 'mom_plugin_init');

function mom_print_my_script() { $options = get_option('mom_options'); ?>

	<script type="text/javascript">
	jQuery(function ($) {
		maxwidth = <?php echo $options['txt_width']; ?>;
		if ( $(window).width() < maxwidth ) {
			$trigger = $("<?php echo $options['txt_trigger']; ?>");
			$contents = $("<?php echo $options['txt_contents']; ?>");

			$contents.hide();
			$trigger
			    .css({
			        'cursor':'pointer'
			    })
			    .click(function(){
			        $contents.slideToggle('slow')
			    })
		}
	});
	</script>

<?php } ?>
