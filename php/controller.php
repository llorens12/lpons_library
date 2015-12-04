<?php
include_once "styles/Template.php";
include_once "styles/CommonStyles.php";
include_once "trait/DBController.php";
include_once "objects/Anonimous.php";
include_once "objects/User.php";
include_once "objects/Librarian.php";
include_once "objects/Admin.php";
include_once "objects/Ajax.php";

//register_shutdown_function('fatalErrorHandler');

session_cache_limiter('nocache,private');
session_start();

if (!isset($_SESSION['email'], $_SESSION['typeUser'], $_SESSION['name'], $_SESSION['home']) && isset($_COOKIE['email'], $_COOKIE['pwd'])) {
    if (isset($_REQUEST['method']) && $_REQUEST['method'] != "startSession")
        header('Location: controller.php?method=startSession');
}


if (isset($_REQUEST['ajax'])) {
    $ajax = new Ajax();

    switch ($_REQUEST['ajax']) {
        case "emailDisponibility":

            $email = "";
            if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];

            $ajax->email($email);
            break;

    }
}
else {
    $user;

    if (!isset($_SESSION['email'], $_SESSION['typeUser'], $_SESSION['name'], $_SESSION['home'])) {
        $user = new Anonimous();
    } else {
        switch ($_SESSION['typeUser']) {
            case "user":

                $user = new User($_SESSION['name'], $_SESSION['email'], $_SESSION['home'], SID);
                break;

            case "librarian":

                $user = new Librarian($_SESSION['name'], $_SESSION['email'], $_SESSION['home'], SID);
                break;

            case "admin":

                $user = new Admin($_SESSION['name'], $_SESSION['email'], $_SESSION['home'], SID);
                break;
        }
    }

    if (isset($_REQUEST['method'])) {
        switch ($_REQUEST['method']) {

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
                }
                break;

            case "logOut":

                $user->logOut();
                header('Location: controller.php');
                break;

            case "showUsers":

                $user->showUsers();
                break;

            case "showBooks":

                $category = "";
                $search   = "";

                if(isset($_REQUEST['category']))
                    $category = $_REQUEST['category'];

                elseif(isset($_REQUEST['search']))
                    $search = $_REQUEST['search'];


                $user->showBooks($category,$search);
                break;

            case "showBook":

                (isset($_REQUEST['isbn']))? $user->showBook($_REQUEST['isbn']) : NULL;
                break;

            case "showReserves":

                $user->showReserves();
                break;

            case "error":

                (isset($_REQUEST['error'])) ?

                    $user->showError($_REQUEST['error'])
                    :
                    $user->showError();

                break;

        }
    }
    elseif(isset($_REQUEST['insert'])){

        switch ($_REQUEST['insert']){

            case "user":

                unset($_REQUEST['insert']);
                (isset($_REQUEST['email']))?  $user->insertUser($_REQUEST) : null;
                break;
        }
    }
    echo $user;
}



/**
 *
 * Handling fatal error
 *
 */

    function fatalErrorHandler()
    {
# Getting last error
        $error = error_get_last();

# Checking if last error is a fatal error
        if(($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR))
        {
# Here we handle the error, displaying HTML, logging, ...
            header('Location: controller.php?method=error&error=Permission denied'.htmlspecialchars(SID));
        }
    }


