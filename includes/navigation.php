<?php
// cek session user
$name = isset($_SESSION['user']) ? $_SESSION['user']['name'] : 'tamu';
?>

<link rel="stylesheet" href="../public/css/navigation.css">
<header>
  <nav class="navbar">
    <!-- logo -->
    <div class="logo">
      <a href="index.php"><img src="../uploads/logo-web.png" alt="Logo"></a>
    </div>

    <!-- pencarian -->
    <div class="search">
      <form action="index.php" method="get">
        <input type="text" name="q" placeholder="Cari makanan.....">
        <button type="submit" name="submit">Cari</button>
      </form>
    </div>

    <!-- hamburger -->
    <div class="hamburger" onclick="toggleMenu()">&#9776;</div>

    <!-- menu -->
    <ul class="nav-links">
      <li>
        <div class="profile" onclick="toggleProfile()">
          <img src="../uploads/profile.png <?= !empty($_SESSION['user']['gambar']) ? $_SESSION['user']['gambar'] : 'default.png'; ?>" alt="<?= $_SESSION['user']['gambar'] ?>">
          <p><?= $name ?></p>
        </div>

        <div class="settings">
          <p><a href="cart.php">Keranjang</a></p>
          <p><a href="http://">Admin</a></p>
          <p><a href="http://">Log out</a></p>
        </div>

      </li>
    </ul>
  </nav>
</header>

<script src="../public/javascript/nav.js"></script>
<script>

</script>