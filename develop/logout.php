<?php

    //Destroy the session just in case and if the user log out
    session_cache_limiter ('nocache,private');
    session_start();
    session_destroy();


    if(isset($_COOKIE['email'], $_COOKIE['pwd'])){
        setcookie("email", "", 0, "/");
        setcookie("pwd"  , "", 0, "/");
    };

    header('Location: ../index.php');

?>