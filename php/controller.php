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

    header('Location: index.php');

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

    if (isset($_REQUEST['method']))
    {
        $method = $_REQUEST['method'];
        unset($_REQUEST['method']);

        switch ($method)
        {

            case "showLogin":

                $user->showLogin();
                break;

            case "showRegister":

                $user->showRegister();
                break;

            case "startSession":

                if ($user->startSession($_REQUEST)) {

                    header('Location: ' . $_SESSION['home'] . htmlspecialchars(SID));
                }
                else
                {
                    $user->showLogin("Incorrect E-mail or Password");
                }
                break;

            case "logOut":

                $user->logOut();
                header('Location: ../index.php');
                break;






            case "showBook":

                $user->showBook($_REQUEST['isbn']);
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

            case "showReserves":

                $category = "";
                $search   = "";

                if(isset($_REQUEST['category']))
                    $category = $_REQUEST['category'];

                if(isset($_REQUEST['search']))
                    $search = $_REQUEST['search'];

                $user->showReserves($category,$search);
                break;

            case "showMyProfile":

                $user->showMyProfile((isset($_REQUEST['error']))? "error" : "");
                break;



            case "showEditReserve":

                $user->showEditReserve($_REQUEST);
                break;





            case "showTableUsers":

            case "showTableDefaulters":

                $category = "";
                $search   = "";

                if(isset($_REQUEST['category']))
                    $category = $_REQUEST['category'];

                if(isset($_REQUEST['search']))
                    $search = $_REQUEST['search'];

                $user->showTableDefaulters($category,$search);
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

            case "showTableUsersReserves":



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

            case "showAdministrateBooks":

                $category = "";
                $search   = "";

                if(isset($_REQUEST['category']))
                    $category = $_REQUEST['category'];

                if(isset($_REQUEST['search'])) {
                    $search = $_REQUEST['search'];
                }

                $user->showAdministrateBooks($category,$search);
                break;

            case "showAdministrateCopies":

            case "showAdministrateUsersReserves":



            case "showAddUser":

                $user->showAddUser();
                break;

            case "showAddBook":

                $user->showAddBook();
                break;

            case "showAddCopy":

                $user->showAddCopy($_REQUEST);
                break;

            case "showAddReserves":



            case "showEditUser":

                $user->showEditUser($_REQUEST);
                break;

            case "showEditBook":
                $user->showEditBook($_REQUEST);
                break;

            case "showEditCopy":

            case "showEditUserReserves":





            case "showError":

                (isset($_REQUEST['error'])) ?

                    $user->showError($_REQUEST['error'])
                    :
                    $user->showError();

                break;

        }
    }
    elseif(isset($_REQUEST['insert']))
    {
        $insert = $_REQUEST['insert'];
        unset($_REQUEST['insert']);

        switch ($insert)
        {

            case "insertUser":

                (!$user->insertUser($_REQUEST))?
                    $user->showRegister("Register error")
                    :
                    header('Location: controller.php?method=showLogin'.htmlspecialchars(SID));
                break;



            case "setInsertDefaultReserve":

                $user->setInsertDefaultReserve($_REQUEST);
                header('Location: controller.php?method=showReserves'.htmlspecialchars(SID))
                break;

            case "setInsertPersonalizedReserve":

                $user->setInsertPersonalizedReserve($_REQUEST);
                header('Location: controller.php?method=showReserves'.htmlspecialchars(SID))
                break;




            case "setInsertUser":

                $user->setInsertUser($_REQUEST);
                header('Location: controller.php?method=showAdministrateUsers&search='.$_REQUEST['email'].htmlspecialchars(SID));
                break;

            case "setInsertBook":

                $user->setInsertBook($_REQUEST);
                header('Location: controller.php?method=showAdministrateBooks&search='.$_REQUEST['isbn'].htmlspecialchars(SID));
                break;

            case "setInsertCopy":

                $user->setInsertCopy($_REQUEST);
                header('Location: controller.php?method=showAdministrateCopies&search='.$_REQUEST['isbn'].htmlspecialchars(SID));
                break;

            case "setInsertUserReserve":



        }
    }
    elseif(isset($_REQUEST['update']))
    {
        $update = $_REQUEST['update'];
        unset($_REQUEST['update']);

        switch($update)
        {

            case "setUpdateReserve":

                if($user->setUpdateReserve($_REQUEST))
                {
                    header('Location: controller.php?method=showReserves&search='.$_REQUEST['isbn']. htmlspecialchars(SID));
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
                    header('Location: controller.php?method=showTableUsers&search='.$_REQUEST['Email']. htmlspecialchars(SID));

                }
                else
                {
                    if(strpos($_SERVER['HTTP_REFERER'], "&error="))
                        header('Location: '.$_SERVER['HTTP_REFERER'].htmlspecialchars(SID));

                    else
                        header('Location: '.$_SERVER['HTTP_REFERER'].'&error=""'.htmlspecialchars(SID));
                }
                break;

            case "setUpdateBook":

            case "setUpdateCopy":

            case "setUpdateUserReserve":


        }
    }
    elseif(isset($_REQUEST['delete']))
    {
        $delete = $_REQUEST['delete'];
        unset($_REQUEST['delete']);

        switch ($delete) {

            case "setDeleteReserve":

                $user->setDeleteReserve($_REQUEST);
                header('Location: controller.php?method=showReserves'.htmlspecialchars(SID));
                break;



            case "setDeleteUser":

                $user->setDeleteUser($_REQUEST['Email']);
                header('Location: controller.php?method=showAdministrateUsers'.htmlspecialchars(SID));
                break;

            case "setDeleteBook":

                $user->setDeleteBook($_REQUEST['ISBN']);
                header('Location: controller.php?method=showAdministrateBooks'.htmlspecialchars(SID));
                break;

            case "setDeleteCopy":

                $user->setDeleteCopy($_REQUEST['id']);
                header('Location: controller.php?method=showAdministrateCopies'.htmlspecialchars(SID));
                break;

            case "setDeleteUserReserve":

                $user->setDeleteUserReserve($_REQUEST);
                header('Location: controller.php?method=showAdministrateUsersReserves'.htmlspecialchars(SID));
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


