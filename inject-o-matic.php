<?php
/*
Plugin Name: Inject-O-Matic
Text Domain: injectomat
Domain Path: /language
Plugin URI: http://plugins.twinpictures.de/plugins/inject-o-matic/
Description: Inject custom jQuery/Javascript into the header and/or footer of a WordPress site.
Version: 0.2
Author: twinpictures
Author URI: http://twinpictuers.de
License: GPL2
*/

/**
 * Class WP_Inject_O_Matic
 * @package WP_Inject_O_Matic
 * @category WordPress Plugins
 */
class WP_Inject_O_Matic {
	/**
	 * Current version
	 * @var string
	 */
	var $version = '0.2';

	/**
	 * Used as prefix for options entry
	 * @var string
	 */
	var $domain = 'injectomat';
	
	/**
	 * Name of the options
	 * @var string
	 */
	var $options_name = 'WP_Inject_O_Matic_options';

	/**
	 * @var array
	 */
	var $options = array(
		'custom_header_script' => '',
		'custom_footer_script' => ''
	);
	
	
	/**
	 * PHP5 constructor
	 */
	function __construct() {
		// set option values
		$this->_set_options();
		
		// load text domain for translations
		load_plugin_textdomain( 'injectomat', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/language/' );

		//header scripts
		add_action('wp_enqueue_scripts', array( $this, 'header_scripts' ));
		
		//footer scripts
		add_action('wp_footer', array( $this, 'footer_scripts' ), 70, 0);
		
		// add actions
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}
	
	//header scripts
	function header_scripts(){
		if( !empty( $this->options['custom_header_script'] ) ){
			echo "\n<script language='javascript' type='text/javascript'>\n";
			echo $this->options['custom_header_script'];
			echo "\n</script>\n";
		}
	}
	
	//footer scripts
	function footer_scripts(){
		if( !empty( $this->options['custom_footer_script'] ) ){
			echo "\n<script language='javascript' type='text/javascript'>\n";
			echo $this->options['custom_footer_script'];
			echo "\n</script>\n";
		}
	}
	
	/**
	 * Callback admin_menu
	 */
	function admin_menu() {
		if ( function_exists( 'add_options_page' ) AND current_user_can( 'manage_options' ) ) {
			// add options page
			$page = add_options_page('Inject-O-Matic Options', 'Inject-O-Matic', 'manage_options', 'inject-o-matic-options', array( $this, 'options_page' ));
		}
	}
	
	/**
	 * Callback admin_init
	 */
	function admin_init() {
		// register settings
		register_setting( $this->domain, $this->options_name );
	}
	
	/**
	 * Admin options page
	 */
	function options_page() {
		$like_it_arr = array(
						__('really tied the room together', 'injectomat'),
						__('made you feel all warm and fuzzy on the inside', 'injectomat'),
						__('restored your faith in humanity... even if only for a fleeting second', 'injectomat'),
						__('rocked your world', 'provided a positive vision of future living', 'injectomat'),
						__('inspired you to commit a random act of kindness', 'injectomat'),
						__('encouraged more regular flossing of the teeth', 'injectomat'),
						__('helped organize your life in the small ways that matter', 'injectomat'),
						__('saved your minutes--if not tens of minutes--writing your own solution', 'injectomat'),
						__('brightened your day... or darkened if if you are trying to sleep in', 'injectomat'),
						__('caused you to dance a little jig of joy and joyousness', 'injectomat'),
						__('inspired you to tweet a little @twinpictues social love', 'injectomat'),
						__('tasted great, while also being less filling', 'injectomat'),
						__('caused you to shout: "everybody spread love, give me some mo!"', 'injectomat'),
						__('helped you keep the funk alive', 'injectomat'),
						__('<a href="http://www.youtube.com/watch?v=dvQ28F5fOdU" target="_blank">soften hands while you do dishes</a>', 'injectomat'),
						__('helped that little old lady <a href="http://www.youtube.com/watch?v=Ug75diEyiA0" target="_blank">find the beef</a>', 'injectomat')
					);
		$rand_key = array_rand($like_it_arr);
		$like_it = $like_it_arr[$rand_key];
	?>
		<div class="wrap">
			<h2>Inject-O-Matic</h2>
		</div>
		
		<div class="postbox-container metabox-holder meta-box-sortables" style="width: 69%">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'Inject-O-Matic Settings', 'injectomat' ) ?></h3>
					<div class="inside">
						<form method="post" action="options.php">
							<?php
								settings_fields( $this->domain );
								$this->_set_options();
								$options = $this->options;
							?>
							<fieldset class="options">
								<table class="form-table">
								<tr>
									<th><?php _e( 'Header Script', 'injectomat' ) ?></th>
									<td><label><textarea id="<?php echo $this->options_name ?>[custom_header_script]" name="<?php echo $this->options_name ?>[custom_header_script]" style="width: 100%; height: 150px;"><?php echo $options['custom_header_script']; ?></textarea>
										<br /><span class="description"><?php _e('Script to inject into the Header', 'injectomat'); ?></span></label>
									</td>
								</tr>
								<tr>
									<th><?php _e( 'Footer Script', 'injectomat' ) ?></th>
									<td><label><textarea id="<?php echo $this->options_name ?>[custom_footer_script]" name="<?php echo $this->options_name ?>[custom_footer_script]" style="width: 100%; height: 150px;"><?php echo $options['custom_footer_script']; ?></textarea>
										<br /><span class="description"><?php _e('Script to inject into the Footer', 'injectomat'); ?></span></label>
									</td>
								</tr>
								</table>
							</fieldset>
							
							<p class="submit">
								<input class="button-primary" type="submit" style="float:right" value="<?php _e( 'Save Changes' ) ?>" />
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<div class="postbox-container side metabox-holder meta-box-sortables" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'About' ) ?></h3>
					<div class="inside">
						<h4>Inject-O-Matic Version <?php echo $this->version; ?></h4>
						<p><?php _e( 'Inject jQuery and other Javascript into the header and/or footer of a WordPress site.', 'injectomat') ?></p>
						<ul>
							<!--<li><?php printf( __( '%sDetailed documentation%s, complete with working demonstrations of all shortcode attributes, is available for your instructional enjoyment.', 'injectomat'), '<a href="http://plugins.twinpictures.de/plugins/inject-o-matic/documentation/" target="_blank">', '</a>'); ?></li>-->
							<li><?php printf( __( 'Help %translate%s this plugin into your language', 'injectomat'), '<a href="http://translate.twinpictures.de/projects/injectomat" target="_blank">', '</a>'); ?></li>
							<li><?php printf( __( 'Free, community based %ssupport%s', 'injectomat'), '<a href="http://wordpress.org/support/plugin/inject-o-matic" target="_blank">', '</a>'); ?></li>
							<li><?php printf( __('If Inject-O-Matic %s, please consider %sreviewing it at WordPress.org%s to better help others make informed plugin choices.', 'injectomat'), $like_it, '<a href="http://wordpress.org/support/view/plugin-reviews/inject-o-matic" target="_blank">', '</a>' ) ?></li>
							<li><a href="http://wordpress.org/extend/plugins/inject-o-matic/" target="_blank">WordPress.org</a> | <a href="http://plugins.twinpictures.de/plugins/inject-o-matic/" target="_blank">Twinpictues Plugin Oven</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<!--
		<div class="postbox-container side metabox-holder meta-box-sortables" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'Level Up!' ) ?></h3>
					<div class="inside">
						<p><?php printf(__( '%sInject-Pro-Matic%s is our premium plugin that offers a few additional attributes and features for <i>ultimate</i> flexibility.', 'injectomatic' ), '<a href="http://plugins.twinpictures.de/premium-plugins/inject-pro-matic/">', '</a>'); ?></p>		
						<h4><?php _e('Reasons To Go Pro', 'injectomat'); ?></h4>
						<ol>
							<li><?php _e('You are an advanced user and want/need additional features', 'injectomat'); ?></li>
							<li><?php _e('Inject-O-Matic was just what I needed. Here, have some money.', 'injectomat'); ?></li>
						</ol>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		-->
	<?php
	}

	/**
	 * Set options from save values or defaults
	 */
	function _set_options() {
		// set options
		$saved_options = get_option( $this->options_name );

		// backwards compatible (old values)
		if ( empty( $saved_options ) ) {
			$saved_options = get_option( $this->domain . 'options' );
		}
		
		// set all options
		if ( ! empty( $saved_options ) ) {
			foreach ( $this->options AS $key => $option ) {
				$this->options[ $key ] = ( empty( $saved_options[ $key ] ) ) ? '' : $saved_options[ $key ];
			}
		}
	}
	
} // end class WP_Inject_O_Matic


/**
 * Create instance
 */
$WP_Inject_O_Matic = new WP_Inject_O_Matic;

?>