<?php
session_start();

require_once('../../config/connect.php');
require_once('../../config/function.php');

// Pengecekan ID dan pengambilan data (menggunakan prepared statement)
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("<script>alert('ID barang tidak valid!'); window.location='./barang.php';</script>");
}

$stmt_select = mysqli_prepare($conn, "SELECT id_barang, nama_barang, harga, stok, gambar FROM barang WHERE id_barang = ?");
mysqli_stmt_bind_param($stmt_select, "i", $id);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
} else {
    die("<script>alert('Barang tidak ditemukan!'); window.location='./barang.php';</script>");
}
mysqli_stmt_close($stmt_select); 

// --- Logika Update ---
if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama_barang']);
    // Filter input seperti sebelumnya...
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $gambar_lama = $data['gambar'];
    $gambar_baru = $gambar_lama;

    if ($harga <= 0 || $stok < 0 || empty($nama)) {
        echo "<script>alert('Data input tidak valid. Pastikan Harga positif, Stok non-negatif, dan Nama barang terisi.');</script>";
    } else {

        if (!empty($_FILES['gambar']['name'])) {
            $gambar = $_FILES['gambar']['name'];
            $tmp = $_FILES['gambar']['tmp_name'];

            $ext = pathinfo($gambar, PATHINFO_EXTENSION);
            $new_name = uniqid('img_', true) . '.' . $ext;
            
            $upload_path = '../../uploads/' . $new_name;

            if (move_uploaded_file($tmp, $upload_path)) {
                $gambar_baru = $new_name;
                
                if (!empty($gambar_lama) && $gambar_lama != 'no-image.png' && file_exists('../../uploads/' . $gambar_lama)) {
                    unlink('../../uploads/' . $gambar_lama);
                }
            } else {
                echo "<script>alert('Gagal mengunggah gambar baru!');</script>";
            }
        }

        $stmt_update = mysqli_prepare($conn, "UPDATE barang SET 
                                             nama_barang=?, 
                                             harga=?, 
                                             stok=?, 
                                             gambar=? 
                                             WHERE id_barang=?");

        mysqli_stmt_bind_param($stmt_update, "sdisi", $nama, $harga, $stok, $gambar_baru, $id);

        if (mysqli_stmt_execute($stmt_update)) {
            echo "<script>alert('Barang berhasil diupdate!'); window.location='./barang.php';</script>";
        } else {
            echo "<script>alert('Gagal update barang: " . mysqli_error($conn) . "');</script>";
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
    <title>Edit Barang: <?= htmlspecialchars($data['nama_barang'] ?? 'Produk') ?></title>
    
    <style>
        /* ===== Palet Warna Tema Krem/Coklat ===== */
        :root {
            --bg-soft: #f8f5f2;     /* Krem Lembut */
            --bg-card: #fffaf6;     /* Putih Gading */
            --text-dark: #3e3227;   /* Coklat Tua Elegan */
            --text-info: #4c3b2f;   /* Coklat sedikit muda */
            --accent-main: #8b5e3c; /* Coklat Hangat (untuk tombol/fokus) */
            --border-light: #e5d4c3;/* Border Krem */
            --border-dark: #bbaaa0; /* Border lebih tegas */
            --success: #6ba050;     /* Hijau daun */
            --danger: #a05050;      /* Merah bata */
        }

        /* ===== GLOBAL STYLES ===== */
        body {
            font-family: "Poppins", "Segoe UI", Tahoma, sans-serif;
            background-color: var(--bg-soft);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .edit-container {
            width: 95%;
            max-width: 600px;
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

        .product-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }
        
        .image-preview-container {
            flex: 1 1 150px;
            text-align: center;
        }

        .current-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--border-dark);
            margin-bottom: 15px;
        }
        
        .product-info {
            flex: 2 1 300px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-info);
            font-size: 0.95rem;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid var(--border-light);
            border-radius: 6px;
            background-color: #fcf8f5; /* Sedikit lebih gelap dari card */
            color: var(--text-dark);
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        input[type="file"] {
            border: none;
            background: none;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: var(--accent-main);
            box-shadow: 0 0 0 3px rgba(139, 94, 60, 0.2);
            outline: none;
        }

        /* ===== ACTION BUTTONS ===== */
        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 20px;
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
            background-color: var(--border-dark);
            color: var(--bg-card);
        }

        .actions a:hover {
            background-color: #9c8e84;
            transform: translateY(-1px);
        }

        /* ===== RESPONSIVITAS ===== */
        @media (max-width: 600px) {
            .form-group {
                flex-direction: column;
                gap: 10px;
            }
            .edit-container {
                padding: 25px;
            }
        }
    </style>
</head>

<body>
    <div class="edit-container">
        <h2>✏️ Edit Barang: <?= htmlspecialchars($data['nama_barang'] ?? 'Produk') ?></h2>
        
        <form method="post" enctype="multipart/form-data" class="product-form">
            
            <div class="form-group">
                
                <div class="image-preview-container">
                    <label>Gambar Saat Ini:</label>
                    <img class="current-img"
                        src="../../uploads/<?= !empty($data['gambar']) ? htmlspecialchars($data['gambar']) : 'no-image.png' ?>"
                        alt="<?= htmlspecialchars($data['nama_barang'] ?? 'Gambar Produk') ?>">
                    <input type="file" name="gambar">
                </div>
                
                <div class="product-info">
                    <label for="nama_barang">Nama Barang:</label>
                    <input type="text" id="nama_barang" name="nama_barang" 
                           value="<?= htmlspecialchars($data['nama_barang'] ?? '') ?>" required>

                    <label for="harga">Harga:</label>
                    <input type="number" id="harga" name="harga" 
                           value="<?= htmlspecialchars($data['harga'] ?? '') ?>" required>

                    <label for="stok">Stok:</label>
                    <input type="number" id="stok" name="stok" 
                           value="<?= htmlspecialchars($data['stok'] ?? '') ?>" required>
                    
                </div>
            </div>

            <div class="actions">
                <a href="./barang.php">Batal & Kembali</a>
                <button type="submit" name="submit">💾 Simpan Perubahan</button>
            </div>

        </form>
    </div>

</body>

</html>