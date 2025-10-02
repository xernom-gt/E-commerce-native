<?php
include_once("../config/function.php");
include_once("../config/connect.php");
session_start();

// ambil keyword pencarian
$keyword = isset($_GET['q']) ? $_GET['q'] : '';

// ambil data barang sesuai pencarian
$barang = ($keyword != '')
  ? search("barang", "nama_barang", $keyword)
  : take("barang");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Banquetia</title>
  <link rel="stylesheet" href="../public/css/index.css">
</head>

<body>
  <div id="wrapper">
    <?php include_once("../includes/navigation.php") ?>

    <main>
      <div class="product-list">
        <?php if (!empty($barang)): ?>
          <?php foreach ($barang as $row):

            if (!isset($_SESSION['user'])) {
              die("
              <script>alert('login lah')</script>
              <script>window.location.href= 'login.php'</script>
              ");
            }

          ?>

            <div class="product-card" onclick="window.location.href='detail.php?id=<?= $row['id_barang'] ?>'">
              <img
                src="../uploads/<?= !empty($row['gambar']) ? htmlspecialchars($row['gambar']) : 'no-image.png' ?>"
                alt="<?= htmlspecialchars($row['nama_barang']) ?>">
              <div class="product-info">
                <h3><?= htmlspecialchars($row['nama_barang']) ?></h3>
                <p class="price">Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
                <p class="stock">Stok: <?= htmlspecialchars($row['stok']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Barang tidak ditemukan.</p>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <script>
    function toggleMenu() {
      document.querySelector(".nav-links").classList.toggle("show");
    }
  </script>
</body>

</html>