<?php
include_once "../styles/Template.php";


/* Tot el que un usuari no autentificat pugui realitzar anira aqui */

class Anonimous extends Template{

    public function __construct()
    {
        parent::__construct();

    }
}