<?php
// ===================================================
// dashboard_satpam.php - Halaman Dashboard Satpam/Admin
// ===================================================

session_start();

// Cek apakah user sudah login sebagai satpam atau admin
if (!isset($_SESSION['id_user']) || !in_array($_SESSION['role'], ['satpam', 'admin'])) {
    header("Location: ../../../frontend/index.php");
    exit();
}

// Include controller yang mengambil data laporan dari database
// dashadmin.php berisi: koneksi DB, hitung total, dan ambil semua laporan
require_once '../../../backend/controllers/dashadmin.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFEWALK - Dashboard Satpam</title>
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
        
        .stats-cards { display: flex; gap: 24px; margin-bottom: 32px; }
        .stats-card { flex: 1; padding: 24px 32px; background: var(--card-bg); border-radius: 20px; border: 1px solid var(--border-color); box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5); }
        .stats-card h3 { font-size: 13px; color: var(--text-secondary); margin-bottom: 12px; font-weight: 500;}
        .stats-card .number { font-size: 36px; font-weight: 600; color: var(--text-primary); }

        .dashboard-content { background: var(--card-bg); border-radius: 24px; padding: 32px; border: 1px solid var(--border-color); flex: 1; box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5); }
        .dashboard-content h3 { font-size: 16px; font-weight: 600; margin-bottom: 24px; color: var(--text-primary); }

        .table-laporan { width: 100%; border-collapse: collapse; }
        .table-laporan th { text-align: left; padding: 12px 16px; font-size: 12px; font-weight: 500; color: var(--text-secondary); border-bottom: 1px solid var(--border-color); }
        .table-laporan td { padding: 16px; font-size: 13px; color: var(--text-primary); border-bottom: 1px solid var(--border-color); vertical-align: top; }
        .table-laporan tr:hover td { background: var(--hover-bg); }

        .btn-action { padding: 6px 16px; border-radius: 8px; font-size: 12px; font-weight: 500; cursor: pointer; border: 1px solid transparent; background: transparent; transition: all 0.2s;}
        .btn-action.proses { border-color: rgba(34, 197, 94, 0.3); color: var(--success); }
        .btn-action.proses:hover { background: rgba(34, 197, 94, 0.1); border-color: var(--success);}
        .btn-action.selesai { border-color: rgba(59, 130, 246, 0.3); color: var(--accent); }
        .btn-action.selesai:hover { background: rgba(59, 130, 246, 0.1); border-color: var(--accent);}
        
        .urgensi-tinggi { color: var(--danger); font-weight: 500; }
        .urgensi-sedang { color: var(--warning); font-weight: 500; }
        .urgensi-rendah { color: var(--success); font-weight: 500; }

        .photo-preview { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border-color); }

        .toast { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); background: var(--bg-tertiary); color: var(--text-primary); padding: 12px 24px; border-radius: 10px; font-size: 14px; box-shadow: 0 4px 20px var(--shadow); border: 1px solid var(--border-color); z-index: 9999; animation: slideUp 0.3s ease; max-width: 90vw; text-align: center; display: none; }
        .toast.show { display: block; }
        @keyframes slideUp { from { opacity: 0; transform: translateX(-50%) translateY(20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo-side">SAFEWALK</div>
        <nav>
            <a href="dashboard_satpam.php" class="active">Dashboard</a>
            <a href="active_alerts.php">Active Alerts</a>
            <a href="history_satpam.php">History</a>
            <a href="settings_satpam.php">Settings</a>
        </nav>
        <div class="toggle-light">Light Mode</div>
        <div class="user-profile">
            <div class="avatar" id="avatarInitials">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?>
            </div>
            <div class="info">
                <div class="name" id="sidebarName"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                <div class="role">Satpam</div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="top-header-panel">
            <div class="header-title">SafeWalk Command Center</div>
            <div class="header-right">
                <div class="time" id="clock">--:-- <span>---, --- -</span></div>
                <div class="status">
                    <span class="dot"></span> System Status: Secure
                </div>
            </div>
        </div>

        <div class="stats-cards">
            <div class="stats-card">
                <h3>Pending Reports</h3>
                <div class="number"><?php echo str_pad($total_pending, 2, '0', STR_PAD_LEFT); ?></div>
            </div>
            <div class="stats-card">
                <h3>Sedang Diproses</h3>
                <div class="number"><?php echo str_pad($total_proses, 2, '0', STR_PAD_LEFT); ?></div>
            </div>
            <div class="stats-card">
                <h3>Selesai</h3>
                <div class="number"><?php echo str_pad($total_selesai, 2, '0', STR_PAD_LEFT); ?></div>
            </div>
        </div>

        <div class="dashboard-content">
            <h3>Semua Laporan Masuk</h3>
            <table class="table-laporan">
                <thead>
                    <tr>
                        <th>Lokasi</th>
                        <th>Keterangan</th>
                        <th>Foto Bukti</th>
                        <th>Urgensi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_laporan as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['lokasi']); ?></td>
                        <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                        <td>
                            <?php if (!empty($row['foto'])): ?>
                                <div class="table-photo">
                                    <img src="../../uploads/<?php echo htmlspecialchars($row['foto']); ?>" alt="Bukti" class="photo-preview">
                                </div>
                            <?php else: ?>
                                <span style="font-size: 13px; color: var(--text-secondary); font-style: italic;">Tidak ada foto</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                                $urgClass = 'urgensi-sedang';
                                if($row['tingkat_urgensi'] === 'Tinggi') $urgClass = 'urgensi-tinggi';
                                elseif($row['tingkat_urgensi'] === 'Rendah') $urgClass = 'urgensi-rendah';
                            ?>
                            <span class="<?php echo $urgClass; ?>"><?php echo htmlspecialchars($row['tingkat_urgensi']); ?></span>
                        </td>
                        <td>
                            <form action="../../../backend/controllers/updatestatus.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_laporan" value="<?php echo $row['id_laporan']; ?>">
                                <input type="hidden" name="halaman_asal" value="dashboard">
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <button type="submit" name="status_baru" value="Diproses" class="btn-action proses">Proses</button>
                                <?php elseif ($row['status'] === 'Diproses'): ?>
                                    <button type="submit" name="status_baru" value="Selesai" class="btn-action selesai">Selesai</button>
                                <?php else: ?>
                                    <span style="color: var(--success); font-size: 13px; font-weight: 500;">Tuntas</span>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
                this.textContent = 'Dark Mode';
            } else {
                root.setProperty('--bg-primary', '#0f172a');
                root.setProperty('--bg-secondary', '#151a24');
                root.setProperty('--bg-tertiary', '#1e293b');
                root.setProperty('--text-primary', '#f8fafc');
                root.setProperty('--text-secondary', '#94a3b8');
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