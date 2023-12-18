<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://theauthorurl
 * @since      1.0.0
 *
 * @package    Fcc_Validator
 * @subpackage Fcc_Validator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fcc_Validator
 * @subpackage Fcc_Validator/admin
 * @author     Edward Soares - WRUE674 <esoares9483@gmail.com>
 */
class Fcc_Validator_Admin {

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
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
        //wp_enqueue_style( 'jquery-datatables-css-buttons', plugin_dir_url( __FILE__ ) . 'DataTables/Buttons-2.3.3/css/buttons.bootstrap5.css' );
        //wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fcc-validator-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
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
        //wp_enqueue_script( 'jquery-datatables-js-buttons', plugin_dir_url( __FILE__ ) . 'DataTables/Buttons-2.3.3/js/buttons.bootstrap5.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . '/js/fcc-validator-admin.js', array( 'jquery' ), date("h:i:s"), true );

    }

//    public function gmrs_weekly_records_update() {
//		    //$api_online = "Online";
//		    global $wpdb;
//		    global $wp_roles;
//
//
////	    $tz = 'America/Denver';
////	    $timestamp = time();
////	    $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
////	    $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
////	    $date = $dt->format('Y/m/d H:i:s');
//
//
//		    $all_sync_users = get_users();
//
//
//		    foreach ($all_sync_users as $all_sync_user){
//
//			    $gmrs_status = get_user_meta( $all_sync_user->id, 'gmrs_status', true );
//
//			    // Run Auto FCC Validator
//			    if ( $gmrs_status != "Suspended" ) {
//
//				    $curl = curl_init();
//				    $url = "https://fccvalidator.com/api/v1/gmrs";
//
//				    curl_setopt_array($curl, array(
//					    CURLOPT_URL => $url,
//					    CURLOPT_RETURNTRANSFER => true,
//					    CURLOPT_ENCODING => '',
//					    CURLOPT_MAXREDIRS => 10,
//					    CURLOPT_TIMEOUT => 0,
//					    CURLOPT_FOLLOWLOCATION => true,
//					    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//					    CURLOPT_CUSTOMREQUEST => 'GET',
//				    ));
//
//				    $response = curl_exec($curl);
//				    curl_close($curl);
//				    $json = json_decode($response, true);
//
//				    if ($json['status'] == 200) {
//
//				    $gmrs_sync = get_user_meta( $all_sync_user->id, 'gmrs_sync', true );
////                echo "Sync: ".$gmrs_sync;
////                echo "<br>";
//				    $now = date('Y-m-d H:i:s');
//
//				    $timediff = strtotime($now) - strtotime($gmrs_sync);
//				    if ($timediff > 86400 || $gmrs_sync === 0 || $gmrs_status === "Manual") {
//					    $table = $wpdb->prefix . 'fcc_gmrs_records';
//					    $wpdb->delete( $table, array( 'frn' => $all_sync_user->frn_number ) );
//
//					    $metas = array(
//						    'callsign'          => null,
//						    'gmrs_status'       => "Not Verified",
//						    'licenseexpiredate' => null,
//						    'gmrs_location'     => null,
//						    'gmrs_sync'         => null
//					    );
//					    //echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
//					    foreach ( $metas as $key => $value ) {
//						    update_user_meta( $all_sync_user->id, $key, $value );
//					    }
//
//					    // Remove Subscriber Role.
//					    $usr_obj = new WP_User( $all_sync_user->id );
//					    // Remove role
//					    $usr_obj->remove_role( 'subscriber' );
//					    // Add role
//					    $usr_obj->add_role( 'not_verified' );
//
//					    //if ($all_sync_user->frn_number != null && $gmrs_status != "Suspended") {
//
//					    $curl = curl_init();
//					    $url  = "https://fccvalidator.com/api/v1/gmrs/$all_sync_user->frn_number";
//
//					    curl_setopt_array( $curl, array(
//						    CURLOPT_URL            => $url,
//						    CURLOPT_RETURNTRANSFER => true,
//						    CURLOPT_ENCODING       => '',
//						    CURLOPT_MAXREDIRS      => 10,
//						    CURLOPT_TIMEOUT        => 0,
//						    CURLOPT_FOLLOWLOCATION => true,
//						    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
//						    CURLOPT_CUSTOMREQUEST  => 'GET',
//					    ) );
//
//					    $response = curl_exec( $curl );
//					    curl_close( $curl );
//					    $json = json_decode( $response, true );
//					    //print_r($json['gmrs_license'][0]['frn']);
//					    //echo "API SYNC";
//
//					    global $wpdb;
//
//					    $wpdb->insert(
//						    $wpdb->prefix . 'fcc_gmrs_records',
//						    [
//							    'usid'       => $json['gmrs_license'][0]['usid'],
//							    'frn'        => $json['gmrs_license'][0]['frn'],
//							    'callsign'   => $json['gmrs_license'][0]['callsign'],
//							    'city'       => $json['gmrs_license'][0]['city'],
//							    'state'      => $json['gmrs_license'][0]['state'],
//							    'status'     => $json['gmrs_license'][0]['status'],
//							    'expiration' => $json['gmrs_license'][0]['expiration'],
//						    ]
//					    );
//
//					    $gmrs_meta = $wpdb->get_row( "SELECT callsign, city, state, status, expiration FROM {$wpdb->prefix}fcc_gmrs_records WHERE frn = '$all_sync_user->frn_number'" );
//
//					    if ( $gmrs_meta > 0 ) { // if we find the record we update the user profile.
//						    $metas = array(
//							    'callsign'          => $gmrs_meta->callsign,
//							    'gmrs_status'       => "Auto",
//							    'licenseexpiredate' => $gmrs_meta->expiration,
//							    'gmrs_location'     => $gmrs_meta->city . ", " . $gmrs_meta->state,
//							    'gmrs_sync'     => date('Y-m-d H:i:s')
//						    );
//						    //echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
//						    foreach ( $metas as $key => $value ) {
//							    update_user_meta( $all_sync_user->id, $key, $value );
//						    }
//
//						    // Add Subscriber Role.
//						    $u = new WP_User( $all_sync_user->id );
//
//						    // Remove role
//						    $u->remove_role( 'not_verified' );
//
//						    // Add role
//						    $u->add_role( 'subscriber' );
//					    } elseif ($gmrs_status === "Manual") { // if we do not find the record we clear the user profile and adjust roles.
//						    $metas = array(
//							    'gmrs_status'       => "Manual",
//							    'gmrs_sync'         => null
//						    );
//						    //echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
//						    foreach ( $metas as $key => $value ) {
//							    update_user_meta( $all_sync_user->id, $key, $value );
//						    }
//
//						    // Remove Subscriber Role.
//						    $usr_obj = new WP_User( $all_sync_user->id );
//						    // Remove role
//						    $usr_obj->remove_role( 'not_verified' );
//						    // Add role
//						    $usr_obj->add_role( 'subscriber' );
//					    } else { // if we do not find the record we clear the user profile and adjust roles.
//						    $metas = array(
//							    'callsign'          => null,
//							    'gmrs_status'       => "Not Verified",
//							    'licenseexpiredate' => null,
//							    'gmrs_location'     => null,
//							    'gmrs_sync'         => null
//						    );
//						    //echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
//						    foreach ( $metas as $key => $value ) {
//							    update_user_meta( $all_sync_user->id, $key, $value );
//						    }
//
//						    // Remove Subscriber Role.
//						    $usr_obj = new WP_User( $all_sync_user->id );
//						    // Remove role
//						    $usr_obj->remove_role( 'subscriber' );
//						    // Add role
//						    $usr_obj->add_role( 'not_verified' );
//					    } // else
//				    }
//			    } // if not suspended
//
//			    } else {
//				    //$api_online = "Offline";
//				    ## DEBUG EMAIL
//				    $to = 'esoares9483@gmail.com';
//				    $subject = get_bloginfo( 'name' ).' - GMRS FCC Plugin';
//				    $message = "API IS DOWN!";
//				    wp_mail( $to, $subject, $message );
//				    ##
//			    }
//		    } // foreach user
//
//
////	    ## DEBUG EMAIL
////	    $to = 'esoares9483@gmail.com';
////	    $subject = 'HOURLY - GMRS FCC Plugin';
////	    $message = 'FINISHED: '.$date;
////	    wp_mail( $to, $subject, $message );
////	    ##
//    } // end update gmrs records daily.

    /**
     * Register the settings page for the admin area.
     *
     * @since    1.0.0
     */
    //public function register_settings_page() {
    //    // Create our settings page as a submenu page.
    //    add_submenu_page(
    //        'users.php',                             // parent slug
    //        __( 'FCC GMRS Validator', 'fcc-gmrs-validator' ),      // page title
    //        __( 'FCC GMRS Validator', 'fcc-gmrs-validator' ),      // menu title
    //        'manage_options',                        // capability
    //        'fcc-gmrs-validator',                           // menu_slug
    //        array( $this, 'display_settings_page' )  // callable function
    //    );
    //}

    /**
     * Display the settings page content for the page we have created.
     *
     * @since    1.0.0
     */
    //public function display_settings_page() {

    //    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/fcc-validator-admin-display.php';

    //}



// Add Menu Items
//add_action('plugins_loaded', 'fcc_validator_plugin_init');

    //function fcc_validator_plugin_init() {
    //    add_action('admin_menu', 'fcc_validator_admin_menu');
    //}

    function fcc_validator_admin_menu() {
        add_menu_page(
            'FCC Validator',
            'FCC Validator',
            'manage_options',
            'fcc_validator',
            array( $this, 'fcc_validator_main_page' ),
            'dashicons-id',
            999);

        add_submenu_page(
            'fcc_validator',
            __('Settings'),
            __('Settings'),
            'manage_options',
            'fcc_validator-settings',
            array( $this, 'fcc_validator_child_settings' ));

//	    add_submenu_page(
//		    'fcc_validator',
//		    __('Members Map'),
//		    __('Members Map'),
//		    'manage_options',
//		    'fcc_validator-map',
//		    array( $this, 'fcc_validator_child_map' ));
    }

    function fcc_validator_main_page() {
        //add_menu_page( 'My Top Level Menu Example', 'Top Level Menu', 'manage_options', 'myplugin/myplugin-admin-page.php', 'myplguin_admin_page', 'dashicons-tickets', 6  );
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/fcc-validator-admin-display.php';

    }

    function fcc_validator_child_settings() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/fcc-validator-admin-settings.php';
    }

//	function fcc_validator_child_map() {
//		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/fcc-validator-admin-map.php';
//	}

}
