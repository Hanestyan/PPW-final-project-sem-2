<?php
// ===================================================
// index.php - Halaman Login SafeWalk
// ===================================================

// Mulai session untuk mengecek status login
session_start();

// Jika sudah login, langsung arahkan ke dashboard yang sesuai
if (isset($_SESSION['id_user'])) {
    if ($_SESSION['role'] === 'satpam' || $_SESSION['role'] === 'admin') {
        header("Location: views/satpam/dashboard_satpam.php");
    } else {
        header("Location: views/mahasiswa/dashboard_user.php");
    }
    exit();
}

// Ambil pesan error dari URL jika ada (dikirim dari loginprocess.php)
$pesan_error = '';
if (isset($_GET['error']) && $_GET['error'] === 'salah_password') {
    $pesan_error = 'Username atau password salah. Silakan coba lagi.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFEWALK - Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <!-- Banner SAFEWALK -->
        <div class="banner-safewalk">SAFEWALK</div>

        <!-- Form Login -->
        <div class="login-container">
            <h2>Login</h2>

            <!-- Tampilkan pesan error jika ada -->
            <?php if ($pesan_error): ?>
                <div class="error-alert" style="
                    background: rgba(239, 68, 68, 0.1);
                    border: 1px solid rgba(239, 68, 68, 0.3);
                    color: #ef4444;
                    padding: 10px 14px;
                    border-radius: 8px;
                    font-size: 13px;
                    margin-bottom: 16px;
                ">
                    <?php echo htmlspecialchars($pesan_error); ?>
                </div>
            <?php endif; ?>

            <!--
                PENTING: action mengarah ke loginprocess.php
                method="POST" agar data dikirim via POST
            -->
            <form action="../backend/controllers/loginprocess.php" method="POST" id="loginForm">

                <div class="form-group">
                    <label for="username">Username</label>
                    <!--
                        name="username" wajib ada agar PHP bisa membaca $_POST['username']
                        value diisi otomatis jika "Remember Me" aktif
                    -->
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        value="<?php echo isset($_COOKIE['remember_username']) ? htmlspecialchars($_COOKIE['remember_username']) : ''; ?>"
                        required
                        autocomplete="off"
                    >
                    <div class="error-text" id="usernameError">Username tidak boleh kosong</div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <!-- name="password" wajib ada agar PHP bisa membaca $_POST['password'] -->
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                        autocomplete="off"
                    >
                    <div class="error-text" id="passError">Password minimal 3 karakter</div>
                </div>

                <div class="remember">
                    <!--
                        name="remember_me" wajib ada agar PHP bisa cek $_POST['remember_me']
                        checked jika cookie remember_username ada
                    -->
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember_me"
                        <?php echo isset($_COOKIE['remember_username']) ? 'checked' : ''; ?>
                    >
                    <label for="remember">Remember Me</label>
                </div>

                <!--
                    name="login" wajib ada agar PHP bisa cek isset($_POST['login'])
                -->
                <div class="btn-wrapper">
                    <button type="submit" name="login" class="btn-login">Login</button>
                </div>
            </form>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        // Validasi form sebelum dikirim ke server
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            let valid = true;

            // Sembunyikan semua pesan error dulu
            document.querySelectorAll('.error-text').forEach(el => el.style.display = 'none');

            // Cek username tidak kosong
            if (!username) {
                document.getElementById('usernameError').style.display = 'block';
                valid = false;
            }

            // Cek password minimal 3 karakter
            if (!password || password.length < 3) {
                document.getElementById('passError').style.display = 'block';
                valid = false;
            }

            // Jika ada error, batalkan pengiriman form
            if (!valid) {
                e.preventDefault();
                return;
            }

            // Jika valid, form akan dikirim ke backend/controllers/loginprocess.php
            // (tidak perlu e.preventDefault() — biarkan form submit normal)
        });

        // Fungsi toast notification
        function showToast(message, duration = 3000) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), duration);
        }
    </script>
</body>
</html>