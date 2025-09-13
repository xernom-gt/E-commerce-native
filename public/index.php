<?php

session_start();

if(isset($_SESSION['user'])){
  $name = $_SESSION['user']['name'];
}else{
  $name = 'tamu';
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>.:Aplikasi Penilaian:.</title>
</head>
<body>
  <div id="wrapper">
    <header></header>
    <nav>
      <ul>
        <li><a href="?">Home</a></li>
        <li><a href="?page=kelas">Kelas</a></li>
        <li><a href="?page=siswa">Siswa</a></li>
        <li><a href="?page=mapel">Mata Pelajaran</a></li>
        <li><a href="?page=nilai">Nilai</a></li>
      </ul>
    </nav>

    <h1>selamat datang, <?= htmlspecialchars($name)?> di karedoks food</h1>
    <main>
      <?php
      ?>
    </main>
  </div>
</body>
</html>