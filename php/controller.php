<?php
include_once "styles/Template.php";
include_once "styles/stylesAnonimous.php";
include_once "styles/stylesUser.php";
include_once "styles/stylesLibrarian.php";
include_once "styles/stylesAdmin.php";
include_once "trait/DBController.php";
include_once "objects/Anonimous.php";
include_once "objects/User.php";
include_once "objects/Librarian.php";
include_once "objects/Admin.php";
include_once "objects/Ajax.php";

session_cache_limiter('nocache,private');
session_start();

if (!isset($_SESSION['email'], $_SESSION['typeUser'], $_SESSION['name'], $_SESSION['home']))
    header('Location: index.php');

register_shutdown_function('fatalErrorHandler');


/**
 * Options of ajax request
 */
if (isset($_REQUEST['ajax']))
{
    $ajax = new Ajax();
    $option = $_REQUEST['ajax'];
    unset($_REQUEST['ajax']);

    switch ($option) {

        case "emailDisponibility":

            $ajax->email($_REQUEST['email']);
            break;

        case "reserveDisponibility":

            $ajax->reserveDisponibility($_REQUEST);
            break;

        /**
         * Return if isset isbn
         */
        case "book":

            $ajax->book($_REQUEST);
            break;
    }
}
else {

    /**
     * Create the object based on the type of user
     */
    $user  = new $_SESSION['typeUser']($_SESSION['name'], $_SESSION['email'], $_SESSION['home'], SID);


    /**
     * View options
     */
    if (isset($_REQUEST['method']))
    {

        $method = $_REQUEST['method'];
        unset($_REQUEST['method']);

        $category = "";
        $search   = "";


        /**
         * Gets the value of a filter or search
         */
        if(isset($_REQUEST['category']))
            $category = $_REQUEST['category'];

        elseif(isset($_REQUEST['btnCategory']))
            $category = $_REQUEST['btnCategory'];

        if(isset($_REQUEST['search']))
            $search = $_REQUEST['search'];


        /**
         * Check option
         */
        switch ($method)
        {

            /**
             * Options of Anonimous type
             */
            case "showLogin":

                $user->showLogin();
                break;

            case "showRegister":

                $user->showRegister();
                break;

            case "startSession":

                if ($user->startSession($_REQUEST))
                    header('Location: ' . $_SESSION['home'] . htmlspecialchars(SID));

                else
                    $user->showLogin("Incorrect E-mail or Password");
                break;





            /**
             * Options of User, Librarian and Admin type
             */
            case "logOut":

                $user->logOut();
                header('Location: ../index.php');
                break;

            case "showBook":

                $user->showBook($_REQUEST['isbn']);
                break;

            case "showBooks":

                $user->showBooks($category,$search);
                break;

            case "showReserves":

                $user->showReserves($category,$search);
                break;

            case "showMyProfile":

                $user->showMyProfile($_REQUEST);
                break;

            case "showEditReserve":

                $user->showEditReserve($_REQUEST);
                break;





            /**
             * Options of Librarian and Admin type
             */
            case "showTableUsers":

                $user->showTableUsers($category,$search);
                break;

            case "showTableDefaulters":

                $user->showTableDefaulters($category,$search);
                break;

            case "showTableBooks":

                $user->showTableBooks($category,$search);
                break;

            case "showTableCopies":

                $user->showTableCopies($category,$search);
                break;



            case "showAdministrateUsers":

                $user->showAdministrateUsers($category,$search);
                break;

            case "showAdministrateBooks":

                $user->showAdministrateBooks($category,$search);
                break;

            case "showAdministrateCopies":

                $user->showAdministrateCopies($category,$search);
                break;

            case "showAdministrateUsersReserves":

                $user->showAdministrateUsersReserves($category,$search);
                break;



            case "showAddUser":

                $user->showAddUser();
                break;

            case "showAddBook":

                $user->showAddBook();
                break;

            case "showAddCopy":

                $user->showAddCopy($_REQUEST);
                break;

            case "showAddUserReserve":

                $user->showAddUserReserve($_REQUEST);
                break;



            case "showEditUser":

                $user->showEditUser($_REQUEST);
                break;

            case "showEditBook":
                $user->showEditBook($_REQUEST);
                break;

            case "showEditCopy":

                $user->showEditCopy($_REQUEST);
                break;

            case "showEditUserReserve":

                $user->showEditUserReserve($_REQUEST);
                break;





            case "showError":

                (isset($_REQUEST['error'])) ?

                    $user->showError($_REQUEST['error'])
                    :
                    $user->showError();

                break;

        }
    }

    /**
     * Insert options
     */
    elseif(isset($_REQUEST['insert']))
    {
        $insert = $_REQUEST['insert'];
        unset($_REQUEST['insert']);

        /**
         * Check option
         */
        switch ($insert)
        {

            /**
             * Options of Anonimous type
             */
            case "insertUser":

                (!$user->insertUser($_REQUEST))?
                    $user->showRegister("Register error")
                    :
                    header('Location: controller.php?method=showLogin'.htmlspecialchars(SID));
                break;



            /**
             * Options of User, Librarian and Admin type
             */
            case "setInsertDefaultReserve":

                if($user->setInsertDefaultReserve($_REQUEST))
                    header('Location: controller.php?method=showReserves'.htmlspecialchars(SID));

                else
                    setError();
                break;

            case "setInsertPersonalizedReserve":

                if($user->setInsertPersonalizedReserve($_REQUEST))
                    header('Location: controller.php?method=showReserves'.htmlspecialchars(SID));

                else
                    setError();
                break;



            /**
             * Options of Librarian and Admin type
             */
            case "setInsertUser":

                if($user->setInsertUser($_REQUEST))
                    header('Location: controller.php?method=showAdministrateUsers&search='.$_REQUEST['email'].htmlspecialchars(SID));

                else
                    setError();
                break;

            case "setInsertBook":

                if($user->setInsertBook($_REQUEST, $_FILES))
                    header('Location: controller.php?method=showAdministrateBooks&search='.$_REQUEST['isbn'].htmlspecialchars(SID));

                else
                    setError();
                break;

            case "setInsertCopy":

                if($user->setInsertCopy($_REQUEST))
                    header('Location: controller.php?method=showAdministrateCopies&search='.$_REQUEST['book'].htmlspecialchars(SID));

                else
                    setError();
                break;

            case "setInsertUserPersonalizedReserve":

                if($user->setInsertUserPersonalizedReserve($_REQUEST))
                    header('Location: controller.php?method=showAdministrateUsersReserves&search='.$_REQUEST['user'].htmlspecialchars(SID));

                else
                    setError();
                break;

            case "setInsertUserDefaultReserve":

                if($user->setInsertUserDefaultReserve($_REQUEST))
                    header('Location: controller.php?method=showAdministrateUsersReserves&search='.$_REQUEST['user'].htmlspecialchars(SID));

                else
                    setError();
                break;

        }
    }

    /**
     * Update options
     */
    elseif(isset($_REQUEST['update']))
    {
        $update = $_REQUEST['update'];
        unset($_REQUEST['update']);

        /**
         * Check option
         */
        switch($update)
        {

            /**
             * Options of User, Librarian and Admin type
             */
            case "setUpdateReserve":

                if($user->setUpdateReserve($_REQUEST))
                    header('Location: controller.php?method=showReserves&search='.$_REQUEST['Start']. htmlspecialchars(SID));

                else
                    setError();
                break;

            case "setUpdateMyProfile":

                if($user->setUpdateMyProfile($_REQUEST))
                    header('Location: controller.php?method=showMyProfile'. htmlspecialchars(SID));
                else
                    setError();
                break;



            /**
             * Options of Librarian and Admin type
             */
            case "setUpdateUser":

                if($user->setUpdateUser($_REQUEST))
                    header('Location: controller.php?method=showAdministrateUsers&search='.$_REQUEST['email']. htmlspecialchars(SID));
                else
                    setError();
                break;

            case "setUpdateBook":

                if($user->setUpdateBook($_REQUEST, $_FILES))
                    header('Location: controller.php?method=showTableBooks&search='.$_REQUEST['isbn']. htmlspecialchars(SID));

                else
                    setError();
                break;

            case "setUpdateCopy":

                if($user->setUpdateCopy($_REQUEST))
                    header('Location: controller.php?method=showTableCopies&search='.$_REQUEST['IDCopy']. htmlspecialchars(SID));
                else
                    setError();
                break;

            case "setUpdateUserReserve":

                if($user->setUpdateUserReserve($_REQUEST))
                    header('Location: controller.php?method=showAdministrateUsersReserves&search='.$_REQUEST['user']. htmlspecialchars(SID));
                else
                    setError();
                break;

        }
    }

    /**
     * Delete options
     */
    elseif(isset($_REQUEST['delete']))
    {
        $delete = $_REQUEST['delete'];
        unset($_REQUEST['delete']);

        /**
         * Check option
         */
        switch ($delete)
        {

            /**
             * Options of User, Librarian and Admin type
             */
            case "setDeleteReserve":

                if($user->setDeleteReserve($_REQUEST))
                    header('Location: controller.php?method=showReserves'.htmlspecialchars(SID));

                else
                    setError();
                break;



            /**
             * Options of Librarian and Admin type
             */
            case "setDeleteUser":

                if($user->setDeleteUser($_REQUEST['Email']))
                    header('Location: controller.php?method=showAdministrateUsers'.htmlspecialchars(SID));

                else
                    setError();
                break;

            case "setDeleteBook":

                if($user->setDeleteBook($_REQUEST['ISBN']))
                    header('Location: controller.php?method=showAdministrateBooks'.htmlspecialchars(SID));

                else
                    setError();
                break;

            case "setDeleteCopy":

                if($user->setDeleteCopy($_REQUEST))
                    header('Location: controller.php?method=showAdministrateCopies'.htmlspecialchars(SID));

                else
                    setError();
                break;

            case "setDeleteUserReserve":

                if($user->setDeleteUserReserve($_REQUEST))
                    header('Location: controller.php?method=showAdministrateUsersReserves'.htmlspecialchars(SID));

                else
                    setError();
                break;

        }
    }

    /**
     * Print web page
     */
    echo $user;
}


/**
 * Function redirect in error case
 */
    function setError()
    {
        if(strpos($_SERVER['HTTP_REFERER'], "&error="))
            header('Location: '.$_SERVER['HTTP_REFERER'].htmlspecialchars(SID));

        else
            header('Location: '.$_SERVER['HTTP_REFERER'].'&error=""'.htmlspecialchars(SID));
    }


/**
 * Handling fatal error
 */
    function fatalErrorHandler()
    {
        $error = error_get_last();
        if(($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR))
        {
            header('Location: '.$_SESSION['home'].htmlspecialchars(SID));
        }
    }


