<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Staff Directory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at 20% 20%, rgba(226, 232, 240, 0.4) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(191, 219, 254, 0.4) 0%, transparent 50%),
                linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e0f2fe 100%);
            color: #1e293b;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Full page showcase mode */
        .showcase-mode {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            z-index: 10;
            opacity: 1;
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .showcase-mode.hidden {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
        }

        .showcase-container {
            max-width: 900px;
            width: 100%;
            text-align: center;
        }

        .showcase-header {
            margin-bottom: 40px;
            animation: fadeInDown 0.8s ease;
        }

        .showcase-title {
            font-size: 3rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .showcase-subtitle {
            font-size: 1.1rem;
            color: #64748b;
            font-weight: 500;
        }

        .profile-showcase {
            background: linear-gradient(to bottom right, #ffffff, #f8fafc);
            border-radius: 24px;
            padding: 48px;
            box-shadow:
                0 1px 3px rgba(0, 0, 0, 0.05),
                0 20px 60px rgba(71, 85, 105, 0.15);
            border: 1px solid rgba(226, 232, 240, 0.8);
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .profile-avatar-large {
            width: 140px;
            height: 140px;
            border-radius: 20px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            margin: 0 auto 24px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            color: #1e40af;
            border: 4px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.2);
            animation: scaleIn 0.6s ease 0.4s both;
        }

        .profile-avatar-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name-large {
            font-size: 2.5rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: -0.03em;
            animation: fadeIn 0.6s ease 0.6s both;
        }

        .profile-position-large {
            font-size: 1.3rem;
            color: #3b82f6;
            font-weight: 600;
            margin-bottom: 8px;
            animation: fadeIn 0.6s ease 0.7s both;
        }

        .profile-office-large {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 16px;
            animation: fadeIn 0.6s ease 0.8s both;
        }

        .profile-location-large {
            font-size: 0.95rem;
            color: #94a3b8;
            margin-bottom: 24px;
            animation: fadeIn 0.6s ease 0.9s both;
        }

        .profile-bio-large {
            font-size: 1rem;
            color: #475569;
            line-height: 1.7;
            max-width: 600px;
            margin: 0 auto 24px;
            animation: fadeIn 0.6s ease 1s both;
        }

        .profile-tag-large {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 0.85rem;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            font-weight: 600;
            border: 1px solid rgba(59, 130, 246, 0.2);
            animation: fadeIn 0.6s ease 1.1s both;
        }

        .showcase-action {
            margin-top: 48px;
            animation: fadeIn 0.6s ease 1.2s both;
        }

        .search-toggle-btn {
            padding: 14px 32px;
            border-radius: 12px;
            border: 2px solid #3b82f6;
            background: transparent;
            color: #3b82f6;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .search-toggle-btn:hover {
            background: #3b82f6;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
        }

        /* Search and grid mode */
        .search-grid-mode {
            padding: 24px 16px;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
            pointer-events: none;
        }

        .search-grid-mode.active {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .back-to-showcase {
            max-width: 1200px;
            margin: 0 auto 20px;
            text-align: center;
        }

        .back-btn {
            padding: 10px 24px;
            border-radius: 10px;
            border: 2px solid #cbd5e1;
            background: #ffffff;
            color: #475569;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .back-btn:hover {
            border-color: #3b82f6;
            color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .search-panel,
        .grid-panel {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        .search-panel {
            margin-bottom: 20px;
            padding: 24px;
            background: linear-gradient(to bottom right, #ffffff, #f8fafc);
            border-radius: 14px;
            box-shadow:
                0 1px 3px rgba(0, 0, 0, 0.05),
                0 6px 24px rgba(71, 85, 105, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .search-row {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 16px;
            align-items: end;
        }

        .search-group {
            min-width: 0;
        }

        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 11px 14px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            font-size: 0.9rem;
            outline: none;
            background: #ffffff;
            color: #1e293b;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        input[type="text"]:focus,
        select:focus {
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        input[type="text"]::placeholder {
            color: #cbd5e1;
        }

        .search-btn-wrap {
            display: flex;
            align-items: flex-end;
        }

        button[type="submit"] {
            padding: 11px 24px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
            transition: all 0.2s ease;
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
        }

        button[type="submit"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.35);
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        .grid-panel {
            margin-bottom: 20px;
        }

        .grid-panel.collapsed {
            display: none;
        }

        .grid-placeholder {
            max-width: 1200px;
            margin: 0 auto 18px;
            padding: 18px;
            border-radius: 12px;
            background: #eef2ff;
            color: #1e40af;
            font-weight: 600;
            text-align: center;
            border: 1px solid rgba(59, 130, 246, 0.25);
        }

        .grid-placeholder.hidden {
            display: none;
        }

        .staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 16px;
        }

        .staff-card {
            background: linear-gradient(to bottom right, #ffffff, #f8fafc);
            border-radius: 12px;
            padding: 18px;
            box-shadow:
                0 1px 3px rgba(0, 0, 0, 0.05),
                0 4px 16px rgba(71, 85, 105, 0.06);
            display: flex;
            gap: 14px;
            align-items: flex-start;
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
            animation: fadeInUp 0.4s ease both;
        }

        .staff-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .staff-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .staff-card:nth-child(3) {
            animation-delay: 0.15s;
        }

        .staff-card:nth-child(4) {
            animation-delay: 0.2s;
        }

        .staff-card:nth-child(5) {
            animation-delay: 0.25s;
        }

        .staff-card:nth-child(6) {
            animation-delay: 0.3s;
        }

        .staff-card:hover {
            transform: translateY(-3px);
            box-shadow:
                0 1px 3px rgba(0, 0, 0, 0.05),
                0 12px 32px rgba(59, 130, 246, 0.12);
        }

        .staff-avatar {
            width: 58px;
            height: 58px;
            border-radius: 10px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            flex-shrink: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e40af;
            border: 2px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 3px 12px rgba(59, 130, 246, 0.12);
        }

        .staff-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .staff-name {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            font-size: 1.02rem;
            letter-spacing: -0.02em;
        }

        .staff-meta {
            font-size: 0.85rem;
            color: #475569;
            line-height: 1.5;
        }

        .staff-meta small {
            font-size: 0.8rem;
            color: #64748b;
        }

        .staff-tag {
            display: inline-block;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 6px;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            margin-top: 6px;
            font-weight: 600;
            border: 1px solid rgba(59, 130, 246, 0.15);
        }

        .empty-state {
            text-align: center;
            color: #64748b;
            margin-top: 40px;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 768px) {
            .showcase-mode {
                padding: 20px;
            }

            .showcase-title {
                font-size: 2rem;
            }

            .showcase-subtitle {
                font-size: 0.95rem;
            }

            .profile-showcase {
                padding: 32px 24px;
            }

            .profile-avatar-large {
                width: 110px;
                height: 110px;
                font-size: 2.5rem;
            }

            .profile-name-large {
                font-size: 1.8rem;
            }

            .profile-position-large {
                font-size: 1.1rem;
            }

            .search-row {
                grid-template-columns: 1fr;
            }

            .search-btn-wrap {
                width: 100%;
            }

            button[type="submit"] {
                width: 100%;
            }

            .staff-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php
    $hasQuery        = (!empty($search) || !empty($office));
    $gridPanelClass  = $hasQuery ? '' : 'collapsed';
    ?>

    <div class="page-wrapper">

        <!-- Full Page Showcase Mode -->
        <div class="showcase-mode" id="showcase-mode">
            <div class="showcase-container">
                <div class="showcase-header">
                    <h1 class="showcase-title">Our Team</h1>
                    <p class="showcase-subtitle">Meet the talented professionals who make it all possible</p>
                </div>

                <div class="profile-showcase">
                    <div class="profile-avatar-large" id="showcase-avatar">ST</div>
                    <h2 class="profile-name-large" id="showcase-name">Staff Member</h2>
                    <div class="profile-position-large" id="showcase-position">Position</div>
                    <div class="profile-office-large" id="showcase-office">Office</div>
                    <div class="profile-location-large" id="showcase-location"></div>
                    <div class="profile-bio-large" id="showcase-bio"></div>
                    <div class="profile-tag-large">Public Profile</div>
                </div>

                <div class="showcase-action">
                    <button class="search-toggle-btn" id="show-search-btn">
                        Search All Staff Members
                    </button>
                </div>
            </div>
        </div>

        <!-- Search and Grid Mode -->
        <div class="search-grid-mode <?= $hasQuery ? 'active' : ''; ?>" id="search-grid-mode">
            <div class="back-to-showcase">
                <button class="back-btn" id="back-to-showcase-btn">
                    ← Back to Showcase
                </button>
            </div>

            <div class="search-panel">
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

            <div class="grid-placeholder <?= $hasQuery ? 'hidden' : ''; ?>">
                Staff cards stay hidden until you start searching—your selected profile keeps flashing on the landing screen above.
            </div>

            <div class="grid-panel <?= $gridPanelClass; ?>">
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

    </div>

    <script>
        (function() {
            const staffData = <?php
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

            const showcaseMode = document.getElementById('showcase-mode');
            const searchGridMode = document.getElementById('search-grid-mode');
            const showSearchBtn = document.getElementById('show-search-btn');
            const backToShowcaseBtn = document.getElementById('back-to-showcase-btn');

            const showcaseAvatar = document.getElementById('showcase-avatar');
            const showcaseName = document.getElementById('showcase-name');
            const showcasePosition = document.getElementById('showcase-position');
            const showcaseOffice = document.getElementById('showcase-office');
            const showcaseLocation = document.getElementById('showcase-location');
            const showcaseBio = document.getElementById('showcase-bio');
            const gridPanel = document.querySelector('.grid-panel');
            const gridPlaceholder = document.querySelector('.grid-placeholder');
            const gridCollapsedClass = 'collapsed';

            let currentIndex = 0;
            let timer = null;

            function renderShowcase(index) {
                if (!staffData || staffData.length === 0) {
                    showcaseName.textContent = 'No staff data';
                    showcasePosition.textContent = '';
                    showcaseOffice.textContent = '';
                    showcaseLocation.textContent = '';
                    showcaseBio.textContent = '';
                    showcaseAvatar.textContent = 'ST';
                    showcaseAvatar.innerHTML = 'ST';
                    return;
                }

                const s = staffData[index];

                const fullName = [s.first_name || '', s.last_name || ''].join(' ').trim();
                showcaseName.textContent = fullName || 'Staff Member';

                while (showcaseAvatar.firstChild) showcaseAvatar.removeChild(showcaseAvatar.firstChild);
                if (s.photo) {
                    const img = document.createElement('img');
                    img.src = '<?= base_url('upload/staff/'); ?>' + s.photo;
                    img.alt = 'Photo';
                    showcaseAvatar.appendChild(img);
                } else {
                    let initials = '';
                    if (s.first_name) initials += s.first_name.charAt(0);
                    if (s.last_name) initials += s.last_name.charAt(0);
                    showcaseAvatar.textContent = initials || 'ST';
                }

                showcasePosition.textContent = s.position_title || 'Position';
                showcaseOffice.textContent = s.office_name || 'Office';

                const addrParts = [];
                if (s.Brgy) addrParts.push(s.Brgy);
                if (s.City) addrParts.push(s.City);
                if (s.Province) addrParts.push(s.Province);
                showcaseLocation.textContent = addrParts.join(', ');

                showcaseBio.textContent = s.short_bio || '';
            }

            function startSlideshow() {
                if (!staffData || staffData.length === 0) {
                    renderShowcase(0);
                    return;
                }

                renderShowcase(currentIndex);

                timer = setInterval(function() {
                    currentIndex = (currentIndex + 1) % staffData.length;
                    renderShowcase(currentIndex);
                }, 4000);
            }

            function showSearchMode() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
                showcaseMode.classList.add('hidden');
                setTimeout(() => {
                    searchGridMode.classList.add('active');
                    if (gridPanel) {
                        if (!hasQuery) {
                            gridPanel.classList.add(gridCollapsedClass);
                        } else {
                            gridPanel.classList.remove(gridCollapsedClass);
                        }
                    }
                    if (gridPlaceholder && hasQuery) {
                        gridPlaceholder.classList.add('hidden');
                    }
                }, 300);
            }

            function showShowcaseMode() {
                searchGridMode.classList.remove('active');
                setTimeout(() => {
                    showcaseMode.classList.remove('hidden');
                    startSlideshow();
                }, 300);
            }

            document.addEventListener('DOMContentLoaded', function() {
                if (hasQuery) {
                    showcaseMode.classList.add('hidden');
                    searchGridMode.classList.add('active');
                } else {
                    startSlideshow();
                }

                showSearchBtn.addEventListener('click', showSearchMode);
                backToShowcaseBtn.addEventListener('click', showShowcaseMode);
            });
        })();
    </script>
</body>

</html>
