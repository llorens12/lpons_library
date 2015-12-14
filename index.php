<?php
/**
 * *Description*: This is the index web page.
 */

    session_cache_limiter ('nocache,private');
    session_start();


    /**
     * If not signed, crate Anonimous session
     */
    if(!isset($_SESSION['email'], $_SESSION['typeUser'], $_SESSION['name'], $_SESSION['home'])){
        $_SESSION['email']      = "anonimous@anonimous.com";
        $_SESSION['typeUser']   = "Anonimous";
        $_SESSION['name']       = "Anonimous";
        $_SESSION['home']       = "controller.php?method=showLogin";
    }

    /**
     * If the user selected the option remember me, start session
     */
    if(isset($_COOKIE['email'], $_COOKIE['pwd']))
        header('Location: php/controller.php?method=startSession');

    /**
     * Go to login page
     */
    header('Location: php/'.$_SESSION['home'].htmlspecialchars(SID));

?>

