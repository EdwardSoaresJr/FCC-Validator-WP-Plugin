<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://fccvalidator.com
 * @since             1.0.0
 * @package           Fcc_Validator
 *
 * @wordpress-plugin
 * Plugin Name:       GMRS / HAM FCC License Validator
 * Plugin URI:        https://fccvalidator.com
 * Description:       Used to validate users against provided FCC license API with activation and suspension of user, as well as create updated membership list.
 * Version:           1.0.1
 * Author:            Edward Soares - (GMRS: WRUE674) (Amateur Radio: KD0RBJ)
 * Author URI:        https://fccvalidator.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fcc-validator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FCC_VALIDATOR_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fcc-validator-activator.php
 */
function activate_fcc_validator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fcc-validator-activator.php';
	Fcc_Validator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fcc-validator-deactivator.php
 */
function deactivate_fcc_validator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fcc-validator-deactivator.php';
	Fcc_Validator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fcc_validator' );
register_deactivation_hook( __FILE__, 'deactivate_fcc_validator' );

function create_gmrs_license_table() {

    global $wpdb;

    $table_name = $wpdb->prefix . "fcc_gmrs_records";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
      usid int(11) unsigned unique NOT NULL,
      frn char(11) unique NULL,
      callsign varchar(25) unique NOT NULL,
      city char(20) NOT NULL,
      state char(2) NOT NULL,
      status char(1) NOT NULL,
      expiration char(10) NOT NULL,
      PRIMARY KEY usid (usid)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook( __FILE__, 'create_gmrs_license_table' );


### EXISTING MODIFIED FUNCTIONS.PHP START
add_filter( 'manage_users_columns', 'rudr_modify_user_table' );

function rudr_modify_user_table( $columns ) {

    // unset( $columns['posts'] ); // maybe you would like to remove default columns
    $columns['registration_date'] = 'Registration date'; // add new

    return $columns;

}

/*
 * Fill our new column with the registration dates of the users
 * @param string $row_output text/HTML output of a table cell
 * @param string $column_id_attr column ID
 * @param int $user user ID (in fact - table row ID)
 */
add_filter( 'manage_users_custom_column', 'rudr_modify_user_table_row', 10, 3 );

function rudr_modify_user_table_row( $row_output, $column_id_attr, $user ) {

    $date_format = 'M j, Y - h:i A';

    switch ( $column_id_attr ) {
        case 'registration_date' :
            return date( $date_format, strtotime( get_the_author_meta( 'registered', $user ) ) );
            break;
        default:
    }

    return $row_output;

}

//function rudr_modify_user_table_row( $row_output, $column_id_attr, $user ) {
//
//    $date_format = 'M j, Y - h:i A';
//
//    switch ( $column_id_attr ) {
//        case 'registration_date' :
//            return date( $date_format, strtotime( get_the_author_meta( 'registered', $user ) ) );
//        case 'frn_number' :
//            return get_the_author_meta( 'frn_number', $user );
//        case 'callsign' :
//            return get_the_author_meta( 'callsign', $user );
//        case 'licenseexpiredate' :
//            return get_the_author_meta( 'licenseexpiredate', $user );
//        default:
//    }
//
//    return $row_output;
//
//}

/*
 * Make our "Registration date" column sortable
 * @param array $columns Array of all user sortable columns {column ID} => {orderby GET-param}
 */
add_filter( 'manage_users_sortable_columns', 'rudr_make_registered_column_sortable' );

function rudr_make_registered_column_sortable( $columns ) {
    return wp_parse_args( array( 'registration_date' => 'registered' ), $columns );
}

// Displays the entry inside of the profile.
function new_contact_methods( $contactmethods ) {
    $contactmethods['frn_number'] = 'FCC FRN Number (required)';
    $contactmethods['callsign'] = 'GMRS Call Sign';
    $contactmethods['licenseexpiredate'] = 'GMRS License Expire Date';
	$contactmethods['gmrs_location'] = 'Member Location';
    //$contactmethods['gmrs_status'] = 'GMRS Club Status';
    return $contactmethods;
}
add_filter( 'user_contactmethods', 'new_contact_methods', 10, 1 );

// Displays the columns on users list
//function new_modify_user_table( $column ) {
//    $column['frn_number'] = 'FRN Number';
//    $column['callsign'] = 'GMRS Call Sign';
//    $column['licenseexpiredate'] = 'License Expire Date';
//    return $column;
//}
//add_filter( 'manage_users_columns', 'new_modify_user_table' );

//function new_modify_user_table_row( $val, $column_name, $user_id ) {
//    switch ($column_name) {
//        case 'frn_number' :
//            return get_the_author_meta( 'frn_number', $user_id );
//        case 'callsign' :
//            return get_the_author_meta( 'callsign', $user_id );
//        case 'licenseexpiredate' :
//            return get_the_author_meta( 'licenseexpiredate', $user_id );
//        default:
//    }
//    return $val;
//}

### EXISTING MODIFIED FUNCTIONS.PHP END



function update_profile_update_gmrs( $user_id ) {
	$curl = curl_init();
	$url = "https://fccvalidator.com/api/v1/gmrs";

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
	));

	$response = curl_exec($curl);
	curl_close($curl);
	$json = json_decode($response, true);

	if ($json['status'] == 200) {
		//$api_online = "Online";
		global $wpdb;
		global $wp_roles;

		$gmrs_status = get_user_meta( $user_id, 'gmrs_status', true );

		$frn_post = sanitize_text_field( $_POST['frn_number']);

		//$user_object = get_userdata($user_id);

		// Run Auto FCC Validator
		if ($frn_post != null && $gmrs_status != "Suspended") {

			$table = $wpdb->prefix . 'fcc_gmrs_records';
			$wpdb->delete( $table, array( 'frn' => $frn_post ) );

			$metas = array(
				'callsign'          => null,
				'gmrs_status'       => "Not Verified",
				'licenseexpiredate' => null,
				'gmrs_location'     => null,
				'gmrs_sync'         => null
			);
			//echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
			foreach ( $metas as $key => $value ) {
				update_user_meta( $user_id, $key, $value );
			}

			// Remove Subscriber Role.
			$usr_obj = new WP_User( $user_id );
			// Remove role
			$usr_obj->remove_role( 'subscriber' );
			// Add role
			$usr_obj->add_role( 'not_verified' );

			//if ($all_sync_user->frn_number != null && $gmrs_status != "Suspended") {

			$curl = curl_init();
			$url  = "https://fccvalidator.com/api/v1/gmrs/$frn_post";

			curl_setopt_array( $curl, array(
				CURLOPT_URL            => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => '',
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => 'GET',
			) );

			$response = curl_exec( $curl );
			curl_close( $curl );
			$json = json_decode( $response, true );
			//print_r($json['gmrs_license'][0]['frn']);
			//echo "API SYNC";

			global $wpdb;

			$wpdb->insert(
				$wpdb->prefix . 'fcc_gmrs_records',
				[
					'usid'       => $json['gmrs_license'][0]['usid'],
					'frn'        => $json['gmrs_license'][0]['frn'],
					'callsign'   => $json['gmrs_license'][0]['callsign'],
					'city'       => $json['gmrs_license'][0]['city'],
					'state'      => $json['gmrs_license'][0]['state'],
					'status'     => $json['gmrs_license'][0]['status'],
					'expiration' => $json['gmrs_license'][0]['expiration'],
				]
			);

			$gmrs_meta = $wpdb->get_row( "SELECT callsign, city, state, status, expiration FROM {$wpdb->prefix}fcc_gmrs_records WHERE frn = '$frn_post'" );

			if ( $gmrs_meta > 0 ) { // if we find the record we update the user profile.
				$metas = array(
					'callsign'          => $gmrs_meta->callsign,
					'gmrs_status'       => "Auto",
					'licenseexpiredate' => $gmrs_meta->expiration,
					'gmrs_location'     => $gmrs_meta->city . ", " . $gmrs_meta->state,
					'gmrs_sync'     => date('Y-m-d H:i:s')
				);
				//echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
				foreach ( $metas as $key => $value ) {
					update_user_meta( $user_id, $key, $value );
				}

				// Add Subscriber Role.
				$u = new WP_User( $user_id );

				// Remove role
				$u->remove_role( 'not_verified' );

				// Add role
				$u->add_role( 'subscriber' );
			} elseif ($gmrs_status === "Manual") { // if we do not find the record we clear the user profile and adjust roles.
				$metas = array(
					'gmrs_status'       => "Manual",
					'gmrs_sync'     => date('Y-m-d H:i:s')
				);
				//echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
				foreach ( $metas as $key => $value ) {
					update_user_meta( $user_id, $key, $value );
				}

				// Remove Subscriber Role.
				$usr_obj = new WP_User( $user_id );
				// Remove role
				$usr_obj->remove_role( 'not_verified' );
				// Add role
				$usr_obj->add_role( 'subscriber' );
			} else { // if we do not find the record we clear the user profile and adjust roles.
				$metas = array(
					'callsign'          => null,
					'gmrs_status'       => "Not Verified",
					'licenseexpiredate' => null,
					'gmrs_location'     => null,
					'gmrs_sync'         => null
				);
				//echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
				foreach ( $metas as $key => $value ) {
					update_user_meta( $user_id, $key, $value );
				}

				// Remove Subscriber Role.
				$usr_obj = new WP_User( $user_id );
				// Remove role
				$usr_obj->remove_role( 'subscriber' );
				// Add role
				$usr_obj->add_role( 'not_verified' );
			} // else
		} else {
			$metas = array(
				'callsign'          => null,
				'gmrs_status'       => "Not Verified",
				'licenseexpiredate' => null,
				'gmrs_location'     => null,
				'gmrs_sync'         => null
			);
			//echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
			foreach ( $metas as $key => $value ) {
				update_user_meta( $user_id, $key, $value );
			}

			// Remove Subscriber Role.
			$usr_obj = new WP_User( $user_id );
			// Remove role
			$usr_obj->remove_role( 'subscriber' );
			// Add role
			$usr_obj->add_role( 'not_verified' );
		}
	} else {
		//$api_online = "Offline";
		## DEBUG EMAIL
		$to = 'esoares9483@gmail.com';
		$subject = get_bloginfo( 'name' ).' - GMRS FCC Plugin';
		$message = "API IS DOWN! - USER PROFILE UPDATE";
		wp_mail( $to, $subject, $message );
		##
	}
}
add_action( 'profile_update', 'update_profile_update_gmrs', 10, 2 );

//add_action( 'updated_user_meta', 'my_update_post_meta', 10, 4 );
//function my_update_post_meta($meta_id, $object_id, $meta_key, $_meta_value) {
//    //if ( !current_user_can( 'edit_user', 1 ) ){
//    //    return false;
//    //}
//    //if( isset( $_POST['callsign'] ) ){
//    //update_user_meta( $user_id, 'callsign', sanitize_text_field( $_POST['callsign'] ) );
//    //    update_user_meta( 1, 'callsign', sanitize_text_field( "" ) );
//    //}
//
//
//
//    //$user_id = get_current_user_id();
//
//    //check to see if 'updated' field exists
//    //$updated= get_user_meta($user_id, 'updated', TRUE);
//
//    //if yes update date /time
//    //$datetime = date('Y-m-d H:i:s');
//
//    update_user_meta( 1, 'gmrs_status', "A" );
//}


//
// disable non admin editing of GMRS fields.
// h/t https://wp-qa.com/how-to-make-custom-field-in-wordpress-user-profile-read-only
function user_login_update_gmrs( $user ) {
	$user = get_user_by( 'email', $user );
	$user_id = $user->ID;

	$curl = curl_init();
	$url = "https://fccvalidator.com/api/v1/gmrs";

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
	));

	$response = curl_exec($curl);
	curl_close($curl);
	$json = json_decode($response, true);

	if ($json['status'] == 200) {
		//$api_online = "Online";
		global $wpdb;
		global $wp_roles;

		$gmrs_status = get_user_meta( $user_id, 'gmrs_status', true );

		$frn_post = $user->frn_number;

		//$user_object = get_userdata($user_id);

		// Run Auto FCC Validator
		if ($frn_post != null && $gmrs_status != "Suspended") {

			$table = $wpdb->prefix . 'fcc_gmrs_records';
			$wpdb->delete( $table, array( 'frn' => $frn_post ) );

			$metas = array(
				'callsign'          => null,
				'gmrs_status'       => "Not Verified",
				'licenseexpiredate' => null,
				'gmrs_location'     => null,
				'gmrs_sync'         => null
			);
			//echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
			foreach ( $metas as $key => $value ) {
				update_user_meta( $user_id, $key, $value );
			}

			// Remove Subscriber Role.
			$usr_obj = new WP_User( $user_id );
			// Remove role
			$usr_obj->remove_role( 'subscriber' );
			// Add role
			$usr_obj->add_role( 'not_verified' );

			//if ($all_sync_user->frn_number != null && $gmrs_status != "Suspended") {

			$curl = curl_init();
			$url  = "https://fccvalidator.com/api/v1/gmrs/$frn_post";

			curl_setopt_array( $curl, array(
				CURLOPT_URL            => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => '',
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => 'GET',
			) );

			$response = curl_exec( $curl );
			curl_close( $curl );
			$json = json_decode( $response, true );
			//print_r($json['gmrs_license'][0]['frn']);
			//echo "API SYNC";

			global $wpdb;

			$wpdb->insert(
				$wpdb->prefix . 'fcc_gmrs_records',
				[
					'usid'       => $json['gmrs_license'][0]['usid'],
					'frn'        => $json['gmrs_license'][0]['frn'],
					'callsign'   => $json['gmrs_license'][0]['callsign'],
					'city'       => $json['gmrs_license'][0]['city'],
					'state'      => $json['gmrs_license'][0]['state'],
					'status'     => $json['gmrs_license'][0]['status'],
					'expiration' => $json['gmrs_license'][0]['expiration'],
				]
			);

			$gmrs_meta = $wpdb->get_row( "SELECT callsign, city, state, status, expiration FROM {$wpdb->prefix}fcc_gmrs_records WHERE frn = '$frn_post'" );

			if ( $gmrs_meta > 0 ) { // if we find the record we update the user profile.
				$metas = array(
					'callsign'          => $gmrs_meta->callsign,
					'gmrs_status'       => "Auto",
					'licenseexpiredate' => $gmrs_meta->expiration,
					'gmrs_location'     => $gmrs_meta->city . ", " . $gmrs_meta->state,
					'gmrs_sync'     => date('Y-m-d H:i:s')
				);
				//echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
				foreach ( $metas as $key => $value ) {
					update_user_meta( $user_id, $key, $value );
				}

				// Add Subscriber Role.
				$u = new WP_User( $user_id );

				// Remove role
				$u->remove_role( 'not_verified' );

				// Add role
				$u->add_role( 'subscriber' );
			} elseif ($gmrs_status === "Manual") { // if we do not find the record we clear the user profile and adjust roles.
				$metas = array(
					'gmrs_status'       => "Manual",
					'gmrs_sync'     => date('Y-m-d H:i:s')
				);
				//echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
				foreach ( $metas as $key => $value ) {
					update_user_meta( $user_id, $key, $value );
				}

				// Remove Subscriber Role.
				$usr_obj = new WP_User( $user_id );
				// Remove role
				$usr_obj->remove_role( 'not_verified' );
				// Add role
				$usr_obj->add_role( 'subscriber' );
			} else { // if we do not find the record we clear the user profile and adjust roles.
				$metas = array(
					'callsign'          => null,
					'gmrs_status'       => "Not Verified",
					'licenseexpiredate' => null,
					'gmrs_location'     => null,
					'gmrs_sync'         => null
				);
				//echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
				foreach ( $metas as $key => $value ) {
					update_user_meta( $user_id, $key, $value );
				}

				// Remove Subscriber Role.
				$usr_obj = new WP_User( $user_id );
				// Remove role
				$usr_obj->remove_role( 'subscriber' );
				// Add role
				$usr_obj->add_role( 'not_verified' );
			} // else
		} else {
			$metas = array(
				'callsign'          => null,
				'gmrs_status'       => "Not Verified",
				'licenseexpiredate' => null,
				'gmrs_location'     => null,
				'gmrs_sync'         => null
			);
			//echo '<pre>' . print_r($gmrs_meta,1) . '</pre>';
			foreach ( $metas as $key => $value ) {
				update_user_meta( $user_id, $key, $value );
			}

			// Remove Subscriber Role.
			$usr_obj = new WP_User( $user_id );
			// Remove role
			$usr_obj->remove_role( 'subscriber' );
			// Add role
			$usr_obj->add_role( 'not_verified' );
		}
	} else {
		//$api_online = "Offline";
		## DEBUG EMAIL
		$to = 'esoares9483@gmail.com';
		$subject = get_bloginfo( 'name' ).' - GMRS FCC Plugin';
		$message = "API IS DOWN! - USER PROFILE UPDATE";
		wp_mail( $to, $subject, $message );
		##
	}
}
add_action('wp_login', 'user_login_update_gmrs', 10, 2);

//function do_anything() {
//	//$api_online = "Offline";
//	## DEBUG EMAIL
//	$to = 'esoares9483@gmail.com';
//	$subject = get_bloginfo( 'name' ).' - GMRS FCC Plugin';
//	$message = "USER_LOGIN:  USER:";
//	wp_mail( $to, $subject, $message );
//	##
//}
//add_action('wp_login', 'do_anything');
//function login_user_update_gmrs( $user_login, $user ) {
//		//$api_online = "Offline";
//		## DEBUG EMAIL
//		$to = 'esoares9483@gmail.com';
//		$subject = get_bloginfo( 'name' ).' - GMRS FCC Plugin';
//		$message = "USER_LOGIN: ".$user_login." USER:".$user;
//		wp_mail( $to, $subject, $message );
//		##
//}
//add_action('wp_login', 'login_user_update_gmrs', 10, 2);

function wordplace_user_profile_fields_disable() {

    global $pagenow;

    // apply only to user profile or user edit pages
    if ($pagenow!=='profile.php' && $pagenow!=='user-edit.php') {
        return;
    }

    // do not change anything for the administrator
    if (current_user_can('administrator')) {
        return;
    }

    add_action( 'admin_footer', 'wordplace_user_profile_fields_disable_js' );

}

add_action('admin_init', 'wordplace_user_profile_fields_disable');


/**
 * Disables selected fields on this plugin Admin views (users.php?page=fcc-gmrs-validator)
 */
function wordplace_user_profile_fields_disable_js() {
    ?>
    <script>
        jQuery(document).ready( function($) {
            var fields_to_disable = ['callsign', 'licenseexpiredate', 'gmrs_status', 'gmrs_location'];
            for(i=0; i<fields_to_disable.length; i++) {
                if ( $('#'+ fields_to_disable[i]).length ) {
                    $('#'+ fields_to_disable[i]).attr("disabled", "disabled");
                }
            }
        });
    </script>
    <?php
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fcc-validator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fcc_validator() {

	$plugin = new Fcc_Validator();
	$plugin->run();

}
run_fcc_validator();
