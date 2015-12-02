<?php


class Admin extends Librarian{

    public function __construct($nameUser, $emailUser, $typeUser, $sid)
    {
        parent::__construct($nameUser, $emailUser, $typeUser, $sid);
    }



    public function __toString()
    {
        $this->setContentMenu($this->myContentMenu());
        return utf8_encode($this->html());
    }
}