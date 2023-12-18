<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://theauthorurl
 * @since      1.0.0
 *
 * @package    Fcc_Validator
 * @subpackage Fcc_Validator/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
// Get all stats for DataTables
$data_table_users = get_users();
?>

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">FCC GMRS Validator - Members List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <table id="gmrs_users_table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>NAME</th>
                            <th>CALLSIGN</th>
                            <th>LOCATION</th>
                        </tr>
                        </thead>
                        <?php
                        if ($data_table_users) {

                            foreach ($data_table_users as $data_table_user){

                                $gmrs_status = get_user_meta( $data_table_user->id, 'gmrs_status', true );
	                            $gmrs_location = get_user_meta( $data_table_user->id, 'gmrs_location', true );
                                if ($gmrs_status != "Suspended" && $gmrs_status != "" && $data_table_user->frn_number != null) {?>


                                    <tr class="">
                                        <! –– NAME ––>
                                        <td>
                                            <div><a href="<?php echo admin_url( "user-edit.php?user_id=".$data_table_user->id ); ?>" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="View/Edit User Profile"><?php echo $data_table_user->first_name . " " . $data_table_user->last_name; ?></a></div>
                                        </td>
                                        <! –– CALLSIGN ––>
                                        <td>
                                            <div><?php if ($data_table_user->callsign) {echo $data_table_user->callsign;} ?></div>
                                        </td>
                                        <! –– LOCATION ––>
                                        <td>
                                            <div><?php if ($gmrs_location) {echo $gmrs_location;} ?></div>
                                        </td>
                                    </tr>
                               <?php } ?>

                            <?php } //foreach
                        } //if
                        ?>

                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section><!-- /.content -->