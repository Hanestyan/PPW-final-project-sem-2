<?php
// ===================================================
// history_satpam.php - Halaman Riwayat Laporan (Satpam)
// Menampilkan semua laporan dari semua mahasiswa
// ===================================================

session_start();

// Cek apakah user sudah login sebagai satpam atau admin
if (!isset($_SESSION['id_user']) || !in_array($_SESSION['role'], ['satpam', 'admin'])) {
    header("Location: ../../../frontend/index.php");
    exit();
}

// Include controller — mengambil semua data laporan dari database
require_once '../../../backend/controllers/dashadmin.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFEWALK - History</title>
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        :root {
            --bg-primary: #0B0F19; --bg-secondary: #151A24; --bg-tertiary: #1E293B;
            --text-primary: #F8FAFC; --text-secondary: #94A3B8; --border-color: rgba(255,255,255,0.05);
            --hover-bg: rgba(255,255,255,0.02); --input-bg: #0B0F19; --accent: #2563EB; --accent-hover: #1D4ED8;
            --btn-primary: #1D4ED8; --success: #22c55e; --warning: #eab308; --danger: #ef4444;
            --shadow: rgba(0,0,0,0.5); --card-bg: #151A24;
        }
        body { background: var(--bg-primary); color: var(--text-primary); min-height: 100vh; display: flex; }
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 32px 40px; background: var(--bg-primary); }

        .top-header-panel {
            background: var(--card-bg); border-radius: 20px; padding: 20px 32px;
            display: flex; justify-content: space-between; align-items: center;
            border: 1px solid var(--border-color); margin-bottom: 32px;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
        }
        .top-header-panel .header-title { font-size: 18px; font-weight: 600; color: var(--text-primary); }
        .top-header-panel .header-right { display: flex; align-items: center; gap: 32px; }
        .top-header-panel .time { font-size: 24px; font-weight: 500; color: var(--text-primary); letter-spacing: 1px;}
        .top-header-panel .time span { font-size: 12px; color: var(--text-secondary); font-weight: 500; margin-left: 8px; text-transform: uppercase; letter-spacing: 1px;}
        .top-header-panel .status { font-size: 12px; padding: 8px 16px; border-radius: 20px; background: rgba(255, 255, 255, 0.05); color: var(--text-primary); border: 1px solid var(--border-color); display: flex; align-items: center; gap: 8px; font-weight: 500;}
        .top-header-panel .status .dot { width: 8px; height: 8px; background: var(--success); border-radius: 50%; display: inline-block; box-shadow: 0 0 8px var(--success); }

        .history-container { background: var(--card-bg); border-radius: 24px; padding: 32px; border: 1px solid var(--border-color); box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5); }

        .history-header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .history-header-row h2 { font-size: 18px; font-weight: 600; margin: 0; color: var(--text-primary); }
        .filter-group { display: flex; gap: 8px; }
        .btn-filter { padding: 8px 16px; background: transparent; border: 1px solid var(--border-color); border-radius: 20px; color: var(--text-secondary); font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
        .btn-filter.active { background: var(--bg-tertiary); color: var(--text-primary); border-color: transparent; }
        .btn-filter:hover:not(.active) { background: var(--hover-bg); color: var(--text-primary); }

        .history-card {
            background: var(--bg-primary); border-radius: 12px; padding: 16px 20px;
            border: 1px solid var(--border-color); margin-bottom: 12px;
            display: flex; justify-content: space-between; align-items: center;
            transition: all 0.2s;
        }
        .history-card:hover { border-color: var(--text-secondary); }
        .history-card .left { display: flex; align-items: center; gap: 16px; }
        .icon-circle {
            width: 48px; height: 48px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 600; flex-shrink: 0;
        }
        .icon-circle.success { color: var(--success); background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2); }
        .icon-circle.pending { color: var(--accent); background: rgba(59, 130, 246, 0.1);  border: 1px solid rgba(59, 130, 246, 0.2); }
        .icon-circle.warning { color: var(--warning); background: rgba(234, 179, 8, 0.1);  border: 1px solid rgba(234, 179, 8, 0.2); }

        .history-card .left .info .title { display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
        .history-card .left .info .title h4 { font-weight: 600; font-size: 16px; color: var(--text-primary); margin: 0; }
        .history-card .left .info .meta { font-size: 14px; color: var(--text-secondary); line-height: 1.5; margin-top: 0; }
        
        .history-card .right { text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }

        .badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1px solid transparent; }
        .badge-selesai  { color: var(--success); border-color: rgba(34, 197, 94, 0.2); background: transparent; }
        .badge-diproses { color: var(--accent);  border-color: rgba(59, 130, 246, 0.2); background: transparent; }
        .badge-pending  { color: var(--warning); border-color: rgba(234, 179, 8, 0.2); background: transparent; }

        .urgency-badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 6px; letter-spacing: 0.5px; text-transform: uppercase; }
        .urgency-rendah { background: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.2); }
        .urgency-sedang { background: rgba(234, 179, 8, 0.1); color: var(--warning); border: 1px solid rgba(234, 179, 8, 0.2); }
        .urgency-tinggi { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); }

        .history-card-hidden { display: none; }

        .toast { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); background: var(--bg-tertiary); color: var(--text-primary); padding: 12px 24px; border-radius: 10px; font-size: 14px; box-shadow: 0 4px 20px var(--shadow); border: 1px solid var(--border-color); z-index: 9999; animation: slideUp 0.3s ease; max-width: 90vw; text-align: center; display: none; }
        .toast.show { display: block; }
        @keyframes slideUp { from { opacity: 0; transform: translateX(-50%) translateY(20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }

        @media (max-width: 768px) { body { flex-direction: column; } .main-content { padding: 16px; } }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo-side">SAFEWALK</div>
        <nav>
            <a href="dashboard_satpam.php">Dashboard</a>
            <a href="active_alerts.php">Active Alerts</a>
            <a href="history_satpam.php" class="active">History</a>
            <a href="settings_satpam.php">Settings</a>
        </nav>
        <div class="toggle-light">Light Mode</div>
        <div class="user-profile">
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?></div>
            <div class="info">
                <div class="name"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                <div class="role">Satpam</div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="top-header-panel">
            <div class="header-title">Riwayat Laporan</div>
            <div class="header-right">
                <div class="time" id="clock">--:-- <span>---, --- -</span></div>
                <div class="status">
                    <span class="dot"></span> System Status: Secure
                </div>
            </div>
        </div>

        <div class="history-container">
            <div class="history-header-row">
                <h2>Semua Riwayat Laporan</h2>
                <!-- Tombol filter — dikendalikan oleh JavaScript di bawah -->
                <div class="filter-group">
                    <button class="btn-filter active" data-filter="semua">Semua</button>
                    <button class="btn-filter" data-filter="Pending">Pending</button>
                    <button class="btn-filter" data-filter="Diproses">Diproses</button>
                    <button class="btn-filter" data-filter="Selesai">Selesai</button>
                </div>
            </div>

            <?php foreach ($data_laporan as $row): ?>
                <?php
                // Tentukan class berdasarkan status
                // Kolom: status (sesuai gambar database)
                $status = $row['status'];
                if ($status === 'Selesai') {
                    $badge_class = 'badge-selesai';
                    $icon_class  = 'success';
                    $icon_text   = '✓';
                } elseif ($status === 'Diproses') {
                    $badge_class = 'badge-diproses';
                    $icon_class  = 'pending';
                    $icon_text   = '⏳';
                } else {
                    $badge_class = 'badge-pending';
                    $icon_class  = 'warning';
                    $icon_text   = '!';
                }
                ?>
                <!-- data-status digunakan oleh JS untuk filter -->
                <div class="history-card" data-status="<?php echo $status; ?>">
                    <div class="left">
                        <div class="icon-circle <?php echo $icon_class; ?>">
                            <?php echo $icon_text; ?>
                        </div>
                        <div class="info">
                            <div class="title">
                                <h4><?php echo htmlspecialchars($row['deskripsi']); ?></h4>
                                <?php
                                    $urgensi_class = 'urgency-sedang';
                                    if($row['tingkat_urgensi'] === 'Tinggi') $urgensi_class = 'urgency-tinggi';
                                    elseif($row['tingkat_urgensi'] === 'Rendah') $urgensi_class = 'urgency-rendah';
                                ?>
                                <span class="urgency-badge <?php echo $urgensi_class; ?>">
                                    <?php echo htmlspecialchars($row['tingkat_urgensi']); ?>
                                </span>
                            </div>
                            <div class="meta">
                                <span>📍 <?php echo htmlspecialchars($row['lokasi']); ?></span>
                                <span>•</span>
                                <span><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></span>
                            </div>
                            <?php if (!empty($row['foto'])): ?>
                                <div style="margin-top: 12px; width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color); box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                                    <img src="../../uploads/<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto Bukti" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="right">
                        <span class="badge <?php echo $badge_class; ?>"><?php echo $status; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($data_laporan)): ?>
                <p style="text-align: center; color: var(--text-secondary); padding: 40px;">
                    Belum ada riwayat laporan.
                </p>
            <?php endif; ?>
        </div>
    </main>

    <div class="toast" id="toast"></div>

    <script>
        function updateClock() {
            const clockEl = document.getElementById('clock');
            if (!clockEl) return;
            const now    = new Date();
            const days   = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
            const months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
            const jam    = String(now.getHours()).padStart(2, '0');
            const menit  = String(now.getMinutes()).padStart(2, '0');
            clockEl.innerHTML = jam + ':' + menit + ' <span>' + days[now.getDay()] + ', ' + months[now.getMonth()] + ' ' + now.getDate() + '</span>';
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Filter kartu riwayat berdasarkan status
        document.querySelectorAll('.btn-filter').forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Tandai tombol yang aktif
                document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.getAttribute('data-filter'); // 'semua', 'Pending', 'Diproses', 'Selesai'

                // Tampilkan atau sembunyikan kartu sesuai filter
                document.querySelectorAll('.history-card').forEach(function(card) {
                    if (filter === 'semua' || card.getAttribute('data-status') === filter) {
                        card.style.display = 'flex'; // Tampilkan
                    } else {
                        card.style.display = 'none'; // Sembunyikan
                    }
                });
            });
        });

        document.querySelector('.toggle-light').addEventListener('click', function() {
            const root    = document.documentElement.style;
            const isLight = root.getPropertyValue('--bg-primary') === '#f8fafc';
            if (!isLight) {
                root.setProperty('--bg-primary', '#f8fafc'); root.setProperty('--bg-secondary', '#ffffff');
                root.setProperty('--bg-tertiary', '#f1f5f9'); root.setProperty('--text-primary', '#0f172a');
                root.setProperty('--text-secondary', '#64748b'); root.setProperty('--border-color', 'rgba(0,0,0,0.1)');
                root.setProperty('--hover-bg', 'rgba(0,0,0,0.05)'); root.setProperty('--card-bg', '#ffffff');
                this.innerHTML = '☀ Dark Mode';
            } else {
                root.setProperty('--bg-primary', '#0B0F19'); root.setProperty('--bg-secondary', '#151A24');
                root.setProperty('--bg-tertiary', '#1E293B'); root.setProperty('--text-primary', '#F8FAFC');
                root.setProperty('--text-secondary', '#94A3B8'); root.setProperty('--border-color', 'rgba(255,255,255,0.05)');
                root.setProperty('--hover-bg', 'rgba(255,255,255,0.02)'); root.setProperty('--card-bg', '#151A24');
                this.innerHTML = '☀ Light Mode';
            }
        });

        function showToast(message, duration = 3000) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), duration);
        }
    </script>
</body>
</html>