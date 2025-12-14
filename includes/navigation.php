<?php
// Cek session user
$name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Tamu';
$role = $_SESSION['user']['role'] ?? 'user';
$gambar = isset($_SESSION['user']['gambar']) && !empty($_SESSION['user']['gambar'])
  ? $_SESSION['user']['gambar']
  : 'default.png';
?>

<link rel="stylesheet" href="../public/css/nav.css">

<header>
  <nav class="navbar">
    <!-- Logo -->
    <div class="logo">
      <a href="index.php"><img src="../uploads/logo-web.png" alt="Logo"></a>
    </div>

    <!-- Pencarian -->
    <div class="search">
      <form action="index.php" method="get">
        <input type="text" name="q" placeholder="Cari makanan...">
        <button type="submit" name="submit">Cari</button>
      </form>
    </div>

    <!-- Menu -->
    <ul class="nav-links">
      <li>
        <div class="profile">
          <img src="../uploads/<?= htmlspecialchars($gambar) ?>" alt="<?= htmlspecialchars($name) ?>">
          <p><?= htmlspecialchars($name) ?></p>
        </div>

        <div class="settings">
          <p><a href="index.php">Home</a></p>
          <p><a href="cart.php">Keranjang</a></p>
          <?php if ($role === 'operator'): ?>
            <p><a href="../auth/admin/index.php">Dashboard Admin</a></p>
          <?php endif; ?>
          <p><a href="logout.php" onclick="return confirm('Yakin ingin log out?')">Log out</a></p>
        </div>
      </li>
    </ul>
  </nav>
</header>

<script src="../public/javascript/nav.js" defer></script>
