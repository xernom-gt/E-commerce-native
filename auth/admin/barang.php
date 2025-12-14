<?php
session_start();

require_once('../../config/function.php');
require_once('../../config/connect.php');

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
    <title>Document</title>
    <link rel="stylesheet" href="../../public/css/barang.css">
</head>

<body>
    <?php include_once('../includes/nav.php') ?>
    <br>
    <br>
    <div class="product-list">
        <div style="margin-bottom: 20px;">
            <a href="script/tambah.php" style="padding: 8px 16px; background: green; color: white; text-decoration: none; border-radius: 4px;">+ Tambah Barang</a>
        </div>

        <?php if (!empty($barang)): ?>
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
                        <a href="script/edit.php?id=<?= $row['id_barang'] ?>" style="color: blue;">✏️ Edit</a> |
                        <a href="script/delete.php?id=<?= $row['id_barang'] ?>" style="color: red;" onclick="return confirm('Yakin mau hapus barang ini?')">🗑️ Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Barang tidak ditemukan.</p>
        <?php endif; ?>
    </div>

</body>

</html>