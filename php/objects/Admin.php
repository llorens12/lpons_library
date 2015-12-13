<?php


class Admin extends Librarian{

    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
    }


    public function showEditUser($request)
    {
        $_SESSION['menu'] = "Users";


        $user = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT email, name, surname, telephone, home, typeUser
                FROM users
                WHERE email = '".$request['Email']."'
            ")
        );


        $this->setContent
        (
            stylesUser::contentForm
            (
                "Edit ".$user['name'],
                stylesAdmin::formUser($user),
                $this->sid,
                (isset($request['error'])),
                "update",
                "setUpdateUser&previousEmail=".$user['email']
            )
        );
    }


    public function showAddUser(){
        $_SESSION['menu'] = "Users";

        $user = array
        (
            "name"      => "",
            "surname"   => "",
            "email"     => "",
            "telephone" => "",
            "home"      => "NULL",
            "typeUser"  => "Admin"
        );

        $this->setContent
        (
            stylesUser::contentForm
            (
                "New User",
                stylesAdmin::formUser($user),
                $this->sid,
                (isset($_REQUEST['error'])),
                "insert",
                "setInsertUser"
            )
        );
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

            $this->showError("ERROR: This option is empty")
            :
            NULL;

        return utf8_encode($this->html());
    }
}