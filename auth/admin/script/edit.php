<?php
session_start();

require_once('../../../config/connect.php');

$id = $_GET['id'];

$stmt_select = mysqli_prepare($conn, "SELECT id_barang, nama_barang, harga, stok, gambar FROM barang WHERE id_barang = ?");
mysqli_stmt_bind_param($stmt_select, "i", $id);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);

if (mysqli_num_rows($result) > 0) {
  $data = mysqli_fetch_assoc($result);
} else {
  die("<script>alert('Barang tidak ditemukan!'); window.location='../barang.php';</script>");
}

if (isset($_POST['submit'])) {
  $nama = trim($_POST['nama_barang']);
  $harga = (int)$_POST['harga'];
  $stok = (int)$_POST['stok'];
  $gambar_lama = $data['gambar'];
  $gambar_baru = $gambar_lama;

  if ($harga <= 0 || $stok < 0 || empty($nama)) {
    echo "<script>alert('Data input tidak valid. Pastikan Harga positif, Stok non-negatif, dan Nama barang terisi.');</script>";
    return;
  }

  if (!empty($_FILES['gambar']['name'])) {
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    $upload_path = '../../../uploads/' . basename($gambar);

    if (move_uploaded_file($tmp, $upload_path)) {
      $gambar_baru = basename($gambar);
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
    echo "<script>alert('Barang berhasil diupdate!'); window.location='../barang.php';</script>";
  } else {
    echo "<script>alert('Gagal update barang: " . mysqli_error($conn) . "');</script>";
  }
  mysqli_stmt_close($stmt_update);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Barang: <?= htmlspecialchars($data['nama_barang'] ?? 'Produk') ?></title>
  <link rel="stylesheet" href="../../../public/css/editbarang.css">
</head>

<body>

  <div class="product-list">

    <div id="wrapper">
    <?php include_once("./auth/includes/nav.php") ?>

      <main>
        <form method="post" enctype="multipart/form-data" class="product-card">

          <img
            src="../../../uploads/<?= !empty($data['gambar']) ? htmlspecialchars($data['gambar']) : 'no-image.png' ?>"
            alt="<?= htmlspecialchars($data['nama_barang'] ?? 'Gambar Produk') ?>">

          <div class="product-info">
            <label>Nama Barang:</label><br>
            <input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang'] ?? '') ?>" required><br>

            <label>Harga:</label><br>
            <input type="number" name="harga" value="<?= htmlspecialchars($data['harga'] ?? '') ?>" required><br>

            <label>Stok:</label><br>
            <input type="number" name="stok" value="<?= htmlspecialchars($data['stok'] ?? '') ?>" required><br>

            <label>Ganti Gambar (opsional):</label><br>
            <input type="file" name="gambar"><br>
          </div>

          <div class="actions">
            <button type="submit" name="submit">💾 Simpan</button>
            <a href="../barang.php">Kembali</a>
          </div>

        </form>
      </main>
    </div>

  </div>

</body>

</html>