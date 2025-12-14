<?php
session_start();

require_once('../../config/function.php');
require_once('../../config/connect.php');

// Ambil keyword pencarian
$keyword = isset($_GET['q']) ? $_GET['q'] : '';

// ambil data barang sesuai pencarian
$barang = ($keyword != '')
    ? search("barang", "nama_barang", $keyword)
    : take("barang");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Barang</title>
    
    <style>
        /* ===== RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", "Segoe UI", Tahoma, sans-serif;
        }
        
        /* ===== BODY & WRAPPER ===== */
        body {
            background: #f8f5f2; /* warna krem lembut */
            color: #3e3227; /* coklat tua elegan */
            padding-top: 80px; /* Jaga jarak dari navbar (asumsi nav fixed/tinggi 80px) */
            min-height: 100vh;
        }

        /* Container untuk seluruh konten (menggantikan <main>) */
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 20px;
        }
        
        /* ===== UTILITY BUTTON (Tambah Barang) ===== */
        .header-actions {
            margin-bottom: 25px;
            text-align: left;
        }

        .add-button {
            padding: 10px 20px;
            background: #8b5e3c; /* Coklat elegan */
            color: #fffaf6;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .add-button:hover {
            background: #a37250; /* Coklat lebih muda saat hover */
        }
        
        /* ===== PRODUCT LIST ===== */
        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Tengahkan card */
            gap: 25px;
        }
        
        /* ===== PRODUCT CARD ===== */
        .product-card {
            background: #fffaf6; /* putih gading */
            border-radius: 16px;
            border: 1px solid #e5d4c3;
            width: 230px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 3px 8px rgba(110, 84, 60, 0.08);
            display: flex;
            flex-direction: column;
        }
        
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 14px rgba(110, 84, 60, 0.15);
        }
        
        /* ===== IMAGE ===== */
        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid #e9d9c8;
        }
        
        /* ===== PRODUCT INFO & ACTIONS ===== */
        .product-info {
            padding: 15px 16px;
            text-align: left; /* Teks info rata kiri */
            flex-grow: 1; /* Agar info mengisi ruang sisa */
        }
        
        .product-info h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #4c3b2f;
            margin-bottom: 8px;
            /* Pastikan teks tetap di satu baris */
            text-overflow: ellipsis; 
            white-space: nowrap;
            overflow: hidden;
        }
        
        .price {
            color: #8b5e3c;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 4px;
        }
        
        .stock {
            font-size: 0.85rem;
            color: #7c6a5b;
        }

        .actions {
            padding: 0 16px 15px; /* Padding bawah untuk pemisah aksi */
            display: flex;
            justify-content: space-around;
            border-top: 1px solid #e5d4c3;
            margin-top: auto; /* Dorong ke bawah card */
        }
        
        .actions a {
            text-decoration: none;
            font-weight: 500;
            padding: 10px 5px;
            transition: color 0.2s ease;
        }

        /* Styling spesifik untuk Edit */
        .actions a[href*="edit.php"] {
            color: #4c3b2f; /* Coklat tua */
        }
        .actions a[href*="edit.php"]:hover {
            color: #8b5e3c; /* Coklat elegan saat hover */
        }

        /* Styling spesifik untuk Delete */
        .actions a[href*="delete.php"] {
            color: #a04040; /* Merah bata/tua */
        }
        .actions a[href*="delete.php"]:hover {
            color: #cc5050; 
        }

        /* Styling untuk pesan 'tidak ditemukan' */
        .product-list > p {
            width: 100%;
            text-align: center;
            font-size: 1.2rem;
            color: #7c6a5b;
            margin-top: 50px;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .product-card {
                width: 45%;
            }
            .content-wrapper {
                padding: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .product-card {
                width: 90%;
            }
            
            body {
                padding-top: 70px;
            }
        }
    </style>
</head>

<body>
    <?php include_once('../includes/nav.php') ?>
    
    <div class="content-wrapper">
        
        <div class="header-actions">
            <a href="script/tambah.php" class="add-button">+ Tambah Barang</a>
        </div>

        <?php if (!empty($barang)): ?>
            <div class="product-list">
                <?php foreach ($barang as $row): ?>
                    <div class="product-card">
                        <img src="../../uploads/<?= !empty($row['gambar']) ? htmlspecialchars($row['gambar']) : 'no-image.png' ?>"
                            alt="<?= htmlspecialchars($row['nama_barang']) ?>">
                        <div class="product-info">
                            <h3><?= htmlspecialchars($row['nama_barang']) ?></h3>
                            <p class="price">Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
                            <p class="stock">Stok: <?= htmlspecialchars($row['stok']) ?></p>
                        </div>
                        <div class="actions">
                            <a href="edit.php?id=<?= $row['id_barang'] ?>">✏️ Edit</a> 
                            <span style="color: #e5d4c3;">|</span> <a href="delete.php?id=<?= $row['id_barang'] ?>" onclick="return confirm('Yakin mau hapus barang ini?')">🗑️ Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; margin-top: 50px;">Barang tidak ditemukan.</p>
        <?php endif; ?>
    </div>

</body>

</html>