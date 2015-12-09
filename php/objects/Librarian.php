<?php

class Librarian extends User{

    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
    }



    public function __toString()
    {
        $this->close();

        $this->setMenuTop
        (
            stylesLibrarian::menuTop
            (
                $this->nameUser,
                $this->sid
            )
        );

        $this->setMenuContent
        (
            stylesLibrarian::menuContent
            (
                $this->sid
            )
        );

        ($this->getContent() == "")?

            $this->showError("ERROR: action not found")
            :
            NULL;

        return utf8_encode($this->html());
    }
}