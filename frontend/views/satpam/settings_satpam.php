<?php
// ===================================================
// settings_satpam.php - Halaman Settings (Satpam)
// ===================================================

session_start();

// Cek apakah user sudah login sebagai satpam atau admin
if (!isset($_SESSION['id_user']) || !in_array($_SESSION['role'], ['satpam', 'admin'])) {
    header("Location: ../../../frontend/index.php");
    exit();
}

// Ambil username dari session untuk ditampilkan di halaman
$username = $_SESSION['username'];

// Tampilkan pesan setelah ganti password
$pesan = $_GET['pesan'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFEWALK - Settings</title>
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

        .settings-container { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        @media (max-width: 768px) { .settings-container { grid-template-columns: 1fr; } body { flex-direction: column; } .main-content { padding: 16px; } }

        .settings-card { background: var(--card-bg); border-radius: 24px; padding: 32px; border: 1px solid var(--border-color); box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5); }
        .settings-card h3 { font-size: 18px; margin-bottom: 24px; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 12px; font-weight: 600; }
        .settings-card.full-width { grid-column: 1 / -1; }

        .form-group { margin-bottom: 24px; }
        .form-group label { display: block; font-size: 13px; color: var(--text-secondary); margin-bottom: 10px; font-weight: 500; }
        .form-group input { width: 100%; padding: 14px 16px; background: var(--input-bg); border: 1px solid var(--border-color); border-radius: 12px; color: var(--text-primary); font-size: 14px; transition: all 0.2s; outline: none; }
        .form-group input:focus { border-color: var(--accent); box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }
        .form-group input[readonly] { opacity: 0.6; cursor: not-allowed; }

        .btn-save { padding: 14px 24px; background: var(--btn-primary); color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; width: 100%; margin-top: 16px; box-shadow: 0 0 20px rgba(29, 78, 216, 0.4); }
        .btn-save:hover { background: var(--accent); box-shadow: 0 0 24px rgba(37, 99, 235, 0.6); }

        .profile-header { display: flex; align-items: center; gap: 24px; }
        .profile-avatar { width: 88px; height: 88px; background: var(--bg-tertiary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 700; color: var(--accent); border: 2px solid var(--accent); box-shadow: 0 0 16px rgba(59, 130, 246, 0.2); }
        .profile-info h4 { font-size: 24px; margin-bottom: 4px; font-weight: 700; }
        .profile-info p { color: var(--text-secondary); font-size: 14px; }

        .alert-success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: var(--success); padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; }

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
            <div class="avatar"><?php echo strtoupper(substr($username, 0, 2)); ?></div>
            <div class="info">
                <div class="name"><?php echo htmlspecialchars($username); ?></div>
                <div class="role">Satpam</div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="top-header-panel">
            <div class="header-title">Settings & Profile</div>
            <div class="header-right">
                <div class="time" id="clock">--:-- <span>---, --- -</span></div>
                <div class="status">
                    <span class="dot"></span> System Status: Secure
                </div>
            </div>
        </div>

        <!-- Tampilkan pesan sukses setelah ganti password -->
        <?php if ($pesan === 'sukses'): ?>
            <div class="alert-success">✓ Password berhasil diperbarui!</div>
        <?php endif; ?>

        <div class="settings-container">
            <!-- Profil Satpam -->
            <div class="settings-card full-width">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($username, 0, 1)); ?>
                    </div>
                    <div class="profile-info">
                        <h4><?php echo htmlspecialchars($username); ?></h4>
                        <p>Satuan Pengamanan (Satpam) Campus</p>
                    </div>
                </div>
            </div>

            <!-- Info Pribadi (hanya tampilan, tidak bisa diubah) -->
            <div class="settings-card">
                <h3>Informasi Pribadi</h3>
                <div class="form-group">
                    <label>Nama Pengguna</label>
                    <input type="text" value="<?php echo htmlspecialchars($username); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" value="Satpam" readonly>
                </div>
                <div class="form-group">
                    <label>Email Kontak</label>
                    <input type="email" value="<?php echo strtolower(htmlspecialchars($username)); ?>@safewalk.ac.id" readonly>
                </div>
            </div>

            <!-- Ganti Password — form ini dikirim ke chgpassword.php -->
            <div class="settings-card">
                <h3>Keamanan Akun</h3>
                <form action="../../../backend/controllers/chgpassword.php" method="POST">
                    <div class="form-group">
                        <label for="password_baru">Password Baru</label>
                        <input type="password" id="password_baru" name="password_baru" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="form-group">
                        <label for="konfirmasi_password">Konfirmasi Password Baru</label>
                        <input type="password" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ulangi password baru" required>
                    </div>
                    <!-- name="ganti_password" agar PHP bisa cek isset($_POST['ganti_password']) -->
                    <button type="submit" name="ganti_password" class="btn-save">Perbarui Password</button>
                </form>
            </div>
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
                root.setProperty('--bg-primary', '#f8fafc'); root.setProperty('--bg-secondary', '#ffffff');
                root.setProperty('--bg-tertiary', '#f1f5f9'); root.setProperty('--text-primary', '#0f172a');
                root.setProperty('--text-secondary', '#64748b'); root.setProperty('--border-color', 'rgba(0,0,0,0.1)');
                root.setProperty('--hover-bg', 'rgba(0,0,0,0.05)'); root.setProperty('--input-bg', '#ffffff');
                root.setProperty('--card-bg', '#ffffff'); this.innerHTML = '☀ Dark Mode';
            } else {
                root.setProperty('--bg-primary', '#0B0F19'); root.setProperty('--bg-secondary', '#151A24');
                root.setProperty('--bg-tertiary', '#1E293B'); root.setProperty('--text-primary', '#F8FAFC');
                root.setProperty('--text-secondary', '#94A3B8'); root.setProperty('--border-color', 'rgba(255,255,255,0.05)');
                root.setProperty('--hover-bg', 'rgba(255,255,255,0.02)'); root.setProperty('--input-bg', '#0B0F19');
                root.setProperty('--card-bg', '#151A24'); this.innerHTML = '☀ Light Mode';
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