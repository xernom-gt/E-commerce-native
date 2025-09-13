<?php

define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASSWORD','');
define('DB_NAME','e_commerce');

$conn = mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME) or die('gagal koneksi')

?>