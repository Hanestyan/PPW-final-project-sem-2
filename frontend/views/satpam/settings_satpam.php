<?php
// views/satpam/settings_satpam.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFEWALK - Settings</title>
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

        .settings-container { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        @media (max-width: 768px) { .settings-container { grid-template-columns: 1fr; } }
        .settings-card { background: var(--card-bg); border-radius: 16px; padding: 32px; border: 1px solid var(--border-color); box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
        .settings-card h3 { font-size: 18px; margin-bottom: 24px; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 12px; }
        .settings-card.full-width { grid-column: 1 / -1; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; color: var(--text-secondary); margin-bottom: 8px; font-weight: 500;}
        .form-group input { width: 100%; padding: 12px 16px; background: var(--input-bg); border: 1px solid var(--border-color); border-radius: 12px; color: var(--text-primary); font-size: 14px; transition: all 0.2s; }
        .form-group input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }

        .btn-save { padding: 12px 24px; background: var(--btn-primary); color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; width: 100%; margin-top: 16px;}
        .btn-save:hover { background: var(--accent-hover); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); }

        .profile-header { display: flex; align-items: center; gap: 24px; }
        .profile-avatar { width: 88px; height: 88px; background: var(--bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; color: var(--text-primary); border: 2px solid var(--accent); box-shadow: 0 0 16px rgba(59, 130, 246, 0.2); }
        .profile-info h4 { font-size: 24px; margin-bottom: 4px; font-weight: 700;}
        .profile-info p { color: var(--text-secondary); font-size: 14px; }

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
            <a href="dashboard_satpam.php">Dashboard</a>
            <a href="active_alerts.php">Active Alerts</a>
            <a href="history_satpam.php">History</a>
            <a href="settings_satpam.php" class="active">Settings</a>
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
            <div class="header-title">Settings & Profile</div>
            <div class="header-right">
                <div class="time" id="clock">20:32 <span>SUN, JUN 7</span></div>
                <div class="status">
                    <span class="dot"></span> System Status: Secure
                </div>
            </div>
        </div>

        <div class="settings-container">
            <div class="settings-card full-width">
                <div class="profile-header">
                    <div class="profile-avatar"><?php echo substr($username, 0, 1); ?></div>
                    <div class="profile-info">
                        <h4><?php echo htmlspecialchars($username); ?></h4>
                        <p>Satuan Pengamanan (Satpam) Campus</p>
                    </div>
                </div>
            </div>

            <div class="settings-card">
                <h3>Informasi Pribadi</h3>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label>Nama Pengguna</label>
                        <input type="text" value="<?php echo htmlspecialchars($username); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nomor Induk Pegawai (NIP)</label>
                        <input type="text" value="STPM-2023-089" readonly>
                    </div>
                    <div class="form-group">
                        <label>Email Kontak</label>
                        <input type="email" value="<?php echo strtolower($username); ?>@safewalk.ac.id">
                    </div>
                    <button type="button" class="btn-save">Simpan Perubahan</button>
                </form>
            </div>

            <div class="settings-card">
                <h3>Keamanan Akun</h3>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label>Password Saat Ini</label>
                        <input type="password" placeholder="Masukkan password saat ini">
                    </div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" placeholder="Masukkan password baru">
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" placeholder="Ulangi password baru">
                    </div>
                    <button type="button" class="btn-save">Perbarui Password</button>
                </form>
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
            clockEl.innerHTML = hours + ':' + minutes + ' <span>' + dayName + ', ' + month + ' ' + day + '</span>';
        }
        updateClock();
        setInterval(updateClock, 1000);

        document.querySelector('.toggle-light').addEventListener('click', function() {
            const root = document.documentElement.style;
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
        document.getElementById('notifToggle').addEventListener('click', function() {
            this.classList.toggle('active');
            showToast(this.classList.contains('active') ? 'Push Notifications enabled' : 'Push Notifications disabled');
        });

        // Update profile name
        document.getElementById('fullname').addEventListener('change', function() {
            const name = this.value;
            document.getElementById('sidebarName').textContent = name;
            document.getElementById('avatarInitials').textContent = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
            
            let userData = JSON.parse(localStorage.getItem('safewalk_user') || sessionStorage.getItem('safewalk_user') || '{}');
            userData.name = name;
            localStorage.setItem('safewalk_user', JSON.stringify(userData));
            showToast('Name updated successfully');
        });

        // Update email
        document.getElementById('email').addEventListener('change', function() {
            document.getElementById('profileEmail').textContent = this.value;
            let userData = JSON.parse(localStorage.getItem('safewalk_user') || sessionStorage.getItem('safewalk_user') || '{}');
            userData.email = this.value;
            localStorage.setItem('safewalk_user', JSON.stringify(userData));
            showToast('Email updated successfully');
        });

        // Change password
        document.getElementById('changePasswordBtn').addEventListener('click', function() {
            const newPassword = prompt('Enter new password (min 4 characters):');
            if (newPassword && newPassword.length >= 4) {
                showToast('Password changed successfully!');
            } else if (newPassword) {
                showToast('Password must be at least 4 characters');
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