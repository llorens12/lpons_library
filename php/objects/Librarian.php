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
                "Users",
                ["Email"],
                false,
                0,
                $this->sid
            )
        );
    }

    public function showDefaulters(){}

    public function showAddUser(){}

    public function showTableBooks(){}

    public function showAdministrateBooks(){}

    public function showAddBook(){}

    public function showAdministrateReserves(){}

    public function showAddReserves(){}



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

            $this->showError("ERROR: action not found")
            :
            NULL;

        return utf8_encode($this->html());
    }
}