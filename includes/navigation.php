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
      <li><a href="#">Keranjang</a></li>
      <li><a href="#">Settings</a></li>
    </ul>
  </nav>
</header>
