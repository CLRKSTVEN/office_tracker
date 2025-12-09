<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Staff Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- If you have Bootstrap, you can link it -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">

    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(1400px 700px at 10% 0%, #e9f2ff, transparent 55%),
                radial-gradient(1400px 700px at 90% 100%, #eaf3ff, transparent 55%),
                linear-gradient(180deg, #f6f9ff, #eef4ff);
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .card {
            width: 100%;
            max-width: 720px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, .12);
            padding: 22px 24px;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 1.5rem;
        }

        p.subtitle {
            margin-top: 0;
            margin-bottom: 18px;
            color: #64748b;
            font-size: .9rem;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 16px;
        }

        .col-half {
            flex: 1 1 48%;
            min-width: 0;
        }

        .col-full {
            flex: 1 1 100%;
        }

        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 4px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #cbd5f5;
            font-size: 0.9rem;
        }

        textarea {
            min-height: 70px;
            resize: vertical;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.78rem;
            margin-top: 2px;
        }

        .actions {
            margin-top: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        button[type="submit"] {
            padding: 9px 14px;
            border-radius: 999px;
            border: none;
            background: #2563eb;
            color: #ffffff;
            font-weight: 600;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background: #1d4ed8;
        }

        a.link {
            color: #2563eb;
            font-size: .85rem;
            text-decoration: none;
        }

        a.link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .card {
                padding: 18px 16px;
            }

            .col-half {
                flex-basis: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="card">
            <h1>Staff Registration</h1>
            <p class="subtitle">
                Fill out this form to create your staff profile and login account.
            </p>

            <?= form_open('register/save'); ?>

            <div class="row">
                <div class="col-half">
                    <label for="first_name">First Name</label>
                    <input type="text"
                        id="first_name"
                        name="first_name"
                        value="<?= set_value('first_name'); ?>"
                        required>
                    <?= form_error('first_name', '<div class="error-text">', '</div>'); ?>
                </div>
                <div class="col-half">
                    <label for="middle_name">Middle Name</label>
                    <input type="text"
                        id="middle_name"
                        name="middle_name"
                        value="<?= set_value('middle_name'); ?>">
                </div>
                <div class="col-half">
                    <label for="last_name">Last Name</label>
                    <input type="text"
                        id="last_name"
                        name="last_name"
                        value="<?= set_value('last_name'); ?>"
                        required>
                    <?= form_error('last_name', '<div class="error-text">', '</div>'); ?>
                </div>
                <div class="col-half">
                    <label for="suffix">Suffix (optional)</label>
                    <input type="text"
                        id="suffix"
                        name="suffix"
                        value="<?= set_value('suffix'); ?>"
                        placeholder="Jr., III, etc.">
                </div>

                <div class="col-half">
                    <label for="position_title">Position</label>
                    <input type="text"
                        id="position_title"
                        name="position_title"
                        value="<?= set_value('position_title'); ?>"
                        required>
                    <?= form_error('position_title', '<div class="error-text">', '</div>'); ?>
                </div>

                <div class="col-half">
                    <label for="office_id">Office</label>
                    <select id="office_id" name="office_id" required>
                        <option value="">Select office</option>
                        <?php if (!empty($offices)): ?>
                            <?php foreach ($offices as $o): ?>
                                <option value="<?= (int)$o->id; ?>"
                                    <?= set_select('office_id', $o->id); ?>>
                                    <?= html_escape($o->name); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?= form_error('office_id', '<div class="error-text">', '</div>'); ?>
                </div>

                <div class="col-full">
                    <label for="address_id">Current Address (Province / City / Barangay)</label>
                    <select id="address_id" name="address_id" required>
                        <option value="">Select address</option>
                        <?php if (!empty($addresses)): ?>
                            <?php foreach ($addresses as $a): ?>
                                <option value="<?= (int)$a->AddID; ?>"
                                    <?= set_select('address_id', $a->AddID); ?>>
                                    <?= html_escape("{$a->Province} - {$a->City} - {$a->Brgy}"); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?= form_error('address_id', '<div class="error-text">', '</div>'); ?>
                </div>

                <div class="col-full">
                    <label for="short_bio">Brief description of your work (optional)</label>
                    <textarea id="short_bio"
                        name="short_bio"
                        placeholder="What do you do in this office?"><?= set_value('short_bio'); ?></textarea>
                </div>

                <div class="col-half">
                    <label for="username">Login Username</label>
                    <input type="text"
                        id="username"
                        name="username"
                        value="<?= set_value('username'); ?>"
                        required>
                    <?= form_error('username', '<div class="error-text">', '</div>'); ?>
                </div>

                <div class="col-half">
                    <label for="password">Password</label>
                    <input type="password"
                        id="password"
                        name="password"
                        required>
                    <?= form_error('password', '<div class="error-text">', '</div>'); ?>
                </div>

                <div class="col-half">
                    <label for="password_confirm">Confirm Password</label>
                    <input type="password"
                        id="password_confirm"
                        name="password_confirm"
                        required>
                    <?= form_error('password_confirm', '<div class="error-text">', '</div>'); ?>
                </div>
            </div>

            <div class="actions">
                <a href="<?= site_url('login'); ?>" class="link">&larr; Back to login</a>
                <button type="submit">Register</button>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</body>

</html>