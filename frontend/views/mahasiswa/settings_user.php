<?php
// views/mahasiswa/settings_user.php
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

        .settings-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; }
        .settings-card { background: var(--card-bg); border-radius: 16px; padding: 24px; border: 1px solid var(--border-color); box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
        .settings-card h3 { font-size: 18px; font-weight: 600; margin-bottom: 20px; color: var(--text-primary); }
        
        .profile-header { display: flex; align-items: center; gap: 24px; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--border-color); }
        .profile-avatar { width: 80px; height: 80px; background: var(--bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 600; color: var(--accent); border: 2px solid var(--border-color); box-shadow: 0 0 15px rgba(59, 130, 246, 0.2); }
        
        .settings-card .form-group { margin-bottom: 20px; }
        .settings-card label { display: block; font-size: 14px; font-weight: 500; color: var(--text-secondary); margin-bottom: 8px; }
        .settings-card input { width: 100%; padding: 12px 16px; background: var(--input-bg); border: 1px solid var(--border-color); border-radius: 12px; color: var(--text-primary); font-size: 14px; outline: none; transition: all 0.2s; }
        .settings-card input:focus { border-color: var(--accent); box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }
        .settings-card input:disabled { opacity: 0.6; cursor: not-allowed; }
        .settings-card .input-meta { font-size: 12px; color: var(--text-secondary); margin-top: 6px; }

        .toggle-row { display: flex; justify-content: space-between; align-items: center; padding: 16px 0; border-bottom: 1px solid var(--border-color); }
        .toggle-row:last-child { border-bottom: none; }

        .toggle-switch { width: 44px; height: 24px; background: var(--bg-tertiary); border-radius: 12px; position: relative; cursor: pointer; transition: background 0.2s; border: 1px solid var(--border-color); }
        .toggle-switch.active { background: var(--btn-primary); border-color: var(--btn-primary); }
        .toggle-switch::after { content: ''; width: 18px; height: 18px; background: var(--text-primary); border-radius: 50%; position: absolute; top: 2px; left: 3px; transition: transform 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .toggle-switch.active::after { transform: translateX(18px); }

        .btn-secondary { padding: 12px 24px; background: transparent; border: 1px solid var(--border-color); border-radius: 12px; color: var(--text-primary); font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; margin-top: 16px; width: 100%; }
        .btn-secondary:hover { background: var(--hover-bg); border-color: var(--text-primary); }

        .settings-card p { font-size: 13px; color: var(--text-secondary); margin-bottom: 12px; }

        .toast { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); background: var(--bg-tertiary); color: var(--text-primary); padding: 12px 24px; border-radius: 10px; font-size: 14px; box-shadow: 0 4px 20px var(--shadow); border: 1px solid var(--border-color); z-index: 9999; animation: slideUp 0.3s ease; max-width: 90vw; text-align: center; display: none; }
        .toast.show { display: block; }
        @keyframes slideUp { from { opacity: 0; transform: translateX(-50%) translateY(20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }

        @media (max-width: 768px) { .settings-grid { grid-template-columns: 1fr; } body { flex-direction: column; } .main-content { padding: 16px; } }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="logo-side">SAFEWALK</div>
        <nav>
            <a href="dashboard_user.php">Dashboard</a>
            <a href="history_user.php">History</a>
            <a href="settings_user.php" class="active">Settings</a>
        </nav>
        <div class="toggle-light">Light Mode</div>
        <div class="user-profile">
            <div class="avatar" id="avatarInitials">JD</div>
            <div class="info">
                <div class="name" id="sidebarName">Aries</div>
                <div class="role">Student</div>
            </div>
        </div>
    </aside>

    <main class="main-content">
    <main class="main-content">
        <div class="top-header-panel">
            <div class="header-title">Account Settings</div>
            <div class="header-right">
                <div class="time" id="clock">--:-- <span>---, --- -</span></div>
                <div class="status">
                    <span class="dot"></span> System Status: Secure
                </div>
            </div>
        </div>

        <div class="settings-grid">
            <div class="settings-card">
                <div class="profile-header">
                    <div class="profile-avatar" id="settingsAvatarInitials">JD</div>
                    <div>
                        <h3 style="margin-bottom: 4px; font-size: 20px;">Personal Info</h3>
                        <p style="color: var(--text-secondary); margin-bottom: 0;">Manage your student profile details.</p>
                    </div>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" value="Student" disabled>
                    <div class="input-meta" id="profileEmail">student@safewalk.edu</div>
                </div>
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" value="Aries">
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" value="aries@safewalk.edu">
                </div>
            </div>

            <div>
                <div class="settings-card" style="margin-bottom: 16px;">
                    <h3>Preferences</h3>
                    <div class="toggle-row">
                        <span>Push Notifications</span>
                        <div class="toggle-switch active" id="notifToggle"></div>
                    </div>
                    <div class="toggle-row">
                        <span>Language</span>
                        <span style="color: var(--text-secondary); font-size: 14px;">Indonesia</span>
                    </div>
                </div>

                <div class="settings-card">
                    <h3>Security</h3>
                    <p>Update your password and 2FA settings</p>
                    <button class="btn-secondary" id="changePasswordBtn">Change Password</button>
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
                    const initials = data.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                    document.getElementById('avatarInitials').textContent = initials;
                    const settingsAvatar = document.getElementById('settingsAvatarInitials');
                    if(settingsAvatar) settingsAvatar.textContent = initials;
                    document.getElementById('fullname').value = data.name;
                    if (data.email) {
                        document.getElementById('email').value = data.email;
                        document.getElementById('profileEmail').textContent = data.email;
                    }
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

        document.getElementById('notifToggle').addEventListener('click', function() {
            this.classList.toggle('active');
            showToast(this.classList.contains('active') ? 'Push Notifications enabled' : 'Push Notifications disabled');
        });

        document.getElementById('fullname').addEventListener('change', function() {
            const name = this.value;
            document.getElementById('sidebarName').textContent = name;
            const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
            document.getElementById('avatarInitials').textContent = initials;
            const settingsAvatar = document.getElementById('settingsAvatarInitials');
            if(settingsAvatar) settingsAvatar.textContent = initials;
            let userData = JSON.parse(localStorage.getItem('safewalk_user') || sessionStorage.getItem('safewalk_user') || '{}');
            userData.name = name;
            localStorage.setItem('safewalk_user', JSON.stringify(userData));
            showToast('Name updated');
        });

        document.getElementById('email').addEventListener('change', function() {
            document.getElementById('profileEmail').textContent = this.value;
            let userData = JSON.parse(localStorage.getItem('safewalk_user') || sessionStorage.getItem('safewalk_user') || '{}');
            userData.email = this.value;
            localStorage.setItem('safewalk_user', JSON.stringify(userData));
            showToast('Email updated');
        });

        document.getElementById('changePasswordBtn').addEventListener('click', function() {
            const newPassword = prompt('Enter new password:');
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