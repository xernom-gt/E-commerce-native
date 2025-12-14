<?php
session_start();

require_once('../../config/function.php');
require_once('../../config/connect.php'); 

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php'); 
    exit();
}

$current_user_name = $_SESSION['user']['name'] ?? 'Admin';

$users = take('user'); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna | Admin</title>
    <link rel="stylesheet" href="../../public/css/admin.css"> 
    <style>
        .user-management-container {
            max-width: 1000px;
            margin: 100px auto 50px;
            padding: 20px;
        }
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        .user-table th, .user-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        .user-table th {
            background-color: #e5d4c3;
            color: #3e3227;
            font-weight: 600;
        }
        .user-table tr:hover {
            background-color: #fffaf6;
        }
        .user-table a {
            text-decoration: none;
            margin-right: 10px;
            font-weight: 500;
        }
        .user-table .edit-btn { color: #4682b4; }
        .user-table .delete-btn { color: #b22222; }
    </style>
</head>

<body>
    <?php include_once('../../includes/nav.php') ?>
    
    <div class="user-management-container">
        <h2>Kelola Data Pengguna</h2>
        <p>Halo, <?= htmlspecialchars($current_user_name) ?>. Anda dapat mengedit atau menghapus data pengguna di sini.</p>

        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role'] ?? 'user') ?></td> 
                            <td>
                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="edit-btn">Edit</a>
                                <a href="delete_user.php?id=<?= $user['id'] ?>" class="delete-btn"
                                   onclick="return confirm('Yakin ingin menghapus user <?= $user['name'] ?>?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Tidak ada data pengguna yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>