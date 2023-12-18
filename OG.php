<?php
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

/*
 * Make our "Registration date" column sortable
 * @param array $columns Array of all user sortable columns {column ID} => {orderby GET-param}
 */
add_filter( 'manage_users_sortable_columns', 'rudr_make_registered_column_sortable' );

function rudr_make_registered_column_sortable( $columns ) {
    return wp_parse_args( array( 'registration_date' => 'registered' ), $columns );
}

function new_contact_methods( $contactmethods ) {
    $contactmethods['callsign'] = 'GMRS Call Sign';
    $contactmethods['licenseexpiredate'] = 'License Expire Date';
    return $contactmethods;
}
add_filter( 'user_contactmethods', 'new_contact_methods', 10, 1 );


function new_modify_user_table( $column ) {
    $column['callsign'] = 'GMRS Call Sign';
    $column['licenseexpiredate'] = 'License Expire Date';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'callsign' :
            return get_the_author_meta( 'callsign', $user_id );
        case 'licenseexpiredate' :
            return get_the_author_meta( 'licenseexpiredate', $user_id );
        default:
    }
    return $val;
}