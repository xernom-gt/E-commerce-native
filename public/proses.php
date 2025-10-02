<?php

include_once("../config/connect.php");
include_once("../config/function.php");

session_start();
$id_barang = $_GET['id'] ?? 0;
$barang = details('barang', $id_barang);


if (!isset($_SESSION['user'])) {
    die("
    <script>alert('login lah')</script>
    <script>window.location.href= 'login.php'</script>
    ");
}

if ($barang['stok'] == 0) {

    $id = $barang['id_barang'];
    die("
    <script>alert('stok abis tau ga?')</script>
    <script>window.location.href='detail.php?id=$id'</script>
    ");
}

$user_id = $_SESSION['user']['id'];

if (isset($_POST['submit'])) {
    $id_barang = (int) $_POST['id_barang'];
    $qty = (int) $_POST['qty'];
    $status = $_POST['status'];
    $id_pesanan = generateID("pesanan", "id_pesanan", "ip");
    $id_detail = generateID('detail_pesanan', 'id_detail', 'dp');

    if (!$barang) {
        die('Barang tidak di temukan');
    }

    $total = $barang['harga'] * $qty;

    $user_id = $_SESSION['user']['id']  ?? 0;
    $query = "INSERT INTO detail_pesanan (id_detail, id_barang, id_user, jumlah, subtotal, tanggal) 
    VALUES ('$id_detail',$id_barang, '$user_id', $qty, $total, NOW())";
    mysqli_query($conn, $query);

    $stok_baru = $barang['stok'] - $qty;
    mysqli_query($conn, "UPDATE barang SET stok=$stok_baru WHERE id_barang=$id_barang");

    mysqli_query($conn, "INSERT INTO pesanan (id_pesanan,id_detail_pesanan,status) VALUES ('$id_pesanan','$id_detail','$status')");


    echo "
    <script>alert('yodah tunggu masih pending')</script>
    <script>window.location.href='index.php'</script>";
}
