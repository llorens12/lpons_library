<?php
    session_cache_limiter ('nocache,private');
    session_start();


    if(!isset($_SESSION['email'], $_SESSION['typeUser'], $_SESSION['name'], $_SESSION['home'])){
        $_SESSION['email']      = "anonimous@anonimous.com";
        $_SESSION['typeUser']   = "Anonimous";
        $_SESSION['name']       = "Anonimous";
        $_SESSION['home']       = "controller.php?method=showLogin";
    }

    if(isset($_REQUEST['uri']))
        $_SESSION['uri'] = $_REQUEST['uri'];

    if(isset($_COOKIE['email'], $_COOKIE['pwd']))
        header('Location: php/controller.php?method=startSession');


    header('Location: php/'.$_SESSION['home'].htmlspecialchars(SID));

?>

