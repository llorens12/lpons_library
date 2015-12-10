<?php

session_cache_limiter ('nocache,private');
session_start();



if(isset($_COOKIE['email'], $_COOKIE['pwd'])){
    echo "Existen las cookies y son: </br></br>";
    echo "email = ".$_COOKIE['email']."</br>";
    echo "email = ".$_COOKIE['pwd']."</br>";

    echo "</br></br></br></br>";

}



if(isset($_SESSION['name'], $_SESSION['email'], $_SESSION['home'])){
    echo "Existe la session: </br></br>";
    echo "email     = ".$_SESSION['email']."</br>";
    echo "typeUser  = ".$_SESSION['typeUser']."</br>";
    echo "name      = ".$_SESSION['name']."</br>";
    echo "home      = ".$_SESSION['home']."</br>";

}