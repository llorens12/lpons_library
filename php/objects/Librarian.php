<?php

class Librarian extends User{

    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
    }

    public function showAdministrateUsers($category = "", $search = ""){
        $_SESSION['menu'] = "Users";

        $filterData = array
        (
            "name"          => "Name",
            "surname"       => "Surname",
            "email"         => "Email",
            "telephone"     => "Telephone",
            "registered"    => "Registered",
            "home"          => "Home"
        );


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM users
                WHERE email != '".$this->emailUser."'
            ";


        if ($category != "" && $category != "*") {
            $sentence .= " ORDER BY " . $category;
        }

        elseif($search != "")
        {
            $sentence .=  $this->getSearchLikeFormat($filterData, $search);
        }


        $this->setContent(
            stylesUser::filterMenu
            (
                "Order by",
                "Default",
                $filterData,
                "Search...",
                "showAdministrateUsers",
                $this->sid
            ).

            stylesUser::table
            (
                $this->select($sentence),
                true,
                true,
                false,
                "User",
                ["Email"],
                false,
                0,
                $this->sid
            )
        );
    }

    public function showDefaulters($category = "", $search = ""){
        $_SESSION['menu'] = "Users";

        $filterData = array
        (
            "name"          => "Name",
            "surname"       => "Surname",
            "email"         => "Email",
            "telephone"     => "Telephone",
            "registered"    => "Registered"
        );


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM reserves JOIN users on user = email
                WHERE
                    date_finish < '".date('Y-m-d')."'
                AND
                    sent is not null
                AND
                    received is null
            ";


        if ($category != "" && $category != "*") {
            $sentence .= " ORDER BY " . $category;
        }

        elseif($search != "")
        {
            $sentence .=  $this->getSearchLikeFormat($filterData, $search);
        }


        $this->setContent(
            stylesUser::filterMenu
            (
                "Order by",
                "Default",
                $filterData,
                "Search...",
                "showDefaulters",
                $this->sid
            ).

            stylesUser::table
            (
                $this->select($sentence)
            )
        );
    }

    public function showAddUser()
    {
        $_SESSION['menu'] = "Users";

        $user = array
        (
            "name"      => "",
            "surname"   => "",
            "email"     => "",
            "telephone" => "",
            "home"      => "NULL",
            "typeUser"  => "User"
        );

        $this->setContent
        (
            stylesUser::contentAdministrateUser
            (
                $user,
                $this->sid,
                "",
                "insert",
                "setInsertUser"
            )
        );
    }

    public function showTableBooks(){}

    public function showAdministrateBooks(){}

    public function showAddBook(){}

    public function showAdministrateReserves(){}

    public function showAddReserves(){}


    public function setInsertUser($request){

        unset($request['typeUser'], $request['registered']);

        $request['registered'] = date('Y-m-d');
        $request['typeUser'] = "User";
        $request['pwd'] = md5($request['pwd']);

        return $this->insert("users",$request);
    }

    public function showEditUser($error){
        $_SESSION['menu'] = "Users";

        $user = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT email, name, surname, telephone, home, typeUser
                FROM users
                WHERE email != '{$this->emailUser}' AND typeUser = 'User'
            ")
        );

        $this->setContent
        (
            stylesUser::contentAdministrateUser
            (
                $user,
                $this->sid,
                $error,
                "update",
                "setUpdateUser&previousEmail=".$user['email']
            )
        );
    }

    public function setUpdateUser($request){

        unset($request['typeUser'], $request['registered']);


        if(isset($request['pwd']) && ($request['pwd'] == "" || $request['pwd'] == " ")){
            unset($request['pwd']);
        }
        else
            $request['pwd'] = md5($request['pwd']);


        $where = "email = '".$request['previousEmail']."'";
        unset($request['previousEmail']);


        return ($this->update("users",$request,$where));
    }

    public function setDeleteUser($email){

        if($email != $this->emailUser)
           $this->delete("users","email = '".$email."'");

    }




    protected function getSearchLikeFormat($filterData, $search){

        $sentence =  " AND ( ";

        foreach($filterData as $key =>$value){
            $sentence .= " LOWER(".$key.")   LIKE LOWER('%" . $search . "%') OR";
        }

        $sentence = trim($sentence, "OR");

        $sentence .= ")";

        return $sentence;
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