<?php

session_start();
require_once('../../config/function.php');
require_once('../../config/connect.php');

// Ambil data user (jika ingin menampilkan nama)
$name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Tamu';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    
    <style>
        /* Pastikan elemen body mengisi seluruh viewport */
        body, html {
            height: 100%;
            margin: 0;
            padding-top: 0; /* Hapus padding-top default jika ada dari CSS lain */
            box-sizing: border-box;
        }

        /* Container utama (wrapper) harus mengisi seluruh ruang yang tersisa setelah header */
        #wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Setidaknya setinggi viewport */
        }
        
        /* Main content: Menggunakan flexbox untuk menengahkan secara vertikal dan horizontal */
        main {
            flex-grow: 1; /* Memastikan main mengisi seluruh ruang yang tersisa */
            display: flex;
            justify-content: center; /* Menengahkan horizontal */
            align-items: center; /* Menengahkan vertikal */
            text-align: center; /* Menengahkan teks di dalam elemen */
            padding-top: 60px; /* Jaga jarak dari header/navbar (sesuaikan dengan tinggi navbar) */
        }
        
        /* Hilangkan tag <center> yang sudah deprecated */
        .welcome-text h1 {
            font-size: 2.5em;
            color: #333;
        }
    </style>
    
</head>

<body>
    <div id="wrapper">
        <?php include_once("../includes/nav.php") ?>

        <main>
            <div class="welcome-text">
                <h1>Selamat Datang, <?= htmlspecialchars($name) ?></h1>
            </div>
        </main>
    </div>

</body>

</html>