<?php
include_once "php/styles/Template.php";
include_once "php/styles/CommonStyles.php";

    session_cache_limiter ('nocache,private');
    session_start();


    if(isset($_SESSION['user'],$_SESSION['typeUser'],$_SESSION['name'],$_SESSION['home']))
        header('Location: php/objects/'.$_SESSION['home'].'?'.htmlspecialchars(SID));


    if(isset($_COOKIE['email'], $_COOKIE['pwd']))
        header('Location: php/startSession.php');


    $p = new Template();

    if(!isset($_REQUEST['register']))
    {
        $p->setContent(Forms::login((isset($_REQUEST['error']))? $_REQUEST['error'] : ""));
        $p->showLogin();
    }
    else
    {
        $p->setContent(Forms::register());
        $p->showRegister();
    }



?>

