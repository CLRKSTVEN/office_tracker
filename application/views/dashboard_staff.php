<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<?php
// Get session info (set during Login::auth)
$fullName        = $this->session->userdata('full_name') ?: $this->session->userdata('username');
$role            = $this->session->userdata('role') ?: 'staff';
$roleLabel       = !empty($role) ? ucfirst(strtolower($role)) : 'Staff';
$dashboardHeading = "{$roleLabel} Dashboard";

// Fallbacks to avoid undefined variable notices
$stats                     = isset($stats) ? (array) $stats : [];
$accomplishments           = isset($accomplishments) ? $accomplishments : [];
$accomplishment_categories = isset($accomplishment_categories) ? $accomplishment_categories : [];
$can_manage_accomplishments = isset($can_manage_accomplishments) ? $can_manage_accomplishments : true;
?>

<style>
    .stat-card {
        border-radius: 16px;
        min-height: 140px;
        border: 0;
    }

    .stat-title {
        font-size: 0.78rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: #111827;
    }

    .stat-meta {
        font-size: 0.85rem;
        color: #64748b;
    }
</style>

<body>

    <div id="wrapper">

        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">

                <div class="container-fluid">

                    <!-- Page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="page-title mb-0">
                                    <?= html_escape($dashboardHeading); ?>
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
                            <div class="card shadow-sm mt-3">
                                <div class="card-body">
                                    <h6 class="font-weight-bold mb-2">Quick form</h6>
                                    <p class="text-muted small mb-3">
                                        Jump straight to the accomplishment log when you need to record something new.
                                    </p>
                                    <a href="#formSection" class="btn btn-sm btn-outline-primary btn-block">
                                        Open editor
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alerts -->
                    <div class="row">
                        <div class="col-12">
                            <?php if ($this->session->flashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    <?= $this->session->flashdata('success'); ?>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            <?php endif; ?>

                            <?php if ($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <?= $this->session->flashdata('error'); ?>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Stats row -->
                    <section id="statsSection">
                        <div class="row">
                            <?php
                            $statCards = [
                                ['title' => 'Active staff',            'value' => $stats['total_staff'] ?? 0,             'meta' => 'Rostered personnel'],
                                ['title' => 'Offices',                 'value' => $stats['total_offices'] ?? 0,           'meta' => 'Supported locations'],
                                ['title' => 'Accomplishments logged',  'value' => $stats['total_accomplishments'] ?? 0,   'meta' => 'Across all staff'],
                                ['title' => 'My public items',         'value' => $stats['public_accomplishments'] ?? 0,  'meta' => 'Visible to visitors'],
                            ];
                            ?>
                            <?php foreach ($statCards as $card): ?>
                                <div class="col-md-6 col-xl-3 mb-3">
                                    <div class="card stat-card shadow-sm">
                                        <div class="card-body">
                                            <div class="stat-title"><?= html_escape($card['title']); ?></div>
                                            <div class="stat-value"><?= number_format((float)$card['value']); ?></div>
                                            <div class="stat-meta"><?= html_escape($card['meta']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <!-- My accomplishments table -->
                    <section id="accomplishmentsSection" class="mt-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div>
                                                <h5 class="card-title mb-1">My accomplishments</h5>
                                                <small class="text-muted">Entries saved under your staff profile.</small>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="accomplishmentsTable">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Category</th>
                                                        <th>Dates</th>
                                                        <th>Visibility</th>
                                                        <th style="width: 150px;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($accomplishments)): ?>
                                                        <?php foreach ($accomplishments as $accomplishment): ?>
                                                            <?php
                                                            $start = $accomplishment->start_date ? htmlspecialchars($accomplishment->start_date) : 'TBD';
                                                            $end   = $accomplishment->end_date ? htmlspecialchars($accomplishment->end_date) : 'Ongoing';
                                                            $visibilityClass = $accomplishment->is_public ? 'badge-success' : 'badge-secondary';
                                                            $visibilityText  = $accomplishment->is_public ? 'Public' : 'Private';
                                                            ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($accomplishment->title); ?></td>
                                                                <td><?= htmlspecialchars($accomplishment->category ?: 'General'); ?></td>
                                                                <td><?= $start; ?> → <?= $end; ?></td>
                                                                <td>
                                                                    <span class="badge <?= $visibilityClass; ?>"><?= $visibilityText; ?></span>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group" role="group">
                                                                        <button type="button"
                                                                            class="btn btn-outline-primary btn-sm btn-edit-accomplishment"
                                                                            data-id="<?= (int)$accomplishment->id; ?>"
                                                                            data-title="<?= htmlspecialchars($accomplishment->title, ENT_QUOTES); ?>"
                                                                            data-category="<?= htmlspecialchars($accomplishment->category, ENT_QUOTES); ?>"
                                                                            data-location="<?= htmlspecialchars($accomplishment->location, ENT_QUOTES); ?>"
                                                                            data-description="<?= htmlspecialchars($accomplishment->description, ENT_QUOTES); ?>"
                                                                            data-start="<?= html_escape($accomplishment->start_date); ?>"
                                                                            data-end="<?= html_escape($accomplishment->end_date); ?>"
                                                                            data-public="<?= $accomplishment->is_public ? '1' : '0'; ?>">
                                                                            Edit
                                                                        </button>
                                                                        <form action="<?= site_url('login/delete_accomplishment/' . (int)$accomplishment->id); ?>"
                                                                            method="post"
                                                                            onsubmit="return confirm('Delete this accomplishment?');">
                                                                            <button type="submit"
                                                                                class="btn btn-outline-danger btn-sm">
                                                                                Delete
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">
                                                                No accomplishments yet. Use the form below to add your first entry.
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Add / edit accomplishment form -->
                    <section id="formSection" class="mt-2 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div>
                                                <h5 class="card-title mb-1">Add or edit accomplishment</h5>
                                                <small class="text-muted">All fields are saved to your profile.</small>
                                            </div>
                                        </div>

                                        <?php if (empty($can_manage_accomplishments)): ?>
                                            <div class="alert alert-warning">
                                                Your user account is not linked to a staff profile yet.
                                                Adding accomplishments requires a staff record.
                                            </div>
                                        <?php endif; ?>

                                        <form id="accomplishmentForm"
                                            action="<?= site_url('login/save_accomplishment'); ?>"
                                            method="post"
                                            data-save-action="<?= site_url('login/save_accomplishment'); ?>"
                                            data-update-action="<?= site_url('login/update_accomplishment'); ?>">

                                            <input type="hidden" name="id" id="accomplishmentId" value="">

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="accomplishmentTitle">
                                                        Title <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text"
                                                        class="form-control form-control-sm"
                                                        id="accomplishmentTitle"
                                                        name="title"
                                                        required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="accomplishmentCategory">Category</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm"
                                                        id="accomplishmentCategory"
                                                        name="category"
                                                        list="accomplishmentCategories"
                                                        placeholder="e.g., Awards, Training">
                                                    <datalist id="accomplishmentCategories">
                                                        <?php foreach ($accomplishment_categories as $category): ?>
                                                            <option value="<?= htmlspecialchars($category); ?>"></option>
                                                        <?php endforeach; ?>
                                                    </datalist>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="accomplishmentLocation">Location</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm"
                                                        id="accomplishmentLocation"
                                                        name="location">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="accomplishmentStart">
                                                        Start Date <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="date"
                                                        class="form-control form-control-sm"
                                                        id="accomplishmentStart"
                                                        name="start_date"
                                                        required>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="accomplishmentEnd">End Date</label>
                                                    <input type="date"
                                                        class="form-control form-control-sm"
                                                        id="accomplishmentEnd"
                                                        name="end_date">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="accomplishmentDescription">Description</label>
                                                <textarea class="form-control form-control-sm"
                                                    id="accomplishmentDescription"
                                                    name="description"
                                                    rows="3"
                                                    placeholder="Keep it concise, this will appear on your profile."></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label>Visibility</label>
                                                <select class="form-control form-control-sm"
                                                    name="is_public"
                                                    id="accomplishmentVisibility">
                                                    <option value="1">Public</option>
                                                    <option value="0">Private</option>
                                                </select>
                                            </div>

                                            <div class="d-flex align-items-center gap-2">
                                                <button type="submit"
                                                    class="btn btn-primary btn-sm"
                                                    id="accomplishmentSubmitBtn">
                                                    Save entry
                                                </button>
                                                <button type="button"
                                                    class="btn btn-outline-secondary btn-sm"
                                                    id="accomplishmentCancelBtn">
                                                    Reset
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

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
        $(function() {
            // DataTable for accomplishments
            var $table = $('#accomplishmentsTable');
            if ($.fn.DataTable) {
                $table.DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: false,
                    ordering: true,
                    columnDefs: [{
                        targets: -1,
                        orderable: false,
                        searchable: false
                    }]
                });
            }

            var $form = $('#accomplishmentForm');
            var $idField = $('#accomplishmentId');
            var $submitBtn = $('#accomplishmentSubmitBtn');
            var $cancelBtn = $('#accomplishmentCancelBtn');
            var $visibility = $('#accomplishmentVisibility');
            var saveAction = $form.data('save-action');
            var updateAction = $form.data('update-action');

            function resetForm() {
                $form.attr('action', saveAction);
                $idField.val('');
                $form.trigger('reset');
                $submitBtn.text('Save entry');
            }

            $('.btn-edit-accomplishment').on('click', function() {
                var $btn = $(this);
                $form.attr('action', updateAction);
                $idField.val($btn.data('id'));
                $('#accomplishmentTitle').val($btn.data('title'));
                $('#accomplishmentCategory').val($btn.data('category'));
                $('#accomplishmentLocation').val($btn.data('location'));
                $('#accomplishmentDescription').val($btn.data('description'));
                $('#accomplishmentStart').val($btn.data('start'));
                $('#accomplishmentEnd').val($btn.data('end'));
                $visibility.val($btn.data('public'));
                $submitBtn.text('Update entry');

                $('html, body').animate({
                    scrollTop: $('#formSection').offset().top - 80
                }, 300);
            });

            $cancelBtn.on('click', function() {
                resetForm();
            });

            resetForm();
        });
    </script>

</body>

</html>
