<?php

session_start();
require_once('../../config/function.php');
require_once('../../config/connect.php');

// PENTING: Lakukan pengecekan apakah user yang login adalah ADMIN
// Jika tidak, sebaiknya arahkan mereka ke halaman user biasa atau tampilkan error.
$user_role = $_SESSION['user']['role'] ?? 'user';

if ($user_role !== 'operator') {
    echo "<script>alert('Akses ditolak. Anda harus login sebagai Admin.'); window.location='../index.php';</script>";
    exit;
}

// Ambil data user admin (jika ingin menampilkan nama admin di halaman)
$name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'operator';

// Logika pemrosesan form "complete" (hanya boleh diakses admin)
if (isset($_POST['complete'])) {
    $id_pesanan = $_POST['id_pesanan'];
    
    // Admin dapat menyelesaikan pesanan mana pun, tidak perlu filter $id_user
    $id_pesanan_safe = mysqli_real_escape_string($conn, $id_pesanan);
    
    // Perbarui status pesanan
    mysqli_query($conn, "UPDATE pesanan SET status='complete' WHERE id_pesanan='$id_pesanan_safe'");
    
    echo "<script>alert('Pesanan berhasil diselesaikan (complete)!'); window.location='index.php';</script>";
    exit;
}


// MODIFIKASI QUERY: Menangkap semua pesanan dari semua user
$query = "
SELECT 
    p.id_pesanan,
    u.name AS nama_user,  -- Tambahkan nama user
    b.nama_barang,
    b.gambar,
    b.harga,
    d.jumlah,
    d.subtotal,
    p.status,
    p.waktu_pesanan
FROM detail_pesanan d
JOIN barang b ON d.id_barang = b.id_barang
JOIN pesanan p ON p.id_detail_pesanan = d.id_detail
JOIN user u ON d.id_user = u.id  -- Tambahkan JOIN ke tabel user
ORDER BY p.waktu_pesanan DESC
";

$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Semua Pesanan (Admin)</title>
    
    <style>
        /* CSS yang sudah ada */
        body,
        html {
            height: 100%;
            margin: 0;
            padding-top: 0;
            box-sizing: border-box;
        }

        #wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            text-align: center;
            padding-top: 80px; 
        }
        
        .welcome-text {
            margin-bottom: 20px;
        }

        .welcome-text h1 {
            font-size: 2.5em;
            color: #333;
        }

        .cart-container {
            width: 90%;
            max-width: 900px; /* Lebar lebih besar untuk info user */
            margin: 20px auto;
        }
        
        .cart-item {
            display: flex;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            text-align: left;
        }

        .cart-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 15px;
        }
        
        .status.pending { background-color: orange; color: white; padding: 4px 8px; border-radius: 4px; }
        .status.complete { background-color: green; color: white; padding: 4px 8px; border-radius: 4px; }
        .btn-complete { background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; margin-top: 10px; }
    </style>

</head>

<body>
    <div id="wrapper">
        <?php include_once("../includes/nav.php") ?>

        <main>
            <div class="welcome-text">
                <h1>Selamat Datang, <?= htmlspecialchars($name); ?>!</h1>
                <h2>Manajemen Semua Pesanan Pengguna</h2>
            </div>
            
            <div class="cart-container">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <div class="cart-item">
                            <img class="cart-img"
                                src="../../uploads/<?= !empty($row['gambar']) ? htmlspecialchars($row['gambar']) : 'no-image.png' ?>"
                                alt="<?= htmlspecialchars($row['nama_barang']); ?>">

                            <div class="cart-details">
                                <h3><?= htmlspecialchars($row['nama_barang']); ?> (ID: #<?= $row['id_pesanan']; ?>)</h3>
                                <h4>Pemesan: <span><?= htmlspecialchars($row['nama_user']); ?></span></h4> <hr>
                                <p>Harga: <span>Rp <?= number_format($row['harga']); ?></span></p>
                                <p>Jumlah: <span><?= $row['jumlah']; ?></span></p>
                                <p>Total: <span>Rp <?= number_format($row['subtotal']); ?></span></p>
                                <p>Status:
                                    <span class="status <?= strtolower($row['status']); ?>">
                                        <?= ucfirst($row['status']); ?>
                                    </span>
                                </p>
                                <p class="tanggal"><?= date('d M Y, H:i', strtotime($row['waktu_pesanan'])); ?></p>

                                <?php if ($row['status'] == 'pending') : ?>
                                    <form method="POST" class="complete-form" 
                                        onsubmit="return confirm('Yakin ingin menyelesaikan pesanan dari <?= htmlspecialchars($row['nama_user']); ?>?')">
                                        <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan']; ?>">
                                        <button type="submit" name="complete" class="btn-complete">Tandai Selesai</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Belum ada pesanan yang masuk.</p>
                <?php endif; ?>
            </div>

        </main>
    </div>

</body>

</html>