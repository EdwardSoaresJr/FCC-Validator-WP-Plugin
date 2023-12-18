(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 *
	 *
	 *
     $(function () {
        $("#clients_table").DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
            //"buttons": ["csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#clients_table_wrapper .col-md-6:eq(0)');
    });
	 *
	 */

	// Get the current date for the export file.
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = mm + '-' + dd + '-' + yyyy;
    //document.write(today);

    $(document).ready(function() {
        var table = $('#gmrs_users_table').DataTable({
            //"dom": 'Blfrtip',
            "lengthMenu": [
                [25, 50, 100, -1],
                [25, 50, 100, "All"]
            ],
            "initComplete": function() {
                $("#gmrs_users_table").show();
            },
            //"buttons": ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis']
            buttons: [{
                extend: 'collection',
                text: 'Export, Print & Column Options...',
                buttons: [{
                    extend: 'pdf',
                    title: 'GMRS Callsign List ' + today,
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },{
                    extend: 'excel',
                    title: 'GMRS Callsign List ' + today,
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'copy'
                }, {
                    extend: 'csv',
                    title: 'GMRS Callsign List ' + today,
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'print',
                    title: 'GMRS Callsign List ' + today,
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                }, {
                    extend: 'colvis'
                }
                ]
            }
            ]
        });
        table.buttons().container().appendTo('#gmrs_users_table_wrapper .col-md-6:eq(0)');
    });


})( jQuery );