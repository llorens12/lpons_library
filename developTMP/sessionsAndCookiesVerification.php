<?php

session_cache_limiter ('nocache,private');
session_start();



if(isset($_COOKIE['email'], $_COOKIE['pwd'])){
    echo "Existen las cookies y son: </br></br>";
    echo "email = ".$_COOKIE['email']."</br>";
    echo "email = ".$_COOKIE['pwd']."</br>";

}

echo "</br></br></br></br>";

if(isset($_SESSION['user'],$_SESSION['typeUser'],$_SESSION['name'],$_SESSION['home'])){
    echo "Existe la session: </br></br>";
    echo "user = ".$_SESSION['user']."</br>";
    echo "typeUser = ".$_SESSION['typeUser']."</br>";
    echo "name = ".$_SESSION['name']."</br>";
    echo "home = ".$_SESSION['home']."</br>";

}