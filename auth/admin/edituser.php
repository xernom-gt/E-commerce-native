<?php
session_start();

require_once('../../config/connect.php');
require_once('../../config/function.php'); // Asumsi file ini ada

// --- 1. Validasi Akses (Hanya Admin/Operator yang Boleh Mengedit) ---
// Asumsi role 'operator' memiliki izin untuk mengedit user.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'operator' && $_SESSION['user']['role'] !== 'admin')) {
    header('Location: ../../login.php');
    exit();
}

// --- 2. Ambil ID User yang Akan Diedit ---
$id_user_to_edit = $_GET['id'] ?? null;

if (!$id_user_to_edit) {
    die("<script>alert('ID user tidak ditemukan!'); window.location='./user.php';</script>");
}

// --- 3. Ambil Data User Saat Ini (untuk ditampilkan di form) ---
$stmt_select = mysqli_prepare($conn, "SELECT id, name, email, password, role FROM user WHERE id = ?");
mysqli_stmt_bind_param($stmt_select, "s", $id_user_to_edit);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);

if (mysqli_num_rows($result) === 0) {
    die("<script>alert('User tidak ditemukan!'); window.location='./user.php';</script>");
}
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_select);


// --- 4. Logika Update Data (POST Request) ---
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password']; // Opsional
    $role = $_POST['role'];

    // Validasi input
    if (empty($name) || empty($email) || empty($role)) {
        echo "<script>alert('Nama, Email, dan Role tidak boleh kosong.');</script>";
        // PENTING: Jika ada error, script akan berhenti di sini dan form akan muncul kembali dengan data lama.
    } else {
        
        // Cek apakah password perlu diubah
        $password_to_db = $data['password']; // Default menggunakan hash password lama
        $password_changed = false;

        if (!empty($new_password)) {
            // Jika user memasukkan password baru, hash password tersebut
            $password_to_db = password_hash($new_password, PASSWORD_DEFAULT);
            $password_changed = true;
        }

        // Siapkan query UPDATE
        $query_update = "UPDATE user SET name=?, email=?, password=?, role=? WHERE id=?";
        $stmt_update = mysqli_prepare($conn, $query_update);

        // Bind parameter: ssssi (string, string, string, string, string/varchar id)
        mysqli_stmt_bind_param($stmt_update, "sssss", $name, $email, $password_to_db, $role, $id_user_to_edit);

        if (mysqli_stmt_execute($stmt_update)) {
            
            // Jika user yang mengedit adalah dirinya sendiri, update session
            if ($_SESSION['user']['id'] === $id_user_to_edit) {
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['role'] = $role;
            }

            echo "<script>alert('Data user " . htmlspecialchars($name) . " berhasil diupdate! " . ($password_changed ? "Password juga diubah." : "") . "'); window.location='kelola_admin.php';</script>";
        } else {
            echo "<script>alert('Gagal update user: " . mysqli_error($conn) . "');</script>";
        }
        mysqli_stmt_close($stmt_update);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User: <?= htmlspecialchars($data['name']) ?></title>
    
    <style>
        /* ===== Palet Warna Tema Krem/Coklat (Disesuaikan untuk Halaman Edit) ===== */
        :root {
            --bg-soft: #f8f5f2;     /* Krem Lembut */
            --bg-card: #fffaf6;     /* Putih Gading */
            --text-dark: #3e3227;   /* Coklat Tua Elegan */
            --text-info: #4c3b2f;   /* Coklat sedikit muda */
            --accent-main: #8b5e3c; /* Coklat Hangat (untuk tombol/fokus) */
            --border-light: #e5d4c3;/* Border Krem */
            --border-dark: #bbaaa0; /* Border lebih tegas */
            --success: #6ba050;     /* Hijau daun untuk Simpan */
            --cancel: #a05050;      /* Merah bata untuk Batal */
        }

        /* ===== GLOBAL STYLES (Adaptasi dari tema Anda) ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", "Segoe UI", Tahoma, sans-serif;
        }

        body {
            background-color: var(--bg-soft);
            color: var(--text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .edit-container {
            width: 95%;
            max-width: 500px;
            background-color: var(--bg-card);
            border-radius: 16px;
            box-shadow: 0 6px 14px rgba(110, 84, 60, 0.15);
            padding: 35px;
            border: 1px solid var(--border-light);
        }
        
        h2 {
            text-align: center;
            color: var(--text-dark);
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border-dark);
            padding-bottom: 12px;
            font-size: 1.8rem;
        }

        .user-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
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
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-light);
            border-radius: 6px;
            background-color: #fcf8f5; 
            color: var(--text-dark);
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
            margin-bottom: 15px;
        }

        input:focus, select:focus {
            border-color: var(--accent-main);
            box-shadow: 0 0 0 3px rgba(139, 94, 60, 0.2);
            outline: none;
        }

        /* ===== ACTION BUTTONS ===== */
        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 25px;
        }

        .actions button,
        .actions a {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background-color 0.3s, transform 0.1s;
        }

        .actions button[name="submit"] {
            background-color: var(--success);
            color: var(--bg-card);
        }

        .actions button[name="submit"]:hover {
            background-color: #558742;
            transform: translateY(-1px);
        }

        .actions a {
            background-color: var(--cancel); /* Menggunakan warna merah bata untuk Batal */
            color: var(--bg-card);
        }

        .actions a:hover {
            background-color: #8a4141;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="edit-container">
        <h2>✏️ Edit User: <?= htmlspecialchars($data['name']) ?></h2>
        
        <form method="post" class="user-form">
            
            <label for="name">Nama (Username):</label>
            <input type="text" id="name" name="name" 
                   value="<?= htmlspecialchars($data['name']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" 
                   value="<?= htmlspecialchars($data['email']) ?>" required>
            
            <label for="new_password">Password Baru (Kosongkan jika tidak ingin diubah):</label>
            <input type="password" id="new_password" name="new_password" placeholder="Masukkan password baru">
            <p style="font-size: 0.8em; color: var(--text-info);">Jika dikosongkan, password lama akan tetap digunakan.</p>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user" <?= (isset($data['role']) && $data['role'] === 'user') ? 'selected' : '' ?>>User Biasa</option>
                <option value="operator" <?= (isset($data['role']) && $data['role'] === 'operator') ? 'selected' : '' ?>>Operator</option>
            </select>
            
            <div class="actions">
                <a href="kelola_admin.php">Batal & Kembali</a>
                <button type="submit" name="submit">💾 Simpan Perubahan</button>
            </div>

        </form>
    </div>

</body>

</html>