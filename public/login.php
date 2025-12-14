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
    <style>
        /* ===== Palet Warna Tema Krem/Coklat ===== */
        :root {
            --bg-soft: #f8f5f2;     /* Krem Lembut */
            --bg-card: #fffaf6;     /* Putih Gading */
            --text-dark: #3e3227;   /* Coklat Tua Elegan */
            --text-info: #4c3b2f;   /* Coklat sedikit muda */
            --accent-main: #8b5e3c; /* Coklat Hangat (untuk tombol/fokus) */
            --border-light: #e5d4c3;/* Border Krem */
            --button-bg: #8b5e3c;   /* Warna tombol utama */
            --error-color: #a05050; /* Merah bata untuk error */
        }

        /* ===== GLOBAL RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", "Segoe UI", Tahoma, sans-serif;
        }

        /* ===== BODY & LAYOUT ===== */
        body {
            background: var(--bg-soft);
            color: var(--text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* ===== LOGIN FORM STYLE ===== */
        form {
            background: var(--bg-card);
            padding: 40px 30px;
            border-radius: 16px;
            border: 1px solid var(--border-light);
            box-shadow: 0 6px 14px rgba(110, 84, 60, 0.15);
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        h1 {
            text-align: center;
            color: var(--text-dark);
            margin-bottom: 10px;
            font-size: 2rem;
        }

        form > div {
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--text-info);
            font-size: 0.95rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            background-color: #fcf8f5; 
            color: var(--text-dark);
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input:focus {
            border-color: var(--accent-main);
            box-shadow: 0 0 0 3px rgba(139, 94, 60, 0.2);
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: var(--button-bg);
            color: var(--bg-card);
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }

        button[type="submit"]:hover {
            background-color: #6a4a30;
            transform: translateY(-1px);
        }

        a {
            text-align: center;
            color: var(--accent-main);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        
        a:hover {
            text-decoration: underline;
        }

        /* Error Message */
        p {
            text-align: center;
            color: var(--error-color);
            background: #ffebeb;
            border: 1px solid var(--error-color);
            padding: 10px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <h1>Login</h1>
        <div>
            <label for="">Gmail</label>
            <input type="text" name="email" id="">
        </div>
        <div>
            <label for="">Password</label>
            <input type="password" name="password" id="">
        </div>
        <div>
            <button type="submit" name="submit">Submit</button>
        </div>
        <a href="register.php">Belum punya akun?</a>
        <?php if ($error): ?>
            <p><?= $error; ?></p>
        <?php endif; ?>
    </form>
</body>

</html>