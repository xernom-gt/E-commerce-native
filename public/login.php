<?php

session_start();

include_once("../config/connect.php");
include_once("../config/function.php");

$name = "";
$password = "";
$login_status = false;
$error = "";



if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = take('user');

    foreach ($result as $res) {
        if ($res['email'] == $email && $res['password'] == $password) {
            $login_status = true;
            $_SESSION['user'] = $res;
            break;
        }
    }
    if ($login_status) {
        header('Location: index.php?succes');
    } else {
        $error = "password dan email salah ";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <form action="" method="post">
        <h1>Login</h1>
        <div>
            <label for="">gmail</label>
            <input type="text" name="email" id="">
        </div>
        <div>
            <label for="">password</label>
            <input type="password" name="password" id="">
        </div>
        <div>
            <button type="submit" name="submit">submit</button>
        </div>
        <a href="register.php">belum punya akun?</a>
        <?php if ($error): ?>
            <p><?= $error; ?></p>
        <?php endif; ?>
    </form>
</body>

</html>