<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<?php
// Get session info (set during Login::auth)
$fullName = $this->session->userdata('full_name') ?: $this->session->userdata('username');
$role     = $this->session->userdata('role') ?: 'staff';
?>

<body>

    <div id="wrapper">

        <!-- If your head.php already includes top-nav and sidebar, you can remove these.
         If not, and you have these files, uncomment them. -->
        <?php // include('includes/top-nav-bar.php'); 
        ?>
        <?php // include('includes/sidebar.php'); 
        ?>

        <div class="content-page">
            <div class="content">

                <div class="container-fluid">

                    <!-- Page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="page-title mb-0">
                                    Staff Dashboard
                                </h4>
                                <div class="page-title-right text-right">
                                    <div class="d-block">
                                        <span class="font-weight-semibold">
                                            <?= html_escape($fullName); ?>
                                        </span>
                                    </div>
                                    <small class="text-muted text-uppercase">
                                        <?= html_escape($role); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Welcome / summary row -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-1">Welcome, <?= html_escape($fullName); ?> 👋</h5>
                                    <p class="text-muted mb-3">
                                        This is your staff dashboard. From here, you will later be able to update your
                                        public profile and post accomplishments that will appear on the public staff directory.
                                    </p>

                                    <div class="alert alert-info mb-0">
                                        <i class="mdi mdi-information-outline mr-1"></i>
                                        <span>
                                            Public visitor kiosk is available on the main screen. Only staff who are logged in
                                            can access this dashboard and manage their own information.
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick stats / placeholders -->
                        <div class="col-lg-4">
                            <div class="card shadow-sm mb-2">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-muted font-weight-bold mb-2">
                                        Quick Snapshot
                                    </h6>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">Profile status</span>
                                        <span class="badge badge-soft-success font-weight-semibold">
                                            Active
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted">Public visibility</span>
                                        <span class="badge badge-soft-primary font-weight-semibold">
                                            Shown in directory
                                        </span>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        (These values will later be based on your staff record: <code>is_active</code> and
                                        <code>is_public</code>.)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick links row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        My Profile
                                    </h5>
                                    <p class="text-muted mb-3">
                                        View and update your basic information such as position, office assignment,
                                        and current address. These details appear in the public staff directory.
                                    </p>
                                    <a href="<?= site_url('staff/profile'); ?>"
                                        class="btn btn-primary btn-sm">
                                        Go to My Profile
                                    </a>
                                    <small class="text-muted d-block mt-2">
                                        (You can create this controller later as <code>Staff/profile</code> to edit the
                                        <code>staff</code> table.)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        My Accomplishments
                                    </h5>
                                    <p class="text-muted mb-3">
                                        Manage the list of accomplishments, projects, and initiatives you want to show
                                        on your public profile. These will appear when guests search your name.
                                    </p>
                                    <a href="<?= site_url('staff/accomplishments'); ?>"
                                        class="btn btn-outline-primary btn-sm">
                                        View My Accomplishments
                                    </a>
                                    <small class="text-muted d-block mt-2">
                                        (Later, you can implement <code>Staff/accomplishments</code> with CRUD for the
                                        <code>staff_accomplishments</code> table.)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Placeholder for future DataTable -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">
                                        Recent Accomplishments <small class="text-muted">(sample layout)</small>
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered mb-0" id="recent-accom-table">
                                            <thead>
                                                <tr>
                                                    <th width="28%">Title</th>
                                                    <th width="30%">Office / Program</th>
                                                    <th width="20%">Date</th>
                                                    <th width="22%">Visibility</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Participation in Regional Youth Summit</td>
                                                    <td>Division Youth Formation / SGOD</td>
                                                    <td>2025-02-15</td>
                                                    <td><span class="badge badge-soft-success">Public</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Stakeholder Partnership Signing (MOA)</td>
                                                    <td>Social Mobilization &amp; Networking</td>
                                                    <td>2025-03-01</td>
                                                    <td><span class="badge badge-soft-success">Public</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Internal Office Planning Workshop</td>
                                                    <td>Office of the SDS</td>
                                                    <td>2025-01-10</td>
                                                    <td><span class="badge badge-soft-secondary">Private</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        (This is just a sample table to use your DataTables assets. Later, you can load real
                                        data from <code>staff_accomplishments</code>.)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- container-fluid -->

            </div> <!-- content -->

            <?php include('includes/footer.php'); ?>

        </div> <!-- content-page -->

        <?php include('includes/themecustomizer.php'); ?>

    </div> <!-- wrapper -->

    <!-- Scripts -->
    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/moment/moment.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/jquery-scrollto/jquery.scrollTo.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/fullcalendar/fullcalendar.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/calendar.init.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/jquery.chat.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/jquery.todo.js"></script>
    <script src="<?= base_url(); ?>assets/libs/raphael/raphael.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/morris-js/morris.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/dashboard.init.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/jszip/jszip.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/pdfmake/pdfmake.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/pdfmake/vfs_fonts.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/buttons.html5.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/buttons.print.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.keyTable.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.select.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/datatables.init.js"></script>

    <script>
        // Initialize DataTable for the sample table
        $(function() {
            $('#recent-accom-table').DataTable({
                responsive: true,
                paging: false,
                searching: false,
                info: false
            });
        });
    </script>

</body>

</html>
