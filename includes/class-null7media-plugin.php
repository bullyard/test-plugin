<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://bullyard.no
 * @since      1.0.0
 *
 * @package    Null7media_Plugin
 * @subpackage Null7media_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Null7media_Plugin
 * @subpackage Null7media_Plugin/includes
 * @author     Rodrigo <Perez>
 */
class Null7media_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Null7media_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	protected $templates;


	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'null7media-plugin';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->templates = array('null7media-template.php' => 'Currency converter template');


	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Null7media_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Null7media_Plugin_i18n. Defines internationalization functionality.
	 * - Null7media_Plugin_Admin. Defines all hooks for the admin area.
	 * - Null7media_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-null7media-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-null7media-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-null7media-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-null7media-plugin-public.php';

		$this->loader = new Null7media_Plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Null7media_Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Null7media_Plugin_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Null7media_Plugin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// add page template to list on page edit
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {
			// 4.6 and older
			$this->loader->add_filter( 'page_attributes_dropdown_pages_args', $this, 'register_project_templates' );
		} else {
			// Add a filter to the wp 4.7 version attributes metabox
			$this->loader->add_filter( 'theme_page_templates', $this, 'add_new_template' );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Null7media_Plugin_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_ajax_currency_ajax', $this, 'process_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_currency_ajax', $this, 'process_ajax' );



		//load template on public
		$this->loader->add_action( 'template_include', $this, 'view_project_template' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Null7media_Plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * attachs template list for new WP
	 *
	 * @since     1.0.0
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

	/**
	 * attachs template list
	 *
	 * @since     1.0.0
	 *
	 */
	public function register_project_templates( $atts ) {

		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}

		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;

	}
	/**
	 * selects template when requested
	 *
	 * @since     1.0.0
	 *
	 */
	public function view_project_template( $template ) {
		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}

		// Return default template if we don't have a custom one defined
		if ( !isset( $this->templates[get_post_meta(
			$post->ID, '_wp_page_template', true
		)] ) ) {
			return $template;
		}

		$file = plugin_dir_path(__FILE__). get_post_meta(
			$post->ID, '_wp_page_template', true
		);

		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}

		// Return template
		return $template;

	}

	private function log2db($val){
		global $wpdb;
		$sql = $wpdb->prepare(
		" INSERT INTO {$wpdb->prefix}".BY_CONF_tablename." (log_result)
									  values(%s)", $val);
		$wpdb->query( $sql );

	}

	public static function get_form_HTML(){

		echo "<h1>".__('Velg antall USD du ønsker å konvertere til NOK med dagens kurs', 'null7media')."</h1>";

		echo "<input type='number' id='convertionInput' name='USD' value='' />";
		echo "<button id='serverBtn'>".__('Konverter via ajax', 'null7media')."</button><br />";
		echo "<div id='formOutput'></div>";


	}

	public function convert_USDNOK($val){

			$key = md5('plugin_cache2_'.$val);

			$stored = get_transient($key);
			if ($stored === false){

				$apiBase = "https://api.fixer.io/latest?base=USD";
				$request = wp_remote_get( $apiBase );

				if( is_wp_error( $request ) ) {
					return false;
				}

				$respArray = json_decode(wp_remote_retrieve_body( $request ), true);
				$rates = $respArray['rates'];

				if ($rates['NOK']){
					$result = number_format($val*$rates['NOK'], 2, ',', ' ');

					//store
					set_transient($key, $result, 60*5); // two minutes

					// log
					$this->log2db($result);

					// return
					return $result;

				}

				return false;

			}else return $stored. " [cached] ";

	}

	public function process_ajax(){


		$passedVal = "";
		if (is_numeric($_REQUEST['val'])) {
			$passedVal = $_REQUEST['val'];
			$calculated = $this->convert_USDNOK($passedVal);

			if ($calculated !==false) {
				$convertedValue = sprintf( esc_html__( '%s USD tilsvarer idag %s NOK', 'null7media' ), $passedVal, $calculated );
			}
		}

		if (isset($convertedValue)) echo $convertedValue;
		else  echo  __("Beløpet du tastet inn kunne ikke konverteres", 'null7media');
		 wp_die();
	}




}
