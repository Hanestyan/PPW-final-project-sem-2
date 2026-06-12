<?php
session_start();
require_once '../../../backend/controllers/dashadmin.php';
// views/satpam/active_alerts.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFEWALK - Active Alerts</title>
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        :root {
            --bg-primary: #0f172a; --bg-secondary: #1e293b; --bg-tertiary: #334155;
            --text-primary: #f8fafc; --text-secondary: #94a3b8; --border-color: rgba(255,255,255,0.05);
            --hover-bg: rgba(255,255,255,0.05); --input-bg: #0f172a; --accent: #3b82f6; --accent-hover: #2563eb;
            --btn-primary: #2563eb; --success: #22c55e; --warning: #eab308; --danger: #ef4444;
            --shadow: rgba(0,0,0,0.5); --card-bg: #1e293b;
        }
        body { background: var(--bg-primary); color: var(--text-primary); min-height: 100vh; display: flex; }
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 32px 40px; background: var(--bg-primary); }

        .top-header-panel {
            background: var(--card-bg); border-radius: 16px; padding: 20px 24px;
            display: flex; justify-content: space-between; align-items: center;
            border: 1px solid var(--border-color); margin-bottom: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .top-header-panel .header-title { font-size: 18px; font-weight: 600; color: var(--text-primary); }
        .top-header-panel .header-right { display: flex; align-items: center; gap: 24px; }
        .top-header-panel .time { font-size: 20px; font-weight: 500; color: var(--text-primary); }
        .top-header-panel .time span { font-size: 13px; color: var(--text-secondary); font-weight: 400; margin-left: 8px;}
        .top-header-panel .status { font-size: 13px; padding: 6px 14px; border-radius: 20px; background: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.2); display: flex; align-items: center; gap: 8px; font-weight: 500;}
        .top-header-panel .status .dot { width: 8px; height: 8px; background: var(--success); border-radius: 50%; display: inline-block; box-shadow: 0 0 8px var(--success); }

        .table-container { background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border-color); overflow-x: auto; box-shadow: 0 4px 20px rgba(0,0,0,0.2); padding: 8px;}
        table { width: 100%; border-collapse: collapse; }
        thead th { text-align: left; padding: 16px 20px; font-size: 13px; font-weight: 600; color: var(--text-secondary); border-bottom: 1px solid var(--border-color); background: transparent; }
        tbody td { padding: 16px 20px; font-size: 14px; border-bottom: 1px solid var(--border-color); vertical-align: middle; color: var(--text-primary); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: var(--hover-bg); border-radius: 8px;}

        .table-photo { width: 56px; height: 56px; background: var(--bg-tertiary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 11px; color: var(--text-secondary); overflow: hidden; }
        .table-photo img { width: 100%; height: 100%; object-fit: cover; }
        .no-photo { font-size: 13px; color: var(--text-secondary); font-style: italic; }

        .priority-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .priority-tinggi { color: var(--danger); }
        .priority-sedang { color: var(--warning); }
        .priority-rendah { color: var(--success); }

        .btn-action { padding: 8px 20px; background: transparent; border: 1px solid var(--btn-primary); border-radius: 8px; color: var(--text-primary); font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
        .btn-action:hover { background: var(--btn-primary); border-color: var(--btn-primary); color: white; }
        .btn-action-success { border-color: var(--success); }
        .btn-action-success:hover { background: var(--success); border-color: var(--success); color: #000; }

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
            <a href="active_alerts.php" class="active">Active Alerts</a>
            <a href="history_satpam.php">History</a>
            <a href="settings_satpam.php">Settings</a>
        </nav>
        <div class="toggle-light">Light Mode</div>
        <div class="user-profile">
            <div class="avatar" id="avatarInitials">AS</div>
            <div class="info">
                <div class="name" id="sidebarName">Admin Satpam</div>
                <div class="role">ID: 9482-X</div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="top-header-panel">
            <div class="header-title">Active Alerts</div>
            <div class="header-right">
                <div class="time" id="clock">20:31 <span>SUN, JUN 7</span></div>
                <div class="status">
                    <span class="dot"></span> System Status: Secure
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Lokasi</th>
                        <th>Keterangan</th>
                        <th>Foto</th>
                        <th>Prioritas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tbody>
                    <?php foreach ($data_laporan as $row): ?>
                        <?php 
                        // Karena ini halaman "Active Alerts", kita sembunyikan yang sudah "Selesai"
                        if ($row['status_penanganan'] == 'Selesai') continue; 
                        ?>
                        
                        <tr>
                            <td><?php echo htmlspecialchars($row['lokasi_gedung']); ?></td>
                            <td><?php echo htmlspecialchars($row['deskripsi_fasilitas']); ?></td>
                            
                            <td>
                                <?php if (!empty($row['foto_kondisi'])): ?>
                                    <div class="table-photo">
                                        <img src="../../uploads/<?php echo $row['foto_kondisi']; ?>" alt="Bukti">
                                    </div>
                                <?php else: ?>
                                    <span class="no-photo">Tidak ada foto</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($row['status_penanganan'] == 'Pending'): ?>
                                    <span class="priority-badge priority-tinggi">Tinggi</span>
                                <?php else: ?>
                                    <span class="priority-badge priority-sedang">Sedang</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <form action="../../backend/processes/updatestatus.php" method="POST" style="margin: 0;">
                                    <input type="hidden" name="id_laporan" value="<?php echo $row['id_laporan']; ?>">
                                    
                                    <?php if ($row['status_penanganan'] == 'Pending'): ?>
                                    <button type="submit" name="status_baru" value="Diproses" class="btn-action">Proses</button>
                                <?php elseif ($row['status_penanganan'] == 'Diproses'): ?>
                                    <button type="submit" name="status_baru" value="Selesai" class="btn-action btn-action-success">Selesai</button>
                                <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                </tbody>
            </table>
        </div>
    </main>

    <div class="toast" id="toast"></div>

    <script>
        function updateClock() {
            const clockEl = document.getElementById('clock');
            if (!clockEl) return;
            const now = new Date();
            const days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const dayName = days[now.getDay()];
            const day = now.getDate();
            const months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
            const month = months[now.getMonth()];
            const timeStr = hours + ':' + minutes;
            const dateStr = dayName + ', ' + month + ' ' + day;
            clockEl.innerHTML = timeStr + ' <span>' + dateStr + '</span>';
        }
        updateClock();
        setInterval(updateClock, 1000);

        function loadUser() {
            let user = localStorage.getItem('safewalk_user');
            if (!user) user = sessionStorage.getItem('safewalk_user');
            if (user) {
                try {
                    const data = JSON.parse(user);
                    document.getElementById('sidebarName').textContent = data.name;
                    document.getElementById('avatarInitials').textContent = data.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                } catch(e) {}
            }
        }
        loadUser();

        // TOGGLE LIGHT MODE
        document.querySelector('.toggle-light').addEventListener('click', function() {
            const root = document.documentElement.style;
            const isLight = root.getPropertyValue('--bg-primary') === '#f6f8fa';
            
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
                root.setProperty('--dot-color', '#2563eb');
                root.setProperty('--dot-glow', 'rgba(37, 99, 235, 0.4)');
                this.textContent = 'Dark Mode';
            } else {
                root.setProperty('--bg-primary', '#0f172a');
                root.setProperty('--bg-secondary', '#1e293b');
                root.setProperty('--bg-tertiary', '#334155');
                root.setProperty('--text-primary', '#f8fafc');
                root.setProperty('--text-secondary', '#94a3b8');
                root.setProperty('--border-color', 'rgba(255,255,255,0.05)');
                root.setProperty('--hover-bg', 'rgba(255,255,255,0.05)');
                root.setProperty('--input-bg', '#0f172a');
                root.setProperty('--card-bg', '#1e293b');
                root.setProperty('--dot-color', '#3b82f6');
                root.setProperty('--dot-glow', 'rgba(59, 130, 246, 0.4)');
                this.textContent = 'Light Mode';
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