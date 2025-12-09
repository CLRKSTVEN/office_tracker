<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Staff Directory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(1400px 700px at 10% 0%, #e9f2ff, transparent 55%),
                radial-gradient(1400px 700px at 90% 100%, #eaf3ff, transparent 55%),
                linear-gradient(180deg, #f6f9ff, #eef4ff);
            color: #0f172a;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .flash-wrapper {
            max-width: 900px;
            width: 100%;
            margin: 0 auto 20px;
            padding: 24px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, .12);
        }

        .flash-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
        }

        .flash-sub {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        .flash-card {
            display: flex;
            gap: 16px;
            align-items: center;
            padding: 16px;
            border-radius: 16px;
            background: linear-gradient(135deg, #eef2ff, #e0f2fe);
        }

        .flash-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #dbeafe;
            flex-shrink: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 700;
            color: #1d4ed8;
        }

        .flash-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .flash-info-name {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .flash-info-main {
            font-size: 0.95rem;
            color: #475569;
        }

        .flash-info-main span {
            display: block;
        }

        .flash-info-bio {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 6px;
            max-height: 3.4em;
            overflow: hidden;
        }

        .flash-tag {
            display: inline-block;
            margin-top: 8px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.78rem;
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 500;
        }

        /* Search + grid panel (hidden until click) */

        .search-panel,
        .grid-panel {
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
        }

        .search-panel {
            margin-bottom: 12px;
            padding: 14px;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .08);
        }

        .search-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .search-group {
            flex: 1 1 260px;
            min-width: 0;
        }

        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 4px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #cbd5f5;
            font-size: 0.9rem;
            outline: none;
        }

        input[type="text"]:focus,
        select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.25);
        }

        .search-btn-wrap {
            display: flex;
            align-items: flex-end;
            flex: 0 0 120px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 9px 12px;
            border-radius: 999px;
            border: none;
            background: #2563eb;
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background: #1d4ed8;
        }

        .grid-panel {
            margin-bottom: 20px;
        }

        .staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 14px;
        }

        .staff-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 8px 18px rgba(15, 23, 42, .06);
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .staff-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #dbeafe;
            flex-shrink: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1d4ed8;
        }

        .staff-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .staff-name {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .staff-meta {
            font-size: 0.82rem;
            color: #64748b;
            line-height: 1.3;
        }

        .staff-meta small {
            font-size: 0.78rem;
        }

        .staff-tag {
            display: inline-block;
            font-size: 0.72rem;
            padding: 2px 8px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            margin-top: 4px;
        }

        .empty-state {
            text-align: center;
            color: #94a3b8;
            margin-top: 30px;
            font-size: 0.95rem;
        }

        /* Hidden by default; shown after click or when there is a query */
        .hidden-initial {
            display: none;
        }

        @media (max-width: 768px) {
            .page-wrapper {
                padding: 12px;
            }

            .flash-wrapper {
                padding: 18px;
            }

            .flash-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-row {
                flex-direction: column;
            }

            .search-btn-wrap {
                flex: 1 1 auto;
            }
        }
    </style>
</head>

<body>
    <?php
    // Determine if there is an active search/filter.
    // If there is, we will show the search + grid immediately and not auto-rotate.
    $hasQuery = (!empty($search) || !empty($office));
    ?>

    <div class="page-wrapper">

        <!-- Flashing single profile area -->
        <div class="flash-wrapper">
            <div class="flash-title">Staff profiles</div>
            <div class="flash-sub">
                <?php if ($hasQuery): ?>
                    Search results are shown below.
                <?php else: ?>
                    Screen is in slideshow mode. Tap anywhere to stop and search.
                <?php endif; ?>
            </div>

            <div class="flash-card" id="flash-card">
                <div class="flash-avatar" id="flash-avatar">
                    <!-- Filled by JS -->
                </div>
                <div>
                    <div class="flash-info-name" id="flash-name">No staff data</div>
                    <div class="flash-info-main">
                        <span id="flash-position"></span>
                        <span id="flash-office"></span>
                        <span id="flash-address"></span>
                    </div>
                    <div class="flash-info-bio" id="flash-bio"></div>
                    <div class="flash-tag" id="flash-tag" style="display:none;">Public profile</div>
                </div>
            </div>
        </div>

        <!-- Search panel: hidden initially if no interaction and no query -->
        <div class="search-panel <?= $hasQuery ? '' : 'hidden-initial'; ?>" id="search-panel">
            <form method="get">
                <div class="search-row">
                    <div class="search-group">
                        <label>Search by name or position</label>
                        <input type="text"
                            name="q"
                            placeholder="e.g. Juan, Specialist, Clerk"
                            value="<?= isset($search) ? html_escape($search) : ''; ?>">
                    </div>
                    <div class="search-group">
                        <label>Office</label>
                        <select name="office">
                            <option value="">All offices</option>
                            <?php if (!empty($offices)): ?>
                                <?php foreach ($offices as $o): ?>
                                    <option value="<?= (int) $o->id; ?>"
                                        <?= (isset($office) && $office == $o->id) ? 'selected' : ''; ?>>
                                        <?= html_escape($o->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="search-btn-wrap">
                        <button type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Staff list panel: hidden initially if no interaction and no query -->
        <div class="grid-panel <?= $hasQuery ? '' : 'hidden-initial'; ?>" id="grid-panel">
            <?php if (!empty($staff)): ?>
                <div class="staff-grid">
                    <?php foreach ($staff as $s): ?>
                        <div class="staff-card">
                            <div class="staff-avatar">
                                <?php if (!empty($s->photo)): ?>
                                    <img src="<?= base_url('upload/staff/' . $s->photo); ?>" alt="Photo">
                                <?php else: ?>
                                    <?php
                                    $initials = '';
                                    if (!empty($s->first_name)) {
                                        $initials .= mb_substr($s->first_name, 0, 1);
                                    }
                                    if (!empty($s->last_name)) {
                                        $initials .= mb_substr($s->last_name, 0, 1);
                                    }
                                    echo html_escape($initials ?: 'ST');
                                    ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="staff-name">
                                    <?= html_escape(trim($s->first_name . ' ' . $s->last_name)); ?>
                                </div>
                                <div class="staff-meta">
                                    <?= html_escape($s->position_title); ?><br>
                                    <?= html_escape($s->office_name); ?>
                                    <?php if (!empty($s->City) || !empty($s->Province) || !empty($s->Brgy)): ?>
                                        <br>
                                        <small>
                                            <?= html_escape($s->Brgy); ?>
                                            <?= $s->Brgy ? ', ' : ''; ?>
                                            <?= html_escape($s->City); ?>
                                            <?= $s->City ? ', ' : ''; ?>
                                            <?= html_escape($s->Province); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($s->short_bio)): ?>
                                    <div class="staff-meta" style="margin-top: 4px;">
                                        <small><?= html_escape($s->short_bio); ?></small>
                                    </div>
                                <?php endif; ?>
                                <div class="staff-tag">Public profile</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    No staff found. Try adjusting your search.
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script>
        (function() {
            // Staff data for the flashing/slideshow area.
            const staffData = <?php
                                // We only need a subset of fields for the flash card.
                                $simple = [];
                                if (!empty($staff)) {
                                    foreach ($staff as $s) {
                                        $simple[] = [
                                            'first_name'      => $s->first_name,
                                            'last_name'       => $s->last_name,
                                            'position_title'  => $s->position_title,
                                            'office_name'     => $s->office_name,
                                            'Brgy'            => $s->Brgy,
                                            'City'            => $s->City,
                                            'Province'        => $s->Province,
                                            'short_bio'       => $s->short_bio,
                                            'photo'           => $s->photo,
                                        ];
                                    }
                                }
                                echo json_encode($simple);
                                ?>;

            const hasQuery = <?= $hasQuery ? 'true' : 'false'; ?>;

            const avatarEl = document.getElementById('flash-avatar');
            const nameEl = document.getElementById('flash-name');
            const posEl = document.getElementById('flash-position');
            const officeEl = document.getElementById('flash-office');
            const addrEl = document.getElementById('flash-address');
            const bioEl = document.getElementById('flash-bio');
            const tagEl = document.getElementById('flash-tag');

            const searchPanel = document.getElementById('search-panel');
            const gridPanel = document.getElementById('grid-panel');

            let currentIndex = 0;
            let timer = null;
            let searchShown = hasQuery;

            function renderStaff(index) {
                if (!staffData || staffData.length === 0) {
                    nameEl.textContent = 'No staff data';
                    posEl.textContent = '';
                    officeEl.textContent = '';
                    addrEl.textContent = '';
                    bioEl.textContent = '';
                    tagEl.style.display = 'none';
                    avatarEl.textContent = 'ST';
                    avatarEl.innerHTML = 'ST';
                    return;
                }

                const s = staffData[index];

                // Name
                const fullName = [s.first_name || '', s.last_name || ''].join(' ').trim();
                nameEl.textContent = fullName || 'Staff';

                // Avatar (photo or initials)
                while (avatarEl.firstChild) avatarEl.removeChild(avatarEl.firstChild);
                if (s.photo) {
                    const img = document.createElement('img');
                    img.src = '<?= base_url('upload/staff/'); ?>' + s.photo;
                    img.alt = 'Photo';
                    avatarEl.appendChild(img);
                } else {
                    let initials = '';
                    if (s.first_name) initials += s.first_name.charAt(0);
                    if (s.last_name) initials += s.last_name.charAt(0);
                    avatarEl.textContent = initials || 'ST';
                }

                posEl.textContent = s.position_title || '';
                officeEl.textContent = s.office_name || '';

                const addrParts = [];
                if (s.Brgy) addrParts.push(s.Brgy);
                if (s.City) addrParts.push(s.City);
                if (s.Province) addrParts.push(s.Province);
                addrEl.textContent = addrParts.join(', ');

                bioEl.textContent = s.short_bio || '';
                tagEl.style.display = 'inline-block';
            }

            function startSlideshow() {
                if (!staffData || staffData.length === 0 || hasQuery) {
                    // No slideshow when there is a search query (user is already interacting)
                    renderStaff(0);
                    return;
                }

                renderStaff(currentIndex);

                timer = setInterval(function() {
                    currentIndex = (currentIndex + 1) % staffData.length;
                    renderStaff(currentIndex);
                }, 3000); // 3 seconds per profile
            }

            document.addEventListener('DOMContentLoaded', function() {
                startSlideshow();

                // On first click anywhere: stop slideshow and show search + grid.
                if (!hasQuery) {
                    document.addEventListener('click', function handleClick() {
                        if (searchShown) return;
                        searchShown = true;

                        if (timer) {
                            clearInterval(timer);
                            timer = null;
                        }

                        if (searchPanel) searchPanel.classList.remove('hidden-initial');
                        if (gridPanel) gridPanel.classList.remove('hidden-initial');
                    }, {
                        once: true
                    });
                } else {
                    // If query already present, ensure panels are visible.
                    if (searchPanel) searchPanel.classList.remove('hidden-initial');
                    if (gridPanel) gridPanel.classList.remove('hidden-initial');
                }
            });
        })();
    </script>
</body>

</html>