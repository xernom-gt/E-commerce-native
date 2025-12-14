<?php
session_start();

require_once('../../config/connect.php');
require_once('../../config/function.php');

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'operator' && $_SESSION['user']['role'] !== 'admin')) {
    header('Location: ../../login.php');
    exit();
}

$id_user_to_delete = $_GET['id'] ?? null;

if (!$id_user_to_delete) {
    die("<script>alert('ID user tidak ditemukan!'); window.location='kelola_admin.php';</script>");
}

if ($id_user_to_delete === $_SESSION['user']['id']) {
    die("<script>alert('Anda tidak bisa menghapus akun Anda sendiri!'); window.location='kelola_admin.php';</script>");
}

$stmt_check = mysqli_prepare($conn, "SELECT name FROM user WHERE id = ?");
mysqli_stmt_bind_param($stmt_check, "s", $id_user_to_delete);
mysqli_stmt_execute($stmt_check);
$result = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt_check);
    die("<script>alert('User tidak ditemukan di database.'); window.location='kelola_admin.php';</script>");
}
$user_data = mysqli_fetch_assoc($result);
$user_name = htmlspecialchars($user_data['name']);
mysqli_stmt_close($stmt_check);

$query_delete = "DELETE FROM user WHERE id = ?";
$stmt_delete = mysqli_prepare($conn, $query_delete);

mysqli_stmt_bind_param($stmt_delete, "s", $id_user_to_delete);

if (mysqli_stmt_execute($stmt_delete)) {
    echo "<script>alert('User $user_name (ID: $id_user_to_delete) berhasil dihapus.'); window.location='kelola_admin.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus user: " . mysqli_error($conn) . "'); window.location='kelola_admin.php';</script>";
}

mysqli_stmt_close($stmt_delete);
mysqli_close($conn);
?>