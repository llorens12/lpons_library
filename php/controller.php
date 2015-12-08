<?php
include_once "styles/Template.php";
include_once "styles/usersCommonStyles.php";
include_once "trait/DBController.php";
include_once "objects/Anonimous.php";
include_once "objects/User.php";
include_once "objects/Librarian.php";
include_once "objects/Admin.php";
include_once "objects/Ajax.php";

session_cache_limiter('nocache,private');
session_start();

if (!isset($_SESSION['email'], $_SESSION['typeUser'], $_SESSION['name'], $_SESSION['home'])) {

    header('Location: index.php?uri='.$_SERVER['REQUEST_URI']);

}


register_shutdown_function('fatalErrorHandler');


if (isset($_REQUEST['ajax'])) {
    $ajax = new Ajax();

    switch ($_REQUEST['ajax']) {
        case "emailDisponibility":

            $ajax->email($_REQUEST['email']);
            break;

        case "reserveDisponibility":

            unset($_REQUEST['ajax']);
            $ajax->reserveDisponibility($_REQUEST);
            break;
    }
}
else {

    $user  = new $_SESSION['typeUser']($_SESSION['name'], $_SESSION['email'], $_SESSION['home'], SID);

    if (isset($_REQUEST['method'])) {
        switch ($_REQUEST['method']) {

            case "showLogin":

                $user->showLogin();
                break;

            case "showRegister":

                $user->showRegister();
                break;

            case "startSession":


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

                        if(isset($_SESSION['uri'])) {
                            $uri = $_SESSION['uri'];
                            unset($_SESSION['uri']);
                            header('Location: ' . $uri.htmlspecialchars(SID));
                        }

                        header('Location: ' . $_SESSION['home'] . htmlspecialchars(SID));
                    } else {
                        $user->showLogin("Incorrect E-mail or Password");
                    }

                break;

            case "logOut":

                $user->logOut();
                header('Location: ../index.php');
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

                $user->showBook($_REQUEST['isbn']);
                break;

            case "showReserves":

                $category = "";
                $search   = "";

                if(isset($_REQUEST['category']))
                    $category = $_REQUEST['category'];

                elseif(isset($_REQUEST['search']))
                    $search = $_REQUEST['search'];

                $user->showReserves($category,$search);
                break;

            case "showError":

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
                $user->insertUser($_REQUEST);
                break;

            case "setPersonalizedReserve":

                unset($_REQUEST['insert']);
                ($user->setPersonalizedReserve($_REQUEST))? header('Location: controller.php?method=showReserves'.htmlspecialchars(SID)) : null;
                break;

            case "setDefaultReserve":

                unset($_REQUEST['insert']);
                ($user->setDefaultReserve($_REQUEST))? header('Location: controller.php?method=showReserves'.htmlspecialchars(SID)) : null;;
                break;

        }
    }
    elseif(isset($_REQUEST['delete'])){
        switch ($_REQUEST['delete']) {

            case "deleteReserve":

                unset($_REQUEST['deleteReserve']);
                ($user->deleteReserve($_REQUEST))? header('Location: controller.php?method=showReserves'.htmlspecialchars(SID)) : null;;
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
            header('Location: '.$_SESSION['home'].htmlspecialchars(SID));
        }
    }


