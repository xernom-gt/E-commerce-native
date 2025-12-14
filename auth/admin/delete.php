<?php
require_once('./config/connect.php');
require_once('./config/function.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM barang WHERE id_barang = '$id'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Barang berhasil dihapus!'); window.location='./barang.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus barang!'); window.location='./barang.php';</script>";
    }
}
?>
