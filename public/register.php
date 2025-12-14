<?php

include_once("../config/connect.php");
include_once("../config/function.php");

$name = "";
$email = "";
$password = "";
$cf_password = "";
$error = "";


if (isset($_POST['submit'])) {
    $name = trim($_POST['name']); // Bersihkan input
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $cf_password = $_POST['cf_password'];

    if (empty($name) || empty($email) || empty($password) || empty($cf_password)) {
        $error = "Harap isi semua kolom di atas!";
    } else {
        if ($password !== $cf_password) {
            $error = "Konfirmasi password tidak cocok!";
        } else {
            // --- Pengecekan Keamanan (Disarankan) ---
            
            // 1. Cek duplikasi email (Asumsi function take() / query manual)
            // Lakukan pengecekan di database apakah email sudah ada.
            $check_stmt = mysqli_prepare($conn, "SELECT id FROM user WHERE email = ?");
            mysqli_stmt_bind_param($check_stmt, "s", $email);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);

            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                 $error = "Email sudah terdaftar. Silakan gunakan email lain.";
            } else {
                // 2. HASH PASSWORD (SANGAT PENTING UNTUK KEAMANAN)
                // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Menggunakan logika Anda: user($name, $email, $password)
                // Anda harus memastikan function user() menerima password yang sudah di-hash
                // atau di-hash di dalamnya, dan menggunakan prepared statement untuk INSERT.
                
                $result = user($name, $email, $password); // *PERINGATAN: Password TIDAK di-hash di sini.*
    
                if ($result) {
                    header("Location: login.php?succes=registered");
                    exit();
                } else {
                    $error = "Pendaftaran gagal: " . mysqli_error($conn);
                };
            }
            mysqli_stmt_close($check_stmt);
        }
    };
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | E-Commerce</title>
    
    <style>
        /* ===== Palet Warna Tema Krem/Coklat ===== */
        :root {
            --bg-soft: #f8f5f2;     /* Krem Lembut */
            --bg-card: #fffaf6;     /* Putih Gading */
            --text-dark: #3e3227;   /* Coklat Tua Elegan */
            --text-info: #4c3b2f;   /* Coklat sedikit muda */
            --accent-main: #8b5e3c; /* Coklat Hangat (untuk tombol/fokus) */
            --border-light: #e5d4c3;/* Border Krem */
            --button-bg: #6ba050;   /* Hijau daun untuk tombol register */
            --error-color: #a05050; /* Merah bata untuk error */
        }

        /* ===== GLOBAL RESET & LAYOUT ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", "Segoe UI", Tahoma, sans-serif;
        }

        body {
            background: var(--bg-soft);
            color: var(--text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* ===== REGISTER FORM STYLE ===== */
        form {
            background: var(--bg-card);
            padding: 40px 30px;
            border-radius: 16px;
            border: 1px solid var(--border-light);
            box-shadow: 0 6px 14px rgba(110, 84, 60, 0.15);
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        h2 {
            text-align: center;
            color: var(--text-dark);
            margin-bottom: 10px;
            font-size: 2rem;
        }

        form > div > div { /* Target div yang membungkus label dan input */
            width: 100%;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--text-info);
            font-size: 0.95rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            background-color: #fcf8f5; 
            color: var(--text-dark);
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input:focus {
            border-color: var(--accent-main);
            box-shadow: 0 0 0 3px rgba(139, 94, 60, 0.2);
            outline: none;
        }

        button[name="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: var(--button-bg);
            color: var(--bg-card);
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
            margin-top: 10px;
        }

        button[name="submit"]:hover {
            background-color: #558742;
            transform: translateY(-1px);
        }

        a {
            text-align: center;
            color: var(--accent-main);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        
        a:hover {
            text-decoration: underline;
        }

        /* Error Message */
        p {
            text-align: center;
            color: var(--error-color);
            background: #ffebeb;
            border: 1px solid var(--error-color);
            padding: 10px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-top: 10px; /* Tambahkan margin agar tidak terlalu rapat */
        }
    </style>
</head>

<body>

    <form action="" method="post">
        <div>
            <h2>Registrasi Akun</h2>
            
            <?php if ($error): ?>
                <p><?= $error ?></p>
            <?php endif; ?>

            <div>
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required> 
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <label for="cf_password">Confirm Password</label>
                <input type="password" name="cf_password" id="cf_password" required>
            </div>
            <div>
                <button name="submit">Daftar</button>
            </div>
        </div>
        <center>
            <a href="./login.php">Sudah punya akun? Login di sini.</a>
        </center>
    </form>
</body>

</html>