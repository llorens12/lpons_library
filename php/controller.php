<?php
include_once "styles/Template.php";
include_once "styles/CommonStyles.php";
include_once "trait/DBController.php";
include_once "objects/Anonimous.php";
include_once "objects/User.php";
include_once "objects/Librarian.php";
include_once "objects/Admin.php";
include_once "objects/Ajax.php";

session_cache_limiter('nocache,private');
session_start();

if (!isset($_SESSION['email'], $_SESSION['typeUser'], $_SESSION['name'], $_SESSION['home']) && isset($_COOKIE['email'], $_COOKIE['pwd']))
{
    if(isset($_REQUEST['method']) && $_REQUEST['method'] != "startSession")
        header('Location: controller.php?method=startSession');
}



if (isset($_REQUEST['ajax']))
{
    $ajax = new Ajax();

    switch ($_REQUEST['ajax']) {
        case "emailDisponibility":

            $email = "";
            if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];

            $ajax->email($email);
            break;

    }
}

else
{
    $user;

    if (!isset($_SESSION['email'], $_SESSION['typeUser'], $_SESSION['name'], $_SESSION['home']))
    {
        $user = new Anonimous();
    } else
    {
        switch ($_SESSION['typeUser'])
        {
            case "user":

                $user = new User($_SESSION['name'], $_SESSION['email'], $_SESSION['typeUser'], SID);
                break;

            case "librarian":

                $user = new Librarian($_SESSION['name'], $_SESSION['email'], $_SESSION['typeUser'], SID);
                break;

            case "admin":

                $user = new Admin($_SESSION['name'], $_SESSION['email'], $_SESSION['typeUser'], SID);
                break;
        }
    }


    if(isset($_REQUEST['method']))
    {
        switch ($_REQUEST['method'])
        {
            case "register":

                $user->register();
                break;

            case "startSession":

                if (isset($_REQUEST['email'], $_REQUEST['pwd']) || isset($_COOKIE['email'], $_COOKIE['pwd'])) {
                    $email = "";
                    $pwd = "";
                    $remember = false;

                    if (isset($_REQUEST['email'], $_REQUEST['pwd'])) {
                        $email = $_REQUEST['email'];
                        $pwd = md5($_REQUEST['pwd']);
                    } elseif (isset($_COOKIE['email'], $_COOKIE['pwd'])) {
                        $email = $_COOKIE['email'];
                        $pwd = $_COOKIE['pwd'];
                    }

                    if (isset($_REQUEST['rememberMe'])) {
                        $remember = true;
                    }


                    if ($user->startSession($email, $pwd, $remember)) {
                        header('Location: ' . $_SESSION['home'] . htmlspecialchars(SID));
                    } else {
                        $user->login("Incorrect E-mail or Password");
                    }
                } else {
                    header('Location: controller.php');
                }
                break;

            case "insertUser":

                unset($_REQUEST['method']);
                $user->insertUser($_REQUEST);
                break;

            case "logOut":

                $user->logOut();
                header('Location: controller.php');
                break;
        }
    }elseif(isset($_SESSION['home'])){
        header('Location: controller.php?'.$_SESSION['home']);
    }

}
echo $user;


