<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://theauthorurl
 * @since      1.0.0
 *
 * @package    Fcc_Validator
 * @subpackage Fcc_Validator/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Fcc_Validator
 * @subpackage Fcc_Validator/public
 * @author     Edward Soares - WRUE674 <esoares9483@gmail.com>
 */
class Fcc_Validator_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fcc_Validator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fcc_Validator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_style( 'fontawesome-css', plugin_dir_url( __FILE__ ) . '/fontawesome-free-6.2.1-web/css/fontawesome.css' );
        wp_enqueue_style( 'fontawesome-brands-css', plugin_dir_url( __FILE__ ) . '/fontawesome-free-6.2.1-web/css/brands.css' );
        wp_enqueue_style( 'fontawesome-solid-css', plugin_dir_url( __FILE__ ) . '/fontawesome-free-6.2.1-web/css/solid.css' );
        wp_enqueue_style( 'adminlte-css', plugin_dir_url( __FILE__ ) . '/css/adminlte.min.css' );
        wp_enqueue_style( 'jquery-datatables-css', plugin_dir_url( __FILE__ ) . '/DataTables/datatables.min.css' );
		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fcc-validator-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fcc_Validator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fcc_Validator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_script( 'jquery-datatables-js', plugin_dir_url( __FILE__ ) . '/DataTables/datatables.min.js', array( 'jquery' ), date("h:i:s"), true );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . '/js/fcc-validator-admin.js', array( 'jquery' ), date("h:i:s"), true );
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fcc-validator-public.js', array( 'jquery' ), $this->version, false );

	}

    function fcc_validator_public_menu() {

        add_submenu_page(
            'fcc_validator',
            __('Members List'),
            __('Members List'),
            'subscriber',
            'fcc_validator-user',
            array( $this, 'fcc_validator_user_page' ));
    }

    function fcc_validator_user_page() {
        //add_menu_page( 'My Top Level Menu Example', 'Top Level Menu', 'manage_options', 'myplugin/myplugin-admin-page.php', 'myplguin_admin_page', 'dashicons-tickets', 6  );
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/fcc-validator-public-display.php';

    }

}
