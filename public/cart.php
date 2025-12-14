<?php
session_start();
include '../config/connect.php';

// pastikan user login
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Silakan login dulu!'); window.location='login.php';</script>";
    exit;
}

$id_user = $_SESSION['user']['id'];

// Fungsi batal pesanan
if (isset($_POST['batal'])) {
    $id_pesanan = $_POST['id_pesanan'];
    mysqli_query($conn, "UPDATE pesanan SET status='cancel' WHERE id_pesanan='$id_pesanan'");
    echo "<script>alert('Pesanan berhasil dibatalkan!'); window.location='cart.php';</script>";
    exit;
}

// Fungsi hapus pesanan
if (isset($_POST['hapus'])) {
    $id_pesanan = $_POST['id_pesanan'];
    mysqli_query($conn, "DELETE FROM pesanan WHERE id_pesanan='$id_pesanan'");
    echo "<script>alert('Pesanan berhasil dihapus!'); window.location='cart.php';</script>";
    exit;
}

// Ambil data pesanan user
$query = "
SELECT 
    p.id_pesanan,
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
WHERE d.id_user = '$id_user'
ORDER BY p.waktu_pesanan DESC
";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Pesanan</title>
    <link rel="stylesheet" href="../public/css/cart.css">
</head>
<body>

<?php include_once("../includes/navigation.php"); ?>
<br><br><br>

<h2 class="judul">Pesanan Kamu</h2>

<div class="cart-container">
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <div class="cart-item">
            <img class="cart-img"
                src="../uploads/<?= !empty($row['gambar']) ? htmlspecialchars($row['gambar']) : 'no-image.png' ?>"
                alt="<?= htmlspecialchars($row['nama_barang']); ?>">

            <div class="cart-details">
                <h3><?= htmlspecialchars($row['nama_barang']); ?></h3>
                <p>Harga: <span>Rp <?= number_format($row['harga']); ?></span></p>
                <p>Jumlah: <span><?= $row['jumlah']; ?></span></p>
                <p>Total: <span>Rp <?= number_format($row['subtotal']); ?></span></p>
                <p>Status: 
                    <span class="status <?= strtolower($row['status']); ?>">
                        <?= ucfirst($row['status']); ?>
                    </span>
                </p>
                <p class="tanggal"><?= date('d M Y, H:i', strtotime($row['waktu_pesanan'])); ?></p>

                <!-- Tombol Batalkan -->
                <?php if ($row['status'] == 'pending') : ?>
                <form method="POST" class="batal-form" onsubmit="return confirm('Yakin batalkan pesanan ini?')">
                    <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan']; ?>">
                    <button type="submit" name="batal" class="btn-batal">Batalkan</button>
                </form>
                <?php endif; ?>

                <!-- Tombol Hapus -->
                <?php if ($row['status'] == 'cancel') : ?>
                <form method="POST" class="hapus-form" onsubmit="return confirm('Yakin hapus pesanan ini secara permanen?')">
                    <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan']; ?>">
                    <button type="submit" name="hapus" class="btn-hapus">Hapus</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
