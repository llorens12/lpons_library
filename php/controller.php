<?php

if(!isset($_SESSION['email'],$_SESSION['typeUser'],$_SESSION['name'],$_SESSION['home'])){

    $anonimous = new Anonimous();

    if(!isset($_REQUEST['method']))
        $anonimous->login((isset($_REQUEST['error']))? $_REQUEST['error'] : "");

    else
    {
        switch($_REQUEST['method'])
        {
            case "register":

                $anonimous->register();
                break;

            case "startSession":

                unset($_REQUEST['method']);
                $anonimous->startSession($_REQUEST['email'],$_REQUEST['pwd']);
                break;

            case "inserUser":

                unset($_REQUEST['insertUser']);
                $anonimous->insertUser($_REQUEST);
                break;

            default:

                $anonimous->setContent("");
        }
    }





}