<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<?php
$fullName = $this->session->userdata('full_name') ?: $this->session->userdata('username');
$role     = $this->session->userdata('role') ?: 'staff';
$roleLabel = ucwords(strtolower($role));
$isAdmin = strtolower((string) $role) === 'admin';
$accomplishments = !empty($accomplishments) ? $accomplishments : [];
$hasStaffProfile = isset($has_staff_profile) ? (bool) $has_staff_profile : true;
?>

<style>
    .overview-header {
        border-radius: 12px;
        padding: 16px 20px;
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid #e2e8f0;
    }

    .overview-header h4 {
        margin-bottom: 2px;
        font-weight: 600;
        letter-spacing: -0.01em;
    }

    .overview-actions .btn {
        min-width: 140px;
        border-radius: 6px;
    }

    .stat-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: none;
    }

    .stat-card .stat-label {
        font-size: 0.82rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        color: #0f172a;
    }

    .simple-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: none;
    }
</style>

<body>

    <div id="wrapper">

        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title mb-0">Office Tracker – <?= html_escape($roleLabel); ?> Dashboard</h4>

                            </div>
                        </div>
                    </div>



                    <?php
                    $stats = isset($stats) ? (array) $stats : [];
                    $statCards = $isAdmin
                        ? [
                            ['label' => 'Active staff', 'value' => $stats['total_staff'] ?? 0, 'meta' => 'Rostered personnel'],
                            ['label' => 'Offices', 'value' => $stats['total_offices'] ?? 0, 'meta' => 'Managed locations'],
                            ['label' => 'Total accomplishments', 'value' => $stats['total_accomplishments'] ?? 0, 'meta' => 'All staff'],
                            ['label' => 'My public posts', 'value' => $stats['public_accomplishments'] ?? 0, 'meta' => 'Visible to visitors'],
                        ]
                        : [
                            ['label' => 'My accomplishments', 'value' => $stats['total_accomplishments'] ?? 0, 'meta' => 'Logged to date'],
                            ['label' => 'Public accomplishments', 'value' => $stats['public_accomplishments'] ?? 0, 'meta' => 'Visible to visitors'],
                        ];
                    ?>
                    <section class="stat-grid mt-3">
                        <div class="row">
                            <?php foreach ($statCards as $card): ?>
                                <div class="col-md-6 col-xl-3 mb-3">
                                    <div class="card stat-card">
                                        <div class="card-body">
                                            <div class="stat-label"><?= html_escape($card['label']); ?></div>
                                            <div class="stat-value"><?= number_format($card['value']); ?></div>
                                            <div class="text-muted">
                                                <?= html_escape($card['meta']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <section class="mt-3">
                        <div class="card simple-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <h5 class="mb-1">My accomplishments</h5>
                                        <small class="text-muted">Recent entries you added from the sidebar.</small>
                                    </div>

                                </div>

                                <?php if (!$hasStaffProfile): ?>
                                    <div class="alert alert-warning mb-0">
                                        Link this account to a staff profile to start logging accomplishments.
                                    </div>
                                <?php elseif (!empty($accomplishments)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm dt-responsive nowrap mb-0"
                                            id="accomplishmentsOverviewTable"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Category</th>
                                                    <th>Dates</th>
                                                    <th>Visibility</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($accomplishments as $item): ?>
                                                    <?php
                                                    $start = $item->start_date ? htmlspecialchars($item->start_date) : 'TBD';
                                                    $end = $item->end_date ? htmlspecialchars($item->end_date) : 'Ongoing';
                                                    $visibilityClass = $item->is_public ? 'badge-success' : 'badge-secondary';
                                                    $visibilityText = $item->is_public ? 'Public' : 'Private';
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($item->title); ?></td>
                                                        <td><?= htmlspecialchars($item->category ?: 'General'); ?></td>
                                                        <td><?= $start; ?> → <?= $end; ?></td>
                                                        <td>
                                                            <span class="badge <?= $visibilityClass; ?>"><?= $visibilityText; ?></span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-0">No accomplishments yet. Add your first one to see it here.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>

                </div>
            </div>

            <?php include('includes/footer.php'); ?>
        </div>

        <?php include('includes/themecustomizer.php'); ?>

    </div>

    <?php include('includes/footer_plugins.php'); ?>
    <script>
        $(function() {
            var $overviewTable = $('#accomplishmentsOverviewTable');
            if ($overviewTable.length && $.fn.DataTable) {
                if ($.fn.DataTable.isDataTable($overviewTable)) {
                    $overviewTable.DataTable().destroy();
                }

                $overviewTable.DataTable({
                    responsive: true,
                    pageLength: 5,
                    lengthChange: false,
                    ordering: true,
                    autoWidth: false,
                    pagingType: 'simple',
                    language: {
                        search: '',
                        searchPlaceholder: 'Search...'
                    },
                    columnDefs: [{
                        targets: -1,
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }]
                });
            }
        });
    </script>

</body>

</html>