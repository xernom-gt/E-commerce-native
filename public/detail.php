<?php
require_once('../includes/navigation.php');

require_once('../config/function.php');
require_once('../config/connect.php');

session_start();
$id = isset($_GET['id']) ? $_GET['id'] : 0; 
$barang = details('barang', $id);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($barang['nama_barang']) ?></title>
    <link rel="stylesheet" href="../public/css/detail.css">
</head>

<body>
    <div class="product-container">
        <button onclick="window.location.href='index.php'">kembali</button>
        <img
            src="../uploads/<?= !empty($barang['gambar']) ? htmlspecialchars($barang['gambar']) : 'no-image.png' ?>"
            alt="<?= htmlspecialchars($barang['nama_barang']) ?>">
        <div class="product-details">
            <h1><?= $barang['nama_barang'] ?></h1>
            <p>Harga : Rp<?= number_format($barang['harga'], 0, ',', ',') ?></p>
            <p>Stock : <?= $barang['stok'] ?></p>
            <form action="proses.php?id=<?= $barang['id_barang']?>" method="post">
                <input type="hidden" name="id_barang" value="<?= $barang['id_barang'] ?>">
                <input type="hidden" name="status" value="pending">
                <input type="hidden" name="harga" value="<?= $barang['harga'] ?>">

                <label for="qty">Jumlah :</label>
                <input type="number" id="qty" name="qty" min='1' max="<?= $barang['stok'] ?>" value="1">

                <button type="submit" name='submit'>Check Out</button>
            </form>
        </div>
    </div>
</body>

</html>