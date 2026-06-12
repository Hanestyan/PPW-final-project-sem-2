<?php
session_start();
require_once '../../../backend/controllers/riwayat.php';
// views/mahasiswa/history_user.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFEWALK - History</title>
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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

        .history-card { 
            background: var(--card-bg); border-radius: 16px; padding: 24px; 
            border: 1px solid var(--border-color); margin-bottom: 16px; 
            display: flex; justify-content: space-between; align-items: center; 
            cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .history-card:hover { border-color: var(--accent); transform: translateY(-2px); }
        .history-card .left { display: flex; align-items: center; gap: 20px; }
        
        .icon-circle {
            width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 600; flex-shrink: 0;
        }
        .icon-circle.selesai { background: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.2); }
        .icon-circle.diproses { background: rgba(59, 130, 246, 0.1); color: var(--accent); border: 1px solid rgba(59, 130, 246, 0.2); }
        .icon-circle.dibatalkan { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); }
        .icon-circle.pending { background: rgba(234, 179, 8, 0.1); color: var(--warning); border: 1px solid rgba(234, 179, 8, 0.2); }

        .history-card .left .title { font-weight: 600; font-size: 16px; color: var(--text-primary); margin-bottom: 4px; }
        .history-card .left .desc { font-size: 13px; color: var(--text-secondary); }
        .history-card .right { text-align: right; }
        .history-card .right .time { font-size: 13px; color: var(--text-secondary); margin-top: 8px; font-weight: 500;}

        .badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1px solid transparent; }
        .badge-selesai { background: rgba(34, 197, 94, 0.1); color: var(--success); border-color: rgba(34, 197, 94, 0.2); }
        .badge-diproses { background: rgba(59, 130, 246, 0.1); color: var(--accent); border-color: rgba(59, 130, 246, 0.2); }
        .badge-dibatalkan { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.2); }
        .badge-pending { background: rgba(234, 179, 8, 0.1); color: var(--warning); border-color: rgba(234, 179, 8, 0.2); }

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
    <aside class="sidebar">
        <div class="logo-side">SAFEWALK</div>
        <nav>
            <a href="dashboard_user.php">Dashboard</a>
            <a href="history_user.php" class="active">History</a>
            <a href="settings_user.php">Settings</a>
        </nav>
        <div class="toggle-light">Light Mode</div>
        <div class="user-profile">
            <div class="avatar" id="avatarInitials">JD</div>
            <div class="info">
                <div class="name" id="sidebarName">Jane Doe</div>
                <div class="role">Student</div>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-header-panel">
            <div class="header-title">Student History</div>
            <div class="header-right">
                <div class="time" id="clock">--:-- <span>---, --- -</span></div>
                <div class="status">
                    <span class="dot"></span> System Status: Secure
                </div>
            </div>
        </div>

        <?php if (empty($riwayat_user)): ?>
            <p style="color: var(--text-secondary); margin-top: 10px; font-size: 14px; text-align: center; padding: 40px; background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border-color);">Belum ada riwayat laporan yang dikirim.</p>
        <?php else: ?>
            <?php foreach ($riwayat_user as $row): ?>
                <?php 
                // Determine classes
                $badge_class = 'badge-pending';
                $icon_class = 'pending';
                $icon_letter = 'P';
                
                if ($row['status'] == 'Selesai') {
                    $badge_class = 'badge-selesai';
                    $icon_class = 'selesai';
                    $icon_letter = 'S';
                } elseif ($row['status'] == 'Diproses') {
                    $badge_class = 'badge-diproses';
                    $icon_class = 'diproses';
                    $icon_letter = 'D';
                } elseif ($row['status'] == 'Dibatalkan') {
                    $badge_class = 'badge-dibatalkan';
                    $icon_class = 'dibatalkan';
                    $icon_letter = 'X';
                }
                ?>
                <div class="history-card">
                    <div class="left">
                        <div class="icon-circle <?php echo $icon_class; ?>">
                            <?php echo $icon_letter; ?>
                        </div>
                        <div>
                            <div class="title"><?php echo htmlspecialchars($row['deskripsi']); ?></div>
                            <div class="desc"><?php echo htmlspecialchars($row['lokasi']); ?></div>
                        </div>
                    </div>
                    <div class="right">
                        <span class="badge <?php echo $badge_class; ?>"><?php echo $row['status']; ?></span>
                        <div class="time"><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
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

        // TOGGLE LIGHT MODE - FIX
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