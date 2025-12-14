<?php

session_start();

$role = $_GET['role'] ?? 'user';

if ($role == 'operator') {
    header('Location: ../auth/index.php');
    exit;
} else {
    echo "<script>
        alert('Maaf, anda tidak memiliki akses');
        window.location.href = 'index.php';
    </script>";
    exit;
}

?>