<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<?php
$fullName = $this->session->userdata('full_name') ?: $this->session->userdata('username');
$role     = $this->session->userdata('role') ?: 'staff';
$roleLabel = ucwords(strtolower($role));
$isAdmin = strtolower((string) $role) === 'admin';
?>

<style>
    .overview-header {
        border-radius: 18px;
        padding: 18px 24px;
        background: linear-gradient(135deg, #0ea5e9, #22c55e);
        color: #fff;
        box-shadow: 0 20px 35px rgba(15, 23, 42, 0.25);
    }

    .overview-header h4 {
        margin-bottom: 4px;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .overview-header small {
        opacity: 0.85;
    }

    .overview-actions .btn {
        min-width: 170px;
        border-radius: 999px;
    }

    .stat-card {
        border-radius: 18px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: none;
    }

    .stat-card .stat-label {
        font-size: 0.78rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 6px;
    }

    .stat-card .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        color: #0f172a;
    }

    .overview-summary {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 16px;
        background: #fff;
        box-shadow: inset 0 0 0 1px rgba(79, 70, 229, 0.04);
    }

    .overview-summary strong {
        display: block;
        font-size: 0.95rem;
        margin-bottom: 4px;
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
                                <div class="page-title-right text-muted small">
                                    <?= html_escape($fullName); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="overview-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <h4 class="mb-0">Key performance indicators</h4>
                                    <small>Quick insight into the teams you manage and your recent activity.</small>
                                </div>
                                <div class="overview-actions d-flex flex-wrap gap-2">
                                    <?php if (!empty($dashboard_nav)): ?>
                                        <?php foreach ($dashboard_nav as $nav): ?>
                                            <a class="btn btn-outline-light btn-sm" href="<?= html_escape($nav['target']); ?>">
                                                <?= html_escape($nav['label']); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <a class="btn btn-outline-light btn-sm" href="<?= site_url('dashboard'); ?>">Refresh</a>
                                    <?php endif; ?>
                                </div>
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
                        <div class="overview-summary">
                            <strong>Need to act?</strong>
                            <p class="mb-1">
                                Run the quick actions from the navigation: log accomplishments when you have new achievements, or register a colleague to keep the roster up to date.
                            </p>
                            <small class="text-muted">
                                Stats refresh when you log a new accomplishment or add staff.
                            </small>
                        </div>
                    </section>

                </div>
            </div>

            <?php include('includes/footer.php'); ?>
        </div>

        <?php include('includes/themecustomizer.php'); ?>

    </div>

    <?php include('includes/footer_plugins.php'); ?>

</body>

</html>
