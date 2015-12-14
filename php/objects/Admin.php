<?php
/**
 * Class Admin, this class extends of Librarian, gets all methods and contains all options of this type user.
 */
class Admin extends Librarian
{
    /**
     * Admin constructor.
     *
     * *Description*: this object call of the parent construct and generate the web page.
     *
     * @param string $nameUser *Description*: contains the name of the user.
     * @param string $emailUser *Description*: contains the email of the user.
     * @param string $home *Description*: contains the url home of this user.
     * @param $sid  *Description*: contains the session id of the user.
     */
    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
    }


    /**
     * Override the parent method and show the form of edit user.
     * @param array $request *Description*: contains the request array.
     * @void method.
     */
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

    /**
     * Override the parent method and show the form of insert a new user.
     * @void method.
     */
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


    /**
     * Override the parent method, this insert the content of web page and close the connection of Database.
     * @return string *Description*: return all content web page.
     */
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

        if($this->getContent() == "")
            $this->showError("ERROR: This option is empty");


        return utf8_encode($this->html());
    }

}