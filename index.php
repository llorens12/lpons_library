<?php
    session_cache_limiter ('nocache,private');
    session_start();


    if(isset($_SESSION['user'],$_SESSION['typeUser'],$_SESSION['name'],$_SESSION['home']))
        header('Location: php/'.$_SESSION['home'].htmlspecialchars(SID));


    if(isset($_COOKIE['email'], $_COOKIE['pwd']))
        header('Location: php/controller.php?method=startSession');


    header('Location: php/controller.php');

?>

