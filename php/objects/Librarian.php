<?php

class Librarian extends User{

    public function __construct($nameUser, $emailUser, $typeUser, $home, $currentOptionMenu, $sid)
    {
        parent::__construct($nameUser, $emailUser, $typeUser, $home, $currentOptionMenu, $sid);
    }
}