<?php
session_start();
// include '../../../backend/config/database.php';
require_once '../../../backend/controllers/dashadmin.php';
$riwayat_terbaru = [];
// views/mahasiswa/dashboard_user.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFEWALK - Dashboard</title>
    <link rel="stylesheet" href="../../../frontend/assets/css/sidebar.css">
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

        .action-cards { display: flex; gap: 24px; margin-bottom: 32px; }
        .action-card { flex: 1; padding: 24px; background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border-color); cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 20px rgba(0,0,0,0.2); display: flex; flex-direction: column; justify-content: center;}
        .action-card:hover { border-color: var(--accent); transform: translateY(-2px); }
        .action-card h3 { font-size: 16px; font-weight: 600; margin-bottom: 8px; color: var(--text-primary); }
        .action-card p { font-size: 13px; color: var(--text-secondary); line-height: 1.5; }

        .dashboard-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; flex: 1; }

        .form-laporan { background: var(--card-bg); border-radius: 16px; padding: 24px; border: 1px solid var(--border-color); box-shadow: 0 4px 20px rgba(0,0,0,0.2);}
        .form-laporan h3 { font-size: 18px; font-weight: 600; margin-bottom: 20px; color: var(--text-primary); }
        .form-laporan .form-group { margin-bottom: 20px; }
        .form-laporan label { display: block; font-size: 14px; font-weight: 500; color: var(--text-secondary); margin-bottom: 8px; }
        .form-laporan input, .form-laporan textarea { width: 100%; padding: 12px 16px; background: var(--input-bg); border: 1px solid var(--border-color); border-radius: 12px; color: var(--text-primary); font-size: 14px; outline: none; transition: all 0.2s; }
        .form-laporan input:focus, .form-laporan textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }
        .form-laporan textarea { resize: vertical; min-height: 100px; }

        .upload-area { border: 2px dashed var(--border-color); border-radius: 12px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.2s; background: rgba(0,0,0,0.2); }
        .upload-area:hover { border-color: var(--accent); background: rgba(59, 130, 246, 0.05); }
        .upload-area svg { width: 32px; height: 32px; fill: var(--accent); margin-bottom: 12px; opacity: 0.8; }
        .upload-area .upload-text { font-size: 14px; color: var(--text-primary); font-weight: 500; margin-bottom: 4px; }
        .upload-area .upload-sub { font-size: 12px; color: var(--text-secondary); }
        
        .upload-actions { display: flex; gap: 12px; justify-content: center; margin-top: 16px; }
        .upload-actions button { padding: 8px 16px; background: var(--bg-tertiary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
        .upload-actions button:hover { background: var(--hover-bg); border-color: var(--text-secondary); }

        .btn-submit { padding: 12px 24px; background: var(--btn-primary); border: none; border-radius: 12px; color: white; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.2s; width: 100%; margin-top: 8px;}
        .btn-submit:hover { background: var(--accent-hover); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);}

        .right-panel { background: var(--card-bg); border-radius: 16px; padding: 24px; border: 1px solid var(--border-color); box-shadow: 0 4px 20px rgba(0,0,0,0.2);}
        .right-panel h3 { font-size: 18px; font-weight: 600; margin-bottom: 20px; color: var(--text-primary); }

        .history-item { padding: 16px 0; border-bottom: 1px solid var(--border-color); cursor: pointer; transition: all 0.2s; }
        .history-item:last-child { border-bottom: none; }
        .history-item:hover { padding-left: 8px; }
        .history-item h4 { font-weight: 600; font-size: 15px; margin-bottom: 4px; color: var(--text-primary); }
        .history-item p { font-size: 13px; color: var(--text-secondary); margin-bottom: 8px; }

        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1px solid transparent; }
        .badge.selesai { background: rgba(34, 197, 94, 0.1); color: var(--success); border-color: rgba(34, 197, 94, 0.2); }
        .badge.diproses { background: rgba(59, 130, 246, 0.1); color: var(--accent); border-color: rgba(59, 130, 246, 0.2); }
        .badge.dibatalkan { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.2); }
        .badge.pending { background: rgba(234, 179, 8, 0.1); color: var(--warning); border-color: rgba(234, 179, 8, 0.2); }

        .toast { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); background: var(--bg-tertiary); color: var(--text-primary); padding: 12px 24px; border-radius: 10px; font-size: 14px; box-shadow: 0 4px 20px var(--shadow); border: 1px solid var(--border-color); z-index: 9999; animation: slideUp 0.3s ease; max-width: 90vw; text-align: center; display: none; }
        .toast.show { display: block; }

        @keyframes slideUp { from { opacity: 0; transform: translateX(-50%) translateY(20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }

        @media (max-width: 900px) { .dashboard-grid { grid-template-columns: 1fr; } .action-cards { flex-direction: column; } }
        @media (max-width: 768px) { .main-content { padding: 16px; } }
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .main-content { padding: 16px; }
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo-side">SAFEWALK</div>
        <nav>
            <a href="dashboard_user.php" class="active">Dashboard</a>
            <a href="history_user.php">History</a>
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

    <!-- MAIN -->
    <main class="main-content">
        <div class="top-header-panel">
            <div class="header-title">Student Dashboard</div>
            <div class="header-right">
                <div class="time" id="clock">--:-- <span>---, --- -</span></div>
                <div class="status">
                    <span class="dot"></span> System Status: Secure
                </div>
            </div>
        </div>

        <div class="action-cards">
            <div class="action-card" onclick="showToast('Request SafeWalk feature coming soon!')">
                <h3>Request SafeWalk</h3>
                <p>Recommended for security escorts</p>
            </div>
            <div class="action-card" onclick="showToast('Use form below to report an incident!')">
                <h3>Report Incident</h3>
                <p>For alerts about suspicious activity or emergencies</p>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="form-laporan">
                <h3>Formulir Laporan</h3>
                <form action="../../../backend/controllers/proseslaporan.php" method="POST" id="reportForm">
                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" id="lokasi" placeholder="e.g. Gedung A, Lt. 3">
                    </div>
                    <div class="form-group">
                        <label for="detail">Detail Laporan</label>
                        <textarea id="detail" placeholder="Describe the incident..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Lampiran Foto (Opsional)</label>
                        <div class="upload-area" id="dropZone">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/>
                            </svg>
                            <div class="upload-text">Klik untuk Upload atau Drag file kesini</div>
                            <div class="upload-sub">PNG, JPG, JPEG (Max. 5MB)</div>
                            <div class="upload-actions">
                                <button type="button" id="uploadBtn">Pilih File</button>
                                <button type="button" id="downloadBtn">Unduh Template</button>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">Kirim Laporan</button>
                </form>
            </div>

            <div class="right-panel">
            <h3>Riwayat Terbaru</h3>
            <?php foreach ($riwayat_terbaru as $row): ?>    
                <div class="item-riwayat">
                    <h4><?php echo $row['lokasi']; ?></h4>
                    <p><?php echo $row['deskripsi']; ?></p>
                    
                    <span class="badge <?php echo strtolower($row['status']); ?>">
                        <?php echo $row['status']; ?>
                    </span>
                </div>
            <?php endforeach; ?>
                </div>
            </div>
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

        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const lokasi = document.getElementById('lokasi').value.trim();
            const detail = document.getElementById('detail').value.trim();
            if (!lokasi || !detail) { showToast('Please fill in all fields!'); return; }
            const now = new Date();
            const timeStr = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
            const historyPanel = document.querySelector('.right-panel');
            const newItem = document.createElement('div');
            newItem.className = 'history-item';
            newItem.innerHTML = '<div class="title">Laporan: ' + detail.substring(0, 20) + (detail.length > 20 ? '...' : '') + ' <span class="badge badge-diproses">Diproses</span></div><div class="meta">' + lokasi + ' - ' + timeStr + '</div>';
            historyPanel.insertBefore(newItem, historyPanel.querySelector('.history-item'));
            document.getElementById('lokasi').value = '';
            document.getElementById('detail').value = '';
            showToast('Report submitted successfully!');
        });

        document.getElementById('dropZone').addEventListener('click', function(e) {
            if(e.target.tagName !== 'BUTTON') {
                document.getElementById('uploadBtn').click();
            }
        });

        document.getElementById('uploadBtn').addEventListener('click', function() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.click();
            input.addEventListener('change', function() {
                if (this.files.length > 0) showToast('File Terlampir: ' + this.files[0].name);
            });
        });

        document.getElementById('downloadBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            showToast('Belum ada file untuk diunduh');
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