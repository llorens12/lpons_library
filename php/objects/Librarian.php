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
            $sentence .= " ORDER BY `" . $category."` DESC";
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
            "name"          => "'Name'",
            "surname"       => "'Surname'",
            "email"         => "'Email'",
            "telephone"     => "'Telephone'",
            "isbn"          => "'ISBN'",
            "id"            => "'ID Copy'",
            "title"         => "'Title'",
            "sent"          => "'Date Send'",
            "date_finish"   => "'Teoric Received'",
            'trim("-" FROM (date_finish - curdate()))' => "'Days Elapsed'"
        );


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM ((reserves JOIN users ON user = email) JOIN copybooks ON copybook = id) JOIN books ON book = isbn
                WHERE
                    date_finish < curdate()
                AND
                    sent IS NOT null
                AND
                    received IS null
            ";

        if($search != "")
        {
            $sentence .=  $this->getSearchLikeFormat($filterData, $search);
        }
        elseif ($category != "" && $category != "*") {
            $sentence .= " ORDER BY `" . $category."` DESC";
        }
        else
            $sentence .= " ORDER BY `Days Elapsed` ASC";


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
            stylesUser::contentForm
            (
                "New User",
                stylesUser::formAdministrateUser($user),
                $this->sid,
                "",
                "insert",
                "setInsertUser"
            )
        );
    }

    public function showTableCopies($category = "", $search = ""){
        $_SESSION['menu'] = "Books";

        $filterData = array
        (
            "id"              => "'ID Copy'",
            "isbn"            => "'ISBN'",
            "title"           => "'Title'",
            "author"          => "'Author'",
            "category"        => "'Category'",
            "status"          => "'Status'",
            "COUNT(copybook)" => "'Total Reserved'",
            'IF(((curdate() between date_start AND date_finish) AND sent IS NOT null AND received IS null),"Yes","No")' => "'Sent?'"
        );


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM (copybooks LEFT JOIN books ON book = isbn) LEFT JOIN reserves ON copybook = id
            ";

        if($search != "")
        {
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, "WHERE");
        }

        $sentence .= " GROUP BY id";

        if ($category != "" && $category != "*") {
            $sentence .= " ORDER BY `" . $category."` DESC";
        }
        else
            $sentence .= " ORDER BY `ISBN`";


        $this->setContent(
            stylesUser::filterMenu
            (
                "Order by",
                "Default",
                $filterData,
                "Search...",
                "showTableCopies",
                $this->sid
            ).

            stylesUser::table
            (
                $this->select($sentence)
            )
        );
    }

    public function showTableBooks($category = "", $search = ""){
        $_SESSION['menu'] = "Books";

        $filterData = array
        (
            "isbn"                           => "ISBN",
            "title"                          => "Title",
            "author"                         => "Author",
            "category"                       => "Category",
            "COUNT(copybook)"                => "'Total Reserved'",
            "COUNT(DISTINCT id)"             => "'Copies'",
            "sum(DISTINCT IF(((curdate() between date_start AND date_finish) AND sent IS NOT null),1,0))" => "'Sent'",
            "((COUNT(DISTINCT id))-(sum(DISTINCT if(((curdate() between date_start AND date_finish) and sent is not null),1,0))))" => "'Not Sent'",
            'SUM(DISTINCT IF(status = "New",1,0))'    => "'New'",
            'SUM(DISTINCT IF(status = "Good", 1, 0))' => "'Good'",
            'SUM(DISTINCT IF(status = "Bad", 1, 0))'  => "'Bad'"
        );


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM (copybooks LEFT JOIN books ON book = isbn) LEFT JOIN reserves ON copybook = id
            ";

        if($search != "")
        {
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, "WHERE");
        }

        $sentence .= " GROUP BY book";

        if ($category != "" && $category != "*") {
            $sentence .= " ORDER BY `" . $category."` DESC";
        }


        $this->setContent(
            stylesUser::filterMenu
            (
                "Order by",
                "Default",
                $filterData,
                "Search...",
                "showTableBooks",
                $this->sid
            ).

            stylesUser::table
            (
                $this->select($sentence)
            )
        );
    }

    public function showAdministrateBooks($category = "", $search = ""){
        $_SESSION['menu'] = "Books";

        $filterData = array
        (
            "isbn"                           => "ISBN",
            "title"                          => "Title",
            "author"                         => "Author",
            "description"                    => "Description",
            "category"                       => "Category"
        );


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM books
            ";

        if($search != "")
        {
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, "WHERE");
        }


        if ($category != "" && $category != "*") {
            $sentence .= " ORDER BY `" . $category."` DESC";
        }


        $this->setContent(
            stylesUser::filterMenu
            (
                "Order by",
                "Default",
                $filterData,
                "Search...",
                "showAdministrateBooks",
                $this->sid
            ).

            stylesUser::table
            (
                $this->select($sentence),
                true,
                true,
                true,
                "Book",
                ["ISBN"],
                false,
                0,
                $this->sid
            )
        );
    }

    public function showAddBook(){
        $this->setContent
        (
            stylesUser::contentForm
            (
                "Add Book",
                stylesLibrarian::formBook(),
                $this->sid,
                "",
                "insert",
                "setInsertBook"
            )
        );
    }

    public function showAddCopy($isbn){
        $this->setContent
        (
            stylesUser::contentForm
            (
                "Add Book",
                stylesLibrarian::formCopy($isbn),
                $this->sid,
                "",
                "insert",
                "setInsertCopy"
            )
        );
    }

    public function showAdministrateReserves(){}

    public function showAddReserves(){}


    public function setInsertCopy($copy){
        return $this->insert("copybooks",$copy);
    }

    public function setInsertBook($request){

    }

    public function setInsertUser($request){

        unset($request['typeUser'], $request['registered']);

        $request['registered'] = date('Y-m-d');
        $request['typeUser'] = "User";
        $request['pwd'] = md5($request['pwd']);

        return $this->insert("users",$request);
    }

    public function showEditBook($request){
        $_SESSION['menu'] = "Books";

        $email = "";
        $error = "";

        if(isset($request['ISBN']))
            $email = $request['ISBN'];

        if(isset($request['error']))
            $error = $request['error'];


        $book = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT *
                FROM books
                WHERE isbn = '".$email."'
            ")
        );

        $this->setContent
        (
            stylesUser::contentForm
            (
                "Edit ".$book['title'],
                stylesLibrarian::formBook($book),
                $this->sid,
                $error,
                "update",
                "setUpdateBook"
            )
        );
    }

    public function showEditUser($request){
        $_SESSION['menu'] = "Users";

        $email = "";
        $error = "";

        if(isset($request['Email']))
            $email = $request['Email'];

        if(isset($request['error']))
            $error = $request['error'];


        $user = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT email, name, surname, telephone, home, typeUser
                FROM users
                WHERE email = '".$email."' AND typeUser = 'User'
            ")
        );

        $this->setContent
        (
            stylesUser::contentForm
            (
                "Edit ".$user['name'],
                stylesUser::formAdministrateUser($user),
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