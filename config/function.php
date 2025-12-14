<?php

use Dom\Mysql;

include_once('connect.php');

//mengambil semua data
function take($table)
{
    global $conn;

    $result = mysqli_query($conn, "SELECT * FROM $table");

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    };
    return $rows;
};

//mengambil semua table barang
function search($table, $field, $keyword)
{
    global $conn;

    $result = mysqli_query($conn, "SELECT * FROM $table WHERE $field LIKE '%$keyword%'");

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    };
    return $rows;
}


//membuat id otomatis 

function generateID($table, $field, $prefix)
{
    global $conn;

    //ambil id terbesar di table 
    $res = mysqli_query($conn, "SELECT MAX($field) as max_id FROM $table");
    $row = mysqli_fetch_assoc($res);
    $maxId = $row['max_id'];

    if ($maxId) {
        $num = (int)substr($maxId, strlen($prefix));
        $num++;
    }
    //apabila row nya tidak ada
    else {
        $num = 1;
    };


    return $prefix . str_pad($num, 3, "0", STR_PAD_LEFT);
}

//membuat user baru ke database

function user($name, $email, $password)
{
    global $conn;

    $id = generateID("user", "id", "mk");

    $res = mysqli_query($conn, "INSERT INTO `user` (id,name,email,password) VALUES ('$id','$name','$email','$password')");

    if (!$res) {
        return "Query gagal:" . mysqli_error($conn);
    } else {
        return "Berhasil membuat akun dengan id $id";
    }
}

function login($email, $password)
{
    global $conn;

    // Query langsung ke database, filter email & password
    $query = mysqli_query($conn, "SELECT * FROM user WHERE email='$email' AND password='$password'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        return $user['name']; // return nama user kalau login berhasil
    } else {
        return false; // login gagal
    }
}
//

function details($table, $id)
{
    global $conn;

    $id = (int)$id;

    $result = mysqli_query($conn, "SELECT * FROM $table WHERE id_barang=$id");

    return mysqli_fetch_assoc($result);
}


