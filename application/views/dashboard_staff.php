<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<?php
$fullName = $this->session->userdata('full_name') ?: $this->session->userdata('username');
$role     = $this->session->userdata('role') ?: 'staff';
$roleLabel = ucwords(strtolower($role));
$accomplishments = !empty($accomplishments) ? $accomplishments : [];
$accomplishment_categories = !empty($accomplishment_categories) ? $accomplishment_categories : [];
$canManage = !empty($can_manage_accomplishments);
$autoOpenAdd = isset($_GET['open']) && strtolower((string) $_GET['open']) === 'add';
$addresses = !empty($addresses) ? $addresses : [];
?>

<style>
    .quick-nav-card {
        border-radius: 18px;
        border: none;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
    }

    .quick-nav-card .quick-nav-actions .btn {
        border-radius: 999px;
        min-width: 140px;
    }

    .table-card,
    .form-card {
        border-radius: 18px;
        border: none;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
    }

    .table-card .card-body,
    .form-card .card-body {
        min-height: 260px;
    }

    /* Hide theme switcher on staff dashboard to avoid the extra sidebar panel */
    .navbar-custom .right-bar-toggle {
        display: none !important;
    }
</style>

<body data-open-add="<?= $autoOpenAdd ? '1' : '0'; ?>">

    <div id="wrapper">
        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="page-title mb-0">Log accomplishments</h4>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-12">
                        <div class="card table-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <h5 class="card-title mb-1">My accomplishments</h5>
                                        <small class="text-muted">Entries saved under your profile.</small>
                                    </div>
                                    <div>
                                            <button type="button"
                                                class="btn btn-primary btn-sm"
                                                id="btnAddAccomplishment"
                                                data-toggle="modal"
                                                data-target="#accomplishmentModal">
                                                Add accomplishment
                                            </button>
                                        </div>
                                    </div>
                                    <?php if (!$canManage): ?>
                                        <div class="alert alert-warning mb-3">
                                            Your user account is not linked to a staff profile yet. Adding accomplishments requires a staff record.
                                        </div>
                                    <?php endif; ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm dt-responsive nowrap mb-0"
                                            id="accomplishmentsTable"
                                            style="width:100%">
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
                                                        $end = $accomplishment->end_date ? htmlspecialchars($accomplishment->end_date) : 'Ongoing';
                                                        $visibilityClass = $accomplishment->is_public ? 'badge-success' : 'badge-secondary';
                                                        $visibilityText = $accomplishment->is_public ? 'Public' : 'Private';
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
                                                                        data-id="<?= (int) $accomplishment->id; ?>"
                                                                        data-title="<?= htmlspecialchars($accomplishment->title, ENT_QUOTES); ?>"
                                                                        data-category="<?= htmlspecialchars($accomplishment->category, ENT_QUOTES); ?>"
                                                                        data-location="<?= htmlspecialchars($accomplishment->location, ENT_QUOTES); ?>"
                                                                        data-description="<?= htmlspecialchars($accomplishment->description, ENT_QUOTES); ?>"
                                                                        data-start="<?= html_escape($accomplishment->start_date); ?>"
                                                                        data-end="<?= html_escape($accomplishment->end_date); ?>"
                                                                        data-public="<?= $accomplishment->is_public ? '1' : '0'; ?>">
                                                                        Edit
                                                                    </button>
                                                                    <form class="accomplishment-delete-form"
                                                                        action="<?= site_url('login/delete_accomplishment/' . (int) $accomplishment->id); ?>"
                                                                        method="post">
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
                                                            No accomplishments yet.
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

                </div>
            </div>

            <?php include('includes/footer.php'); ?>
        </div>

        <div class="modal fade" id="accomplishmentModal" tabindex="-1" role="dialog" aria-labelledby="accomplishmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="accomplishmentForm"
                        action="<?= site_url('login/save_accomplishment'); ?>"
                        method="post"
                        data-save-action="<?= site_url('login/save_accomplishment'); ?>"
                        data-update-action="<?= site_url('login/update_accomplishment'); ?>">
                        <div class="modal-header">
                            <h5 class="modal-title" id="accomplishmentModalLabel">Add accomplishment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php if (!$canManage): ?>
                                <div class="alert alert-warning">
                                    Your user account is not linked to a staff profile yet. Adding accomplishments requires a staff record.
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="id" id="accomplishmentId" value="">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="accomplishmentTitle">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="accomplishmentTitle"
                                        name="title"
                                        required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="accomplishmentCategory">Category</label>
                                    <input type="text" class="form-control form-control-sm"
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
                                <div class="form-group col-md-4">
                                    <label for="accomplishmentProvince">Province <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm"
                                        id="accomplishmentProvince"
                                        required>
                                        <option value="">Select province</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="accomplishmentCity">City / Municipality <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm"
                                        id="accomplishmentCity"
                                        required>
                                        <option value="">Select city / municipality</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="accomplishmentBarangay">Barangay <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm"
                                        id="accomplishmentBarangay"
                                        required>
                                        <option value="">Select barangay</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="location" id="accomplishmentLocation" value="">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="accomplishmentStart">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-sm"
                                        id="accomplishmentStart"
                                        name="start_date"
                                        required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="accomplishmentEnd">End Date</label>
                                    <input type="date" class="form-control form-control-sm"
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
                                <select class="form-control form-control-sm" name="is_public" id="accomplishmentVisibility">
                                    <option value="1">Public</option>
                                    <option value="0">Private</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="accomplishmentCancelBtn">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm" id="accomplishmentSubmitBtn">
                                Save entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <?php include('includes/footer_plugins.php'); ?>

    <script>
        $(function() {
            var $table = $('#accomplishmentsTable');
            var dataTable = null;
            if ($table.length && $.fn.DataTable) {
                if ($.fn.DataTable.isDataTable($table)) {
                    $table.DataTable().destroy();
                }
                dataTable = $table.DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: false,
                    ordering: true,
                    autoWidth: false,
                    pagingType: 'simple',
                    language: {
                        search: '',
                        searchPlaceholder: 'Search accomplishments'
                    },
                    columnDefs: [{
                        targets: -1,
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }]
                });
            } else {
                console.warn('DataTables plugin is missing; table features are limited.');
            }

            var $modal = $('#accomplishmentModal');
            var $modalTitle = $('#accomplishmentModalLabel');
            var $form = $('#accomplishmentForm');
            var $idField = $('#accomplishmentId');
            var $submitBtn = $('#accomplishmentSubmitBtn');
            var $cancel = $('#accomplishmentCancelBtn');
            var $visibility = $('#accomplishmentVisibility');
            var $addBtn = $('#btnAddAccomplishment');
            var saveAction = $form.data('save-action');
            var updateAction = $form.data('update-action');
            var addressList = <?php
                echo json_encode(array_map(function ($row) {
                    return [
                        'id' => (int) $row->AddID,
                        'province' => $row->Province,
                        'city' => $row->City,
                        'barangay' => $row->Brgy,
                    ];
                }, $addresses));
            ?>;

            var $province = $('#accomplishmentProvince');
            var $city = $('#accomplishmentCity');
            var $barangay = $('#accomplishmentBarangay');
            var $locationField = $('#accomplishmentLocation');

            function populateProvinces() {
                var provinces = Array.from(new Set(addressList.map(function(item) { return item.province; }))).sort();
                $province.empty().append($('<option>').val('').text('Select province'));
                provinces.forEach(function(prov) {
                    $province.append($('<option>').val(prov).text(prov));
                });
            }

            function populateCities(province) {
                $city.empty().append($('<option>').val('').text('Select city / municipality'));
                $barangay.empty().append($('<option>').val('').text('Select barangay'));
                if (!province) { return; }
                var cities = Array.from(new Set(addressList
                    .filter(function(item) { return item.province === province; })
                    .map(function(item) { return item.city; }))).sort();
                cities.forEach(function(city) {
                    $city.append($('<option>').val(city).text(city));
                });
            }

            function populateBarangays(province, city) {
                $barangay.empty().append($('<option>').val('').text('Select barangay'));
                if (!province || !city) { return; }
                var brgys = addressList
                    .filter(function(item) { return item.province === province && item.city === city; })
                    .map(function(item) { return item.barangay; })
                    .filter(function(value, index, self) { return self.indexOf(value) === index; })
                    .sort();
                brgys.forEach(function(brgy) {
                    $barangay.append($('<option>').val(brgy).text(brgy));
                });
            }

            function updateLocationField() {
                var province = $province.val();
                var city = $city.val();
                var brgy = $barangay.val();
                var composed = '';
                if (province || city || brgy) {
                    composed = [province, city, brgy].filter(Boolean).join(' / ');
                }
                $locationField.val(composed);
            }

            function setAddressSelections(province, city, brgy) {
                populateProvinces();
                if (province) {
                    $province.val(province);
                    populateCities(province);
                } else {
                    populateCities('');
                }
                if (province && city) {
                    $city.val(city);
                    populateBarangays(province, city);
                } else {
                    populateBarangays('', '');
                }
                if (province && city && brgy) {
                    $barangay.val(brgy);
                }
                updateLocationField();
            }

            function guessAddressFromLocation(locationText) {
                if (!locationText) {
                    return null;
                }
                var normalized = locationText.toLowerCase().trim();
                var match = addressList.find(function(item) {
                    var variants = [
                        (item.province + ' / ' + item.city + ' / ' + item.barangay).toLowerCase(),
                        (item.province + ' - ' + item.city + ' - ' + item.barangay).toLowerCase(),
                        (item.province + ', ' + item.city + ', ' + item.barangay).toLowerCase(),
                        (item.province + ' ' + item.city + ' ' + item.barangay).toLowerCase(),
                    ];
                    return variants.indexOf(normalized) !== -1;
                });
                if (match) {
                    return match;
                }
                var parts = locationText.split(/[-,/]/).map(function(part) { return part.trim(); }).filter(Boolean);
                return {
                    province: parts[0] || '',
                    city: parts[1] || '',
                    barangay: parts[2] || ''
                };
            }

            function resetForm() {
                $form.attr('action', saveAction);
                $idField.val('');
                $form.trigger('reset');
                $visibility.val('1');
                $submitBtn.text('Save entry');
                $modalTitle.text('Add accomplishment');
                setAddressSelections('', '', '');
            }

            $addBtn.on('click', function() {
                resetForm();
                $modal.modal('show');
            });

            $table.on('click', '.btn-edit-accomplishment', function(event) {
                event.preventDefault();
                event.stopPropagation();
                var $btn = $(this);
                $form.attr('action', updateAction);
                $idField.val($btn.data('id'));
                $('#accomplishmentTitle').val($btn.data('title'));
                $('#accomplishmentCategory').val($btn.data('category'));
                $('#accomplishmentDescription').val($btn.data('description'));
                $('#accomplishmentStart').val($btn.data('start'));
                $('#accomplishmentEnd').val($btn.data('end'));
                var guessed = guessAddressFromLocation($btn.data('location'));
                if (guessed) {
                    setAddressSelections(guessed.province, guessed.city, guessed.barangay);
                } else {
                    setAddressSelections('', '', '');
                }
                $('#accomplishmentLocation').val($btn.data('location'));
                $visibility.val($btn.data('public'));
                $submitBtn.text('Update entry');
                $modalTitle.text('Edit accomplishment');
                $modal.modal('show');
            });

            $table.on('submit', '.accomplishment-delete-form', function(event) {
                event.stopPropagation();
                var confirmed = window.confirm('Delete this accomplishment?');
                if (!confirmed) {
                    event.preventDefault();
                    return;
                }
                $(this).find('button[type="submit"]').prop('disabled', true);
            });

            $cancel.on('click', function() {
                $modal.modal('hide');
            });

            $modal.on('hidden.bs.modal', function() {
                resetForm();
            });

            $province.on('change', function() {
                populateCities($(this).val());
                updateLocationField();
            });

            $city.on('change', function() {
                populateBarangays($province.val(), $(this).val());
                updateLocationField();
            });

            $barangay.on('change', function() {
                updateLocationField();
            });

            $form.on('submit', function(event) {
                updateLocationField();
                if (!$province.val() || !$city.val() || !$barangay.val()) {
                    event.preventDefault();
                    alert('Please select province, city/municipality, and barangay.');
                    return false;
                }
            });

            // Auto-open add modal when triggered via sidebar link (e.g., ?open=add)
            var params = new URLSearchParams(window.location.search);
            var shouldAutoOpen = params.get('open') === 'add' || $('body').data('open-add') === 1 || $('body').data('open-add') === '1';
            if (shouldAutoOpen) {
                resetForm();
                $modal.modal('show');
            }

            resetForm();
        });
    </script>

</body>

</html>
