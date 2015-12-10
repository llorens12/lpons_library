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


//register_shutdown_function('fatalErrorHandler');


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

            case "showEditReserve":

                $user->showEditReserve($_REQUEST);
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

                if(isset($_REQUEST['btnCategory']))
                    $category = $_REQUEST['btnCategory'];

                elseif(isset($_REQUEST['category']))
                    $category = $_REQUEST['category'];

                if(isset($_REQUEST['search']))
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

                if(isset($_REQUEST['search']))
                    $search = $_REQUEST['search'];

                $user->showReserves($category,$search);
                break;

            case "showDefaulters":

                $category = "";
                $search   = "";

                if(isset($_REQUEST['category']))
                    $category = $_REQUEST['category'];

                if(isset($_REQUEST['search']))
                    $search = $_REQUEST['search'];

                $user->showDefaulters($category,$search);
                break;

            case "showTableBooks":

                $category = "";
                $search   = "";

                if(isset($_REQUEST['category']))
                    $category = $_REQUEST['category'];

                if(isset($_REQUEST['search']))
                    $search = $_REQUEST['search'];

                $user->showTableBooks($category,$search);
                break;


            case "showTableCopies":

                $category = "";
                $search   = "";

                if(isset($_REQUEST['category']))
                    $category = $_REQUEST['category'];

                if(isset($_REQUEST['search']))
                    $search = $_REQUEST['search'];

                $user->showTableCopies($category,$search);
                break;

            case "showAdministrateUsers":

                $category = "";
                $search   = "";

                if(isset($_REQUEST['category']))
                    $category = $_REQUEST['category'];

                if(isset($_REQUEST['search'])) {
                    $search = $_REQUEST['search'];
                }

                $user->showAdministrateUsers($category,$search);
                break;

            case "showMyProfile":

                $user->showMyProfile((isset($_REQUEST['error']))? "error" : "");
                break;

            case "showEditUser":

                $user->showEditUser((isset($_REQUEST['error']))? "error" : "");
                break;

            case "showAddUser":

                $user->showAddUser();
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

            case "insertUser":

                unset($_REQUEST['insert']);
                (!$user->insertUser($_REQUEST))?
                    $user->showRegister("Register error")
                    :
                    header('Location: controller.php?method=showLogin'.htmlspecialchars(SID));
                break;

            case "setInsertPersonalizedReserve":

                unset($_REQUEST['insert']);
                ($user->setInsertPersonalizedReserve($_REQUEST))?
                    header('Location: controller.php?method=showReserves'.htmlspecialchars(SID))
                    :
                    null;
                break;

            case "setInsertDefaultReserve":

                unset($_REQUEST['insert']);
                ($user->setInsertDefaultReserve($_REQUEST))?
                    header('Location: controller.php?method=showReserves'.htmlspecialchars(SID))
                    :
                    null;
                break;

            case "setInsertUser":

                unset($_REQUEST['insert']);
                $user->setInsertUser($_REQUEST);
                header('Location: controller.php?method=showAdministrateUsers'.htmlspecialchars(SID));
                break;

        }
    }
    elseif(isset($_REQUEST['delete'])){
        switch ($_REQUEST['delete']) {

            case "setDeleteReserve":

                unset($_REQUEST['delete']);
                ($user->setDeleteReserve($_REQUEST))?
                    header('Location: controller.php?method=showReserves'.htmlspecialchars(SID))
                    :
                    null;
                break;

            case "setDeleteUser":

                $user->setDeleteUser($_REQUEST['Email']);
                header('Location: '.$_SERVER['HTTP_REFERER'].htmlspecialchars(SID));
                break;

        }
    }
    elseif(isset($_REQUEST['update'])){
        switch($_REQUEST['update']){

            case "setUpdateReserve":

                unset($_REQUEST['update']);
                if($user->setUpdateReserve($_REQUEST))
                {
                    header('Location: controller.php?method=showReserves'. htmlspecialchars(SID));
                }
                else
                {
                    if(strpos($_SERVER['HTTP_REFERER'], "&error="))
                        header('Location: '.$_SERVER['HTTP_REFERER'].htmlspecialchars(SID));

                    else
                        header('Location: '.$_SERVER['HTTP_REFERER'].'&error=""'.htmlspecialchars(SID));
                }
                break;

            case "setUpdateMyProfile":

                unset($_REQUEST['update']);
                if($user->setUpdateMyProfile($_REQUEST))
                {
                    header('Location: controller.php?method=showMyProfile'. htmlspecialchars(SID));

                }
                else
                {
                    if(strpos($_SERVER['HTTP_REFERER'], "&error="))
                        header('Location: '.$_SERVER['HTTP_REFERER'].htmlspecialchars(SID));

                    else
                        header('Location: '.$_SERVER['HTTP_REFERER'].'&error=""'.htmlspecialchars(SID));
                }
                break;

            case "setUpdateUser":

                unset($_REQUEST['update']);
                if($user->setUpdateUser($_REQUEST))
                {
                    header('Location: controller.php?method=showAdministrateUsers'. htmlspecialchars(SID));

                }
                else
                {
                    if(strpos($_SERVER['HTTP_REFERER'], "&error="))
                        header('Location: '.$_SERVER['HTTP_REFERER'].htmlspecialchars(SID));

                    else
                        header('Location: '.$_SERVER['HTTP_REFERER'].'&error=""'.htmlspecialchars(SID));
                }
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
        $error = error_get_last();
        if(($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR))
        {
            header('Location: '.$_SESSION['home'].htmlspecialchars(SID));
        }
    }


