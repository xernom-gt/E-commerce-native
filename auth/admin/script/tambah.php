<?php
require_once('../../../config/connect.php');

if (isset($_POST['submit'])) {
    $nama = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "../../../uploads/" . $gambar);

    $query = "INSERT INTO barang (nama_barang, harga, stok, gambar) VALUES ('$nama', '$harga', '$stok', '$gambar')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Barang berhasil ditambahkan!'); window.location='../barang.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan barang!');</script>";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <label>Nama Barang:</label><br>
    <input type="text" name="nama_barang" required><br><br>

    <label>Harga:</label><br>
    <input type="number" name="harga" required><br><br>

    <label>Stok:</label><br>
    <input type="number" name="stok" required><br><br>

    <label>Gambar:</label><br>
    <input type="file" name="gambar" required><br><br>

    <button type="submit" name="submit">Tambah</button>
</form>