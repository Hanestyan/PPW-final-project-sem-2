<?php
// ===================================================
// history_user.php - Halaman Riwayat Laporan Mahasiswa
// ===================================================

// Include controller yang mengambil data riwayat dari database
// riwayat.php sudah berisi: session_start(), cek login, dan query data
require_once '../../../backend/controllers/riwayat.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFEWALK - Riwayat Laporan</title>
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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

        .history-card {
            background: var(--card-bg); border-radius: 24px; padding: 32px;
            border: 1px solid var(--border-color); margin-bottom: 16px;
            display: flex; justify-content: space-between; align-items: center;
            transition: all 0.2s; box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
        }
        .history-card:hover { border-color: var(--accent); }
        .history-card .left { display: flex; align-items: center; gap: 20px; }

        .icon-circle {
            width: 48px; height: 48px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 600; flex-shrink: 0;
        }
        .icon-circle.selesai   { color: var(--success); background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2); }
        .icon-circle.diproses  { color: var(--accent); background: rgba(59, 130, 246, 0.1);  border: 1px solid rgba(59, 130, 246, 0.2); }
        .icon-circle.dibatalkan { color: var(--danger); background: rgba(239, 68, 68, 0.1);  border: 1px solid rgba(239, 68, 68, 0.2); }
        .icon-circle.pending   { color: var(--warning); background: rgba(234, 179, 8, 0.1);  border: 1px solid rgba(234, 179, 8, 0.2); }

        .history-card .left .title { display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
        .history-card .left .title h4 { font-weight: 600; font-size: 16px; color: var(--text-primary); margin: 0; }
        .history-card .left .desc  { font-size: 14px; color: var(--text-secondary); line-height: 1.5; }
        .history-card .right { text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
        .history-card .right .time { font-size: 13px; color: var(--text-secondary); font-weight: 500; }

        .badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1px solid transparent; }
        .badge-selesai   { color: var(--success); border-color: rgba(34, 197, 94, 0.2); background: transparent; }
        .badge-diproses  { color: var(--accent);  border-color: rgba(59, 130, 246, 0.2); background: transparent; }
        .badge-dibatalkan { color: var(--danger);  border-color: rgba(239, 68, 68, 0.2); background: transparent; }
        .badge-pending   { color: var(--warning); border-color: rgba(234, 179, 8, 0.2); background: transparent; }

        .urgency-badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 6px; letter-spacing: 0.5px; text-transform: uppercase; }
        .urgency-rendah { background: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.2); }
        .urgency-sedang { background: rgba(234, 179, 8, 0.1); color: var(--warning); border: 1px solid rgba(234, 179, 8, 0.2); }
        .urgency-tinggi { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); }

        .btn-delete {
            display: flex; align-items: center; gap: 6px; justify-content: center;
            background: rgba(239, 68, 68, 0.1); color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-delete:hover { background: var(--danger); color: white; }
        .btn-delete svg { width: 14px; height: 14px; }

        .empty-state { background: var(--card-bg); border-radius: 24px; border: 1px solid var(--border-color); padding: 60px 20px; text-align: center; color: var(--text-secondary); font-size: 14px; }

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
            <a href="dashboard_user.php">Dashboard</a>
            <a href="history_user.php" class="active">History</a>
            <a href="settings_user.php">Settings</a>
            <a href="../../../backend/controllers/logout.php" style="color: var(--danger); margin-top: auto;">Logout</a>
        </nav>
        <div class="toggle-light">Light Mode</div>
        <div class="user-profile">
            <div class="avatar" id="avatarInitials">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?>
            </div>
            <div class="info">
                <div class="name" id="sidebarName"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                <div class="role">Mahasiswa</div>
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

        <!-- Tampilkan pesan sukses/error penghapusan -->
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'dihapus'): ?>
            <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: var(--success); padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 24px;">✓ Laporan berhasil dihapus!</div>
        <?php elseif (isset($_GET['error']) && $_GET['error'] === 'gagal_hapus'): ?>
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: var(--danger); padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 24px;">✗ Laporan gagal dihapus. Hanya laporan berstatus Pending yang dapat dihapus.</div>
        <?php endif; ?>

        <?php if (empty($riwayat_user)): ?>
            <!-- Tampilkan pesan jika belum ada riwayat -->
            <div class="empty-state">Belum ada riwayat laporan yang dikirim.</div>
        <?php else: ?>
            <?php foreach ($riwayat_user as $row): ?>
                <?php
                // Tentukan class badge dan icon berdasarkan status laporan
                // Kolom: status (sesuai gambar database)
                $status = $row['status'];

                if ($status === 'Selesai') {
                    $badge_class  = 'badge-selesai';
                    $icon_class   = 'selesai';
                    $icon_huruf   = 'S';
                } elseif ($status === 'Diproses') {
                    $badge_class  = 'badge-diproses';
                    $icon_class   = 'diproses';
                    $icon_huruf   = 'D';
                } elseif ($status === 'Dibatalkan') {
                    $badge_class  = 'badge-dibatalkan';
                    $icon_class   = 'dibatalkan';
                    $icon_huruf   = 'X';
                } else {
                    // Default: Pending
                    $badge_class  = 'badge-pending';
                    $icon_class   = 'pending';
                    $icon_huruf   = 'P';
                }
                ?>
                <div class="history-card">
                    <div class="left">
                        <div class="icon-circle <?php echo $icon_class; ?>">
                            <?php echo $icon_huruf; ?>
                        </div>
                        <div>
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
                            <div class="desc"><?php echo htmlspecialchars($row['lokasi']); ?></div>
                            
                            <?php if (!empty($row['foto'])): ?>
                                <div style="margin-top: 12px; width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color); box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                                    <img src="../../uploads/<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto Bukti" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="right">
                        <span class="badge <?php echo $badge_class; ?>"><?php echo $status; ?></span>
                        <div class="time"><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></div>
                        
                        <!-- Tombol Hapus (Hanya untuk Pending) -->
                        <?php if ($status === 'Pending'): ?>
                        <form action="../../../backend/controllers/deletelaporan.php" method="POST" style="margin-top: 4px;" onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                            <input type="hidden" name="id_laporan" value="<?php echo $row['id_laporan']; ?>">
                            <input type="hidden" name="halaman_asal" value="history">
                            <button type="submit" class="btn-delete" title="Hapus Laporan">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                Hapus
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <div class="toast" id="toast"></div>

    <script>
        // Update jam secara real-time
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

        // Toggle Light/Dark Mode
        document.querySelector('.toggle-light').addEventListener('click', function() {
            const root    = document.documentElement.style;
            const isLight = root.getPropertyValue('--bg-primary') === '#f8fafc';

            if (!isLight) {
                root.setProperty('--bg-primary', '#f8fafc');
                root.setProperty('--bg-secondary', '#ffffff');
                root.setProperty('--bg-tertiary', '#f1f5f9');
                root.setProperty('--text-primary', '#0f172a');
                root.setProperty('--text-secondary', '#64748b');
                root.setProperty('--border-color', 'rgba(0,0,0,0.1)');
                root.setProperty('--hover-bg', 'rgba(0,0,0,0.05)');
                root.setProperty('--input-bg', '#ffffff');
                root.setProperty('--card-bg', '#ffffff');
                this.innerHTML = '☀ Dark Mode';
            } else {
                root.setProperty('--bg-primary', '#0B0F19');
                root.setProperty('--bg-secondary', '#151A24');
                root.setProperty('--bg-tertiary', '#1E293B');
                root.setProperty('--text-primary', '#F8FAFC');
                root.setProperty('--text-secondary', '#94A3B8');
                root.setProperty('--border-color', 'rgba(255,255,255,0.05)');
                root.setProperty('--hover-bg', 'rgba(255,255,255,0.02)');
                root.setProperty('--input-bg', '#0B0F19');
                root.setProperty('--card-bg', '#151A24');
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