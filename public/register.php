<?php

include_once("../config/connect.php");
include_once("../config/function.php");

$name = "";
$email = "";
$password = "";
$cf_password = "";
$error = "";


if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cf_password = $_POST['cf_password'];

    if (empty($name) || empty($email) || empty($password) || empty($cf_password)) {
        $error = "harap isi field di atas!";
    } else {
        if ($password == $cf_password) {
            $result = user($name, $email, $password);

            if ($result) {
                header("Location: login.php?succes");
                exit();
            } else {
                die("Query gagal: " . mysqli_error($conn));
            };
        } else {
            $error = "harap confirmasi password ada!";
        }
    };
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
</head>

<body>

    <form action="" method="post">
        <div>
            <h2>Register</h2>
            <div>
                <label for="">nama</label>
                <input type="text" name="name" value="<?= $name ?>" id="">
            </div>
            <div>
                <label for="">email</label>
                <input type="email" name="email" value="<?= $email ?>" id="">
            </div>
            <div>
                <label for="">password</label>
                <input type="password" name="password" id="">
            </div>
            <div>
                <label for="">confirm password</label>
                <input type="password" name="cf_password" id="">
            </div>
            <div>
                <button name="submit">submit</button>
            </div>
        </div>
        <a href="./login.php">sudah punya akun?</a>

        <?php if ($error): ?>
            <p><?= $error ?></p>
        <?php endif; ?>
    </form>
</body>

</html>