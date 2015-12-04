<?php


class Admin extends Librarian{

    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
    }



    public function __toString()
    {
        $this->setContentMenu($this->myContentMenu());
        if($this->getContent() == "")
            $this->setContent("<h1 style='text-align: center'>ERROR: action not found</h1>");
        return utf8_encode($this->html());
    }
}