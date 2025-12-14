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
    $nama = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar_lama = $data['gambar'];
    $gambar_baru = $gambar_lama;
    
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
<link rel="stylesheet" href="../../public/css/admin.css">
</head>
<body>

<div class="product-list" style="display:flex; justify-content:center; margin-top:40px;">
  <form method="post" enctype="multipart/form-data" class="product-card" style="width:320px; padding:20px; background:#fff; box-shadow:0 3px 10px rgba(0,0,0,0.1); border-radius:10px;">
    
    <img 
      src="../../../uploads/<?= !empty($data['gambar']) ? htmlspecialchars($data['gambar']) : 'no-image.png' ?>" 
      alt="<?= htmlspecialchars($data['nama_barang'] ?? 'Gambar Produk') ?>" 
      style="width:100%; border-radius:8px; margin-bottom:15px;">

    <div class="product-info">
      <label>Nama Barang:</label><br>
      <input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang'] ?? '') ?>" required
            style="width:100%; padding:8px; margin:6px 0; border:1px solid #ccc; border-radius:5px;"><br>

      <label>Harga:</label><br>
      <input type="number" name="harga" value="<?= htmlspecialchars($data['harga'] ?? '') ?>" required
            style="width:100%; padding:8px; margin:6px 0; border:1px solid #ccc; border-radius:5px;"><br>

      <label>Stok:</label><br>
      <input type="number" name="stok" value="<?= htmlspecialchars($data['stok'] ?? '') ?>" required
            style="width:100%; padding:8px; margin:6px 0; border:1px solid #ccc; border-radius:5px;"><br>

      <label>Ganti Gambar (opsional):</label><br>
      <input type="file" name="gambar" style="margin:8px 0;"><br>
    </div>

    <div class="actions" style="margin-top:10px; text-align:center;">
      <button type="submit" name="submit" 
        style="padding:10px 20px; background:#4CAF50; color:white; border:none; border-radius:6px; cursor:pointer;">💾 Simpan</button>
      <a href="../barang.php" 
        style="margin-left:10px; padding:10px 20px; background:#ccc; color:black; text-decoration:none; border-radius:6px;">Kembali</a>
    </div>

  </form>
</div>

</body>
</html>