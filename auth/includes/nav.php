<?php

$name = isset($_SESSION['user']) ? $_SESSION['user']['name'] : 'Admin';
$role = $_SESSION['user']['role'] ?? 'admin';
$gambar = isset($_SESSION['user']['gambar']) && !empty($_SESSION['user']['gambar'])
    ? $_SESSION['user']['gambar']
    : 'default.png';
?>

<link rel="stylesheet" href="../../public/css/nav.css">
<script src="../../public/javascript/nav.js" defer></script>

<header>
  <nav class="navbar">
    <div class="logo">
      <a href="index.php"><img src="../../uploads/logo-web.png" alt="Logo"></a>
    </div>

    <ul class="nav-links">
      <li>
        <div class="profile">
          <img src="../../uploads/<?= htmlspecialchars($gambar) ?>" alt="<?= htmlspecialchars($gambar) ?>">
          <p><?= htmlspecialchars($name) ?></p>
        </div>

        <div class="settings">
          <p><a href="index.php">Dashboard</a></p>
          <p><a href="barang.php">Data Barang</a></p>
          <p><a href="kelola_admin.php">Kelola Akun</a></p>
          <p><a href="../../public/index.php">User</a></p>
          <p><a href="../../public/logout.php" onclick="return confirm('Yakin ingin log out?')">Log out</a></p>
        </div>
      </li>
    </ul>
  </nav>
</header>
