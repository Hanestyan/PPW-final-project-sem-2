<?php
// index.php - Login Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFEWALK - Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <!-- BANNER SAFEWALK (PISAH, LEBAR SAMA) -->
        <div class="banner-safewalk">SAFEWALK</div>

        <!-- LOGIN CARD -->
        <div class="login-container">
            <h2>Login</h2>
            <form action="backend/controllers/loginprocess.php" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" placeholder="Contoh: Aries" required autocomplete="off">
                    <div class="error-text" id="namaError">Please enter your name</div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;" required autocomplete="off">
                    <div class="error-text" id="passError">Password must be at least 3 characters</div>
                </div>
                <div class="remember">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember Me</label>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nama = document.getElementById('nama').value.trim();
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;
            let valid = true;

            document.querySelectorAll('.error-text').forEach(el => el.style.display = 'none');

            if (!nama) {
                document.getElementById('namaError').style.display = 'block';
                valid = false;
            }
            
            if (!password || password.length < 3) {
                document.getElementById('passError').style.display = 'block';
                valid = false;
            }

            if (!valid) return;

            const userData = {
                name: nama,
                role: 'Student',
                email: nama.toLowerCase().replace(/\s+/g, '.') + '@safewalk.edu'
            };

            if (remember) {
                localStorage.setItem('safewalk_user', JSON.stringify(userData));
                localStorage.setItem('safewalk_remembered', nama);
            } else {
                sessionStorage.setItem('safewalk_user', JSON.stringify(userData));
                localStorage.removeItem('safewalk_remembered');
            }

            showToast('Welcome, ' + nama + '! Redirecting...');
            
            setTimeout(() => {
                window.location.href = 'views/mahasiswa/dashboard_user.php';
            }, 1200);
        });

        const rememberedName = localStorage.getItem('safewalk_remembered');
        if (rememberedName) {
            document.getElementById('nama').value = rememberedName;
            document.getElementById('remember').checked = true;
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
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