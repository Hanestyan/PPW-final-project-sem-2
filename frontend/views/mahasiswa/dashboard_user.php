<?php
// ===================================================
// dashboard_user.php - Halaman Dashboard Mahasiswa
// ===================================================

// Include controller yang mengambil data dari database
// dashuser.php sudah berisi: session_start(), cek login, dan query data
require_once '../../../backend/controllers/dashuser.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFEWALK - Dashboard</title>
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

        .action-cards { display: flex; gap: 24px; margin-bottom: 32px; }
        .action-card { flex: 1; padding: 24px; background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border-color); cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 20px rgba(0,0,0,0.2); display: flex; flex-direction: column; justify-content: center;}
        .action-card:hover { border-color: var(--accent); transform: translateY(-2px); }
        .action-card h3 { font-size: 16px; font-weight: 600; margin-bottom: 8px; color: var(--text-primary); }
        .action-card p { font-size: 13px; color: var(--text-secondary); line-height: 1.5; }

        .dashboard-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; flex: 1; }

        .form-laporan { background: var(--card-bg); border-radius: 24px; padding: 32px; border: 1px solid var(--border-color); box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);}
        .form-laporan h3 { font-size: 18px; font-weight: 600; margin-bottom: 24px; color: var(--text-primary); }
        .form-laporan .form-group { margin-bottom: 24px; }
        .form-laporan label { display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 10px; }
        .form-laporan input, .form-laporan textarea, .form-laporan select {
            width: 100%; padding: 14px 16px; background: var(--input-bg);
            border: 1px solid var(--border-color); border-radius: 12px;
            color: var(--text-primary); font-size: 14px; outline: none; transition: all 0.2s;
        }
        .form-laporan input:focus, .form-laporan textarea:focus, .form-laporan select:focus {
            border-color: var(--accent); box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }
        .form-laporan textarea { resize: vertical; min-height: 100px; }
        .form-laporan select option { background: var(--bg-secondary); }

        .upload-area { border: 2px dashed var(--border-color); border-radius: 12px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.2s; background: rgba(0,0,0,0.2); }
        .upload-area:hover { border-color: var(--accent); background: rgba(59, 130, 246, 0.05); }
        .upload-area svg { width: 32px; height: 32px; fill: var(--accent); margin-bottom: 12px; opacity: 0.8; }
        .upload-area .upload-text { font-size: 14px; color: var(--text-primary); font-weight: 500; margin-bottom: 4px; }
        .upload-area .upload-sub { font-size: 12px; color: var(--text-secondary); }
        .upload-preview { margin-top: 10px; font-size: 13px; color: var(--success); }

        .btn-submit { padding: 14px 24px; background: var(--btn-primary); border: none; border-radius: 12px; color: white; font-weight: 500; cursor: pointer; font-size: 14px; transition: all 0.2s; width: 100%; margin-top: 8px; box-shadow: 0 0 20px rgba(29, 78, 216, 0.4);}
        .btn-submit:hover { background: var(--accent); box-shadow: 0 0 24px rgba(37, 99, 235, 0.6);}

        .right-panel { background: var(--card-bg); border-radius: 24px; padding: 32px; border: 1px solid var(--border-color); box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);}
        .right-panel h3 { font-size: 18px; font-weight: 600; margin-bottom: 24px; color: var(--text-primary); }

        .history-item { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; margin-bottom: 16px; transition: all 0.2s; }
        .history-item:hover { border-color: var(--accent); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.3); }
        .history-item-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; gap: 12px; }
        .history-item-title { font-size: 15px; font-weight: 600; color: var(--text-primary); margin: 0; line-height: 1.4; word-break: break-word; }
        .history-item-subtitle { font-size: 13px; color: var(--text-secondary); margin-bottom: 16px; line-height: 1.5; }
        .history-item-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 16px; margin-top: 4px; }
        
        .badges-wrapper { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

        /* Badge status laporan */
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1px solid transparent; }
        .badge.selesai   { color: var(--success); border-color: rgba(34, 197, 94, 0.2); background: transparent; }
        .badge.diproses  { color: var(--accent);  border-color: rgba(59, 130, 246, 0.2); background: transparent; }
        .badge.pending   { color: var(--warning); border-color: rgba(234, 179, 8, 0.2); background: transparent; }
        .badge.dibatalkan { color: var(--danger);  border-color: rgba(239, 68, 68, 0.2); background: transparent; }

        .urgency-badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 6px; letter-spacing: 0.5px; text-transform: uppercase; }
        .urgency-rendah { background: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.2); }
        .urgency-sedang { background: rgba(234, 179, 8, 0.1); color: var(--warning); border: 1px solid rgba(234, 179, 8, 0.2); }
        .urgency-tinggi { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); }

        .btn-delete {
            display: flex; align-items: center; gap: 6px;
            background: rgba(239, 68, 68, 0.1); color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-delete:hover { background: var(--danger); color: white; }
        .btn-delete svg { width: 14px; height: 14px; }

        /* Notifikasi sukses/error */
        .alert-success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: var(--success); padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
        .alert-error   { background: rgba(239, 68, 68, 0.1);  border: 1px solid rgba(239, 68, 68, 0.3);  color: var(--danger);  padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }

        .empty-state { text-align: center; padding: 40px 20px; color: var(--text-secondary); font-size: 14px; }

        .toast { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); background: var(--bg-tertiary); color: var(--text-primary); padding: 12px 24px; border-radius: 10px; font-size: 14px; box-shadow: 0 4px 20px var(--shadow); border: 1px solid var(--border-color); z-index: 9999; animation: slideUp 0.3s ease; max-width: 90vw; text-align: center; display: none; }
        .toast.show { display: block; }

        @keyframes slideUp { from { opacity: 0; transform: translateX(-50%) translateY(20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }

        @media (max-width: 900px) { .dashboard-grid { grid-template-columns: 1fr; } .action-cards { flex-direction: column; } }
        @media (max-width: 768px) { body { flex-direction: column; } .main-content { padding: 16px; } }
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
            <a href="../../../backend/controllers/logout.php" style="color: var(--danger); margin-top: auto;">Logout</a>
        </nav>
        <div class="toggle-light">Light Mode</div>
        <div class="user-profile">
            <div class="avatar" id="avatarInitials">
                <!-- Inisial dari username, diambil dari session -->
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
            <div class="header-title">Student Dashboard</div>
            <div class="header-right">
                <div class="time" id="clock">--:-- <span>---, --- -</span></div>
                <div class="status">
                    <span class="dot"></span> System Status: Secure
                </div>
            </div>
        </div>

        <div class="action-cards">
            <div class="action-card" onclick="showToast('Fitur Request SafeWalk segera hadir!')">
                <h3>Request SafeWalk</h3>
                <p>Minta pendampingan keamanan dari petugas satpam</p>
            </div>
            <div class="action-card" onclick="document.querySelector('.form-laporan').scrollIntoView({behavior:'smooth'})">
                <h3>Laporkan Insiden</h3>
                <p>Kirim laporan kerusakan atau kejadian mencurigakan</p>
            </div>
        </div>

        <!-- Tampilkan pesan sukses atau error dari redirect -->
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'laporan_terkirim'): ?>
            <div class="alert-success">✓ Laporan berhasil dikirim! Satpam akan segera menangani.</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert-error">✗ Terjadi kesalahan. Silakan coba lagi.</div>
        <?php endif; ?>

        <!-- Tampilkan pesan sukses/error penghapusan -->
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'dihapus'): ?>
            <div class="alert-success">✓ Laporan berhasil dihapus!</div>
        <?php elseif (isset($_GET['error']) && $_GET['error'] === 'gagal_hapus'): ?>
            <div class="alert-error">✗ Laporan gagal dihapus. Hanya laporan berstatus Pending yang dapat dihapus.</div>
        <?php endif; ?>

        <div class="dashboard-grid">
            <!-- FORM KIRIM LAPORAN -->
            <div class="form-laporan">
                <h3>Formulir Laporan</h3>
                <!--
                    enctype="multipart/form-data" wajib ada agar bisa upload foto
                    Gunakan path absolute untuk menghindari Page Not Found (404)
                -->
                <form action="../../../backend/controllers/proseslaporan.php" method="POST" enctype="multipart/form-data" id="reportForm">

                    <div class="form-group">
                        <label for="lokasi">Lokasi Kejadian</label>
                        <!-- name="lokasi" sesuai kolom di database -->
                        <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: Gedung A, Lantai 3" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Laporan</label>
                        <!-- name="deskripsi" sesuai kolom di database -->
                        <textarea id="deskripsi" name="deskripsi" placeholder="Jelaskan detail kejadian atau kerusakan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="urgensi">Tingkat Urgensi</label>
                        <!-- name="urgensi" dikirim ke proseslaporan.php sebagai tingkat_urgensi -->
                        <select id="urgensi" name="urgensi">
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang" selected>Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Foto Bukti (Opsional)</label>
                        <div class="upload-area" id="dropZone" onclick="document.getElementById('fotoInput').click()">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/>
                            </svg>
                            <div class="upload-text">Klik untuk Upload Foto</div>
                            <div class="upload-sub">PNG, JPG, JPEG (Maks. 5MB)</div>
                            <div class="upload-preview" id="namaFile"></div>
                        </div>
                        <!-- Input file tersembunyi, name="foto" sesuai kolom di database -->
                        <input type="file" id="fotoInput" name="foto" accept="image/*" style="display:none">
                    </div>

                    <!-- name="submit" agar PHP bisa cek isset($_POST['submit']) -->
                    <button type="submit" name="submit" class="btn-submit">Kirim Laporan</button>
                </form>
            </div>

            <!-- RIWAYAT LAPORAN TERBARU -->
            <div class="right-panel">
                <h3>Riwayat Terbaru</h3>

                <?php if (empty($riwayat_terbaru)): ?>
                    <!-- Tampilkan pesan jika belum ada laporan -->
                    <div class="empty-state">Belum ada laporan yang dikirim.</div>
                <?php else: ?>
                    <?php foreach ($riwayat_terbaru as $row): ?>
                        <div class="history-item">
                            <div class="history-item-header">
                                <h4 class="history-item-title"><?php echo htmlspecialchars($row['deskripsi']); ?></h4>
                                <span class="badge <?php echo strtolower($row['status']); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </div>
                            
                            <div class="history-item-subtitle"><?php echo htmlspecialchars($row['lokasi']); ?></div>
                            
                            <div class="history-item-footer">
                                <div class="badges-wrapper">
                                    <?php
                                        $urgensi_class = 'urgency-sedang';
                                        if($row['tingkat_urgensi'] === 'Tinggi') $urgensi_class = 'urgency-tinggi';
                                        elseif($row['tingkat_urgensi'] === 'Rendah') $urgensi_class = 'urgency-rendah';
                                    ?>
                                    <span class="urgency-badge <?php echo $urgensi_class; ?>">
                                        <?php echo htmlspecialchars($row['tingkat_urgensi']); ?>
                                    </span>
                                </div>

                                <?php if ($row['status'] === 'Pending'): ?>
                                <form action="../../../backend/controllers/deletelaporan.php" method="POST" style="margin: 0;" onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                                    <input type="hidden" name="id_laporan" value="<?php echo $row['id_laporan']; ?>">
                                    <input type="hidden" name="halaman_asal" value="dashboard">
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
            </div>
        </div>
    </main>

    <div class="toast" id="toast"></div>

    <script>
        // Update jam secara real-time
        function updateClock() {
            const clockEl = document.getElementById('clock');
            if (!clockEl) return;
            const now = new Date();
            const days   = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
            const months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
            const jam    = String(now.getHours()).padStart(2, '0');
            const menit  = String(now.getMinutes()).padStart(2, '0');
            const hari   = days[now.getDay()];
            const bulan  = months[now.getMonth()];
            clockEl.innerHTML = jam + ':' + menit + ' <span>' + hari + ', ' + bulan + ' ' + now.getDate() + '</span>';
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Tampilkan nama file yang dipilih di upload area
        document.getElementById('fotoInput').addEventListener('change', function() {
            const namaFile = document.getElementById('namaFile');
            if (this.files.length > 0) {
                namaFile.textContent = '✓ File terpilih: ' + this.files[0].name;
            } else {
                namaFile.textContent = '';
            }
        });

        // Toggle Light/Dark Mode
        document.querySelector('.toggle-light').addEventListener('click', function() {
            const root   = document.documentElement.style;
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

        // Fungsi untuk menampilkan toast notification
        function showToast(message, duration = 3000) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), duration);
        }
    </script>
</body>
</html>