<?php

if (isset($_REQUEST['email'], $_REQUEST['pwd']) || isset($_COOKIE['email'], $_COOKIE['pwd'])) {

    $email = "";
    $pwd   = "";

    if(isset($_REQUEST['email'], $_REQUEST['pwd']))
    {
        $email = $_REQUEST['email'];
        $pwd   = $_REQUEST['pwd'];
    }
    elseif(isset($_COOKIE['email'], $_COOKIE['pwd']))
    {
        $email = $_COOKIE['email'];
        $pwd   = $_COOKIE['pwd'];
    }



    $connection = new mysqli("localhost", "root", "", "lpons_library");
    $user = $connection->query("SELECT * FROM users WHERE email = '{$email}' AND pwd = '{$pwd}';")->fetch_assoc();
    $connection->close();

    if (count($user) != 0) {

        session_cache_limiter('nocache,private');
        session_start();
        $_SESSION['typeUser'] = $user['typeUser'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['user'] = $user['email'];


        switch ($user['typeUser']) {
            case "user":
                $_SESSION['home'] = "Books.php";
                break;

            case "librarian":
                $_SESSION['home'] = "User.php";
                break;

            case "admin":
                $_SESSION['home'] = "User.php";
                break;
        }

        if (isset($_REQUEST['rememberMe']) && $_REQUEST['rememberMe'] == "remember") {
            setcookie("email", $user['email'], time() + 7776000, "/");
            setcookie("pwd"  , $user['pwd']  , time() + 7776000, "/");
        }


        //The htmlspecialchars() is used to prevent attacks related XSS
        header('Location: objects/' . $_SESSION['home'] . '?' . htmlspecialchars(SID));

    } else  header('Location: ../index.php?error=Incorrect E-mail or Password ');

} else  header("Location: ../index.php?error=You haven't insert all data");
?>