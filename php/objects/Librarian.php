<?php

class Librarian extends User{



    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
    }




    public function showTableUsers($category = "", $search = "")
    {
        $_SESSION['menu'] = "Users";

        $filterData = array
        (
            "name"          => "'Name'",
            "surname"       => "'Surname'",
            "email"         => "'Email'",
            "telephone"     => "'Telephone'",
            "typeUser"      => "'Type User'",
            "registered"    => "'Registered'",
            "COUNT(user)"   => "'Total Reserves'",
            "SUM(IF((curdate() < date_finish), 1, 0))" => "'After Reserves'",
            "SUM(if(((curdate() between date_start AND date_finish) AND sent IS NOT null), 1, 0))" => "'Books in House'",
            "SUM(IF(received > date_finish, 1, 0))" => "'Total Delays'",
            "IF((SUM(IF((date_finish < curdate() AND sent IS NOT null AND received IS null), 1, 0)) != 0), 'Yes', 'No')" => "'Defaulter?'"
        );

        if($_SESSION['typeUser'] != 'Admin')
            unset($filterData['typeUser']);

        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM users LEFT JOIN reserves on email = user
            ";

        $where = "";

        if($_SESSION['typeUser'] != 'Admin')
        {
            $sentence .= "WHERE typeUser = 'User'";
        }
        else
            $where = "WHERE";



        if($search != "")
        {
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, $where);
        }

        $sentence .= " GROUP BY `email`";

        if ($category != "" && $category != "*")
        {
            $sentence .= " ORDER BY `" . $category."`";
        }


        $this->setContent(
            stylesUser::filterMenu
            (
                "Order by",
                "Default",
                $filterData,
                "Search...",
                "showTableUsers",
                $this->sid
            ).

            stylesUser::table
            (
                $this->select($sentence)
            )
        );
    }

    public function showTableDefaulters($category = "", $search = "")
    {
        $_SESSION['menu'] = "Users";

        $filterData = array
        (
            "name"          => "'Name'",
            "surname"       => "'Surname'",
            "email"         => "'Email'",
            "telephone"     => "'Telephone'",
            "typeUser"      => "'Type User'",
            "isbn"          => "'ISBN'",
            "id"            => "'ID Copy'",
            "title"         => "'Title'",
            "sent"          => "'Date Send'",
            "date_finish"   => "'Teoric Received'",
            'trim("-" FROM (date_finish - curdate()))' => "'Days Elapsed'"
        );


        if($_SESSION['typeUser'] != 'Admin')
            unset($filterData['typeUser']);

        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM ((reserves JOIN copybooks ON copybook = id) JOIN books ON book = isbn) JOIN users ON user = email
                WHERE
                    date_finish < curdate()
                AND
                    sent IS NOT null
                AND
                    received IS null
            ";


        if($_SESSION['typeUser'] != 'Admin')
            $sentence .= "AND typeUser = 'User'";



        if($search != "")
        {
            $sentence .=  $this->getSearchLikeFormat($filterData, $search);
        }

        if ($category != "" && $category != "*")
        {
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

    public function showTableBooks($category = "", $search = ""){
        $_SESSION['menu'] = "Books";

        $filterData = array
        (
            "isbn"                           => "'ISBN'",
            "title"                          => "'Title'",
            "author"                         => "'Author'",
            "category"                       => "'Category'",
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
                FROM (copybooks RIGHT JOIN books ON book = isbn) LEFT JOIN reserves ON copybook = id
            ";

        if($search != "")
        {
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, "WHERE");
        }

        $sentence .= " GROUP BY book";

        if ($category != "" && $category != "*")
        {
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

    public function showTableCopies($category = "", $search = "")
    {
        $_SESSION['menu'] = "Books";

        $filterData = array
        (
            "isbn"            => "'ISBN'",
            "id"              => "'ID Copy'",
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

        if ($category != "" && $category != "*")
        {
            $sentence .= " ORDER BY `" . $category."` DESC";
        }
        else
            $sentence .= " ORDER BY `isbn`,`id`";


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



    public function showAdministrateUsers($category = "", $search = "")
    {
        $_SESSION['menu'] = "Users";

        $filterData = array
        (
            "name"          => "'Name'",
            "surname"       => "'Surname'",
            "email"         => "'Email'",
            "telephone"     => "'Telephone'",
            "typeUser"      => "'Type User'",
            "registered"    => "'Registered'",
            "home"          => "'Home'"
        );


        if($_SESSION['typeUser'] != 'Admin')
            unset($filterData['typeUser']);

        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM users

            ";


        $where = "";

        if($_SESSION['typeUser'] != 'Admin')
        {
            $sentence .= "WHERE typeUser = 'User'";
        }
        else
            $where = "WHERE";

        if ($category != "" && $category != "*")
        {
            $sentence .= " ORDER BY `" . $category."` DESC";
        }

        elseif($search != "")
        {
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, $where);
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
                true,
                "UserReserve",
                "User",
                ["Email"],
                false,
                false,
                0,
                $this->sid
            )
        );
    }

    public function showAdministrateBooks($category = "", $search = "")
    {
        $_SESSION['menu'] = "Books";

        $filterData = array
        (
            "isbn"                           => "'ISBN'",
            "title"                          => "'Title'",
            "author"                         => "'Author'",
            "description"                    => "'Description'",
            "category"                       => "'Category'"
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


        if ($category != "" && $category != "*")
        {
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
                "Copy",
                "Book",
                ["ISBN"],
                false,
                false,
                0,
                $this->sid
            )
        );
    }

    public function showAdministrateCopies($category = "", $search = "")
    {
        $_SESSION['menu'] = "Books";

        $filterData = array
        (
            "isbn"            => "'ISBN'",
            "id"              => "'ID Copy'",
            "title"           => "'Title'",
            "author"          => "'Author'",
            "category"        => "'Category'",
            "status"          => "'Status'"
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

        if ($category != "" && $category != "*")
        {
            $sentence .= " ORDER BY `" . $category."` DESC";
        }
        else
            $sentence .= " ORDER BY `isbn`,`id`";

        $this->setContent(
            stylesUser::filterMenu
            (
                "Order by",
                "Default",
                $filterData,
                "Search...",
                "showAdministrateCopies",
                $this->sid
            ).

            stylesUser::table
            (
                $this->select($sentence),
                true,
                true,
                false,
                "",
                "Copy",
                ["ID Copy"],
                false,
                false,
                0,
                $this->sid
            )
        );
    }

    public function showAdministrateUsersReserves($category = "", $search = "")
    {
        $_SESSION['menu'] = "Reserves";

        $filterData = array
        (
            "user"          => "'User'",
            "typeUser"      => "'Type User'",
            "copyBook"      => "'ID Copy'",
            "isbn"          => "'ISBN'",
            "title"         => "'Title'",
            "author"        => "'Author'",
            "category"      => "'Category'",
            "date_start"    => "'Start'",
            "date_finish"   => "'End'",
            "sent"          => "'Sent'",
            "received"      => "'Received'"
        );


        if($_SESSION['typeUser'] != "Admin")
            unset($filterData['typeUser']);


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM ((reserves LEFT JOIN copybooks ON copybook = id ) LEFT JOIN books ON book = isbn) LEFT JOIN users ON user = email
            ";

        $where = "";

        if($_SESSION['typeUser'] != 'Admin')
        {
            $sentence .= "WHERE typeUser = 'User'";
        }
        else
            $where = "WHERE";


        if ($category != "" && $category != "*") {
            $sentence .= " ORDER BY `" . $category."` DESC";
        }

        if($search != "")
        {
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, $where);
        }



        $this->setContent(
            stylesUser::filterMenu
            (
                "Order by",
                "Default",
                $filterData,
                "Search...",
                "showAdministrateUsersReserves",
                $this->sid
            ).

            stylesUser::table
            (
                $this->select($sentence),
                true,
                true,
                false,
                "",
                "UserReserve",
                ["ID Copy", "Start"],
                true,
                false,
                $this->MAX_DAYS_RESERVE,
                $this->sid
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
                (isset($_REQUEST['error'])),
                "insert",
                "setInsertUser"
            )
        );
    }

    public function showAddBook()
    {
        $_SESSION['menu'] = "Books";

        $this->setContent
        (
            stylesUser::contentForm
            (
                "Add Book",
                stylesLibrarian::formBook(),
                $this->sid,
                (isset($_REQUEST['error'])),
                "insert",
                "setInsertBook"
            )
        );
    }

    public function showAddCopy($isbn)
    {
        $_SESSION['menu'] = "Books";

        $isbn['isbn'] = $isbn['ISBN'];

        $this->setContent
        (
            stylesUser::contentForm
            (
                "Add Copy",
                stylesLibrarian::formCopy($isbn),
                $this->sid,
                (isset($_REQUEST['error'])),
                "insert",
                "setInsertCopy&book=".$isbn['isbn']
            )
        );
    }

    public function showAddUserReserve($request)
    {
        $_SESSION['menu'] = "Reserves";

    $this->setContent
    (
        stylesLibrarian::formAddReserve
        (
            $request,
            $this->select
            ("
                SELECT isbn, title FROM books WHERE  isbn IN
                (
                    SELECT   book
                    FROM     copybooks
                    GROUP BY book
                )
            "),
            (isset($request['error']))
        )
    );
    }



    public function showEditUser($request)
    {
        $_SESSION['menu'] = "Users";


        $typeUser = "";
        if($_SESSION['typeUser'] != "Admin")
            $typeUser = "AND typeUser = 'User'";

        $user = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT email, name, surname, telephone, home, typeUser
                FROM users
                WHERE
                    email = '".$request['Email']."'
                ".$typeUser."
            ")
        );

        $this->setContent
        (
            stylesUser::contentForm
            (
                "Edit ".$user['name'],
                stylesUser::formAdministrateUser($user),
                $this->sid,
                (isset($request['error'])),
                "update",
                "setUpdateUser&previousEmail=".$user['email']
            )
        );
    }

    public function showEditBook($request)
    {
        $_SESSION['menu'] = "Books";

        $book = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT *
                FROM books
                WHERE isbn = '".$request['ISBN']."'
            ")
        );

        $this->setContent
        (
            stylesUser::contentForm
            (
                "Edit ".$book['title'],
                stylesLibrarian::formBook($book),
                $this->sid,
                (isset($request['error'])),
                "update",
                "setUpdateBook&primaryISBN=".$book['isbn']
            )
        );
    }

    public function showEditCopy($request)
    {
        $_SESSION['menu'] = "Books";


        $data = mysqli_fetch_assoc($this->select("SELECT * FROM copybooks WHERE id = ".$request['IDCopy']));


        $this->setContent
        (
            stylesUser::contentForm
            (
                "Edit Copy",
                stylesLibrarian::formCopy($data),
                $this->sid,
                (isset($request['error'])),
                "update",
                "setUpdateCopy&id=".$request['IDCopy']
            )
        );
    }

    public function showEditUserReserve($request)
    {
        $_SESSION['menu'] = "Reserves";


        $data = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT isbn, title, author, category, date_start, date_finish, copybook, user, sent, received
                FROM (
                reserves
                JOIN copybooks ON copybook = id
                )
                JOIN books ON book = isbn
                WHERE
                copybook =  '".$request['IDCopy']."'
                    AND
                date_start =  '".$request['Start']."'
             ")
        );


        $this->setContent
        (
            stylesUser::contentForm
            (
                "Edit User Reserve",
                stylesLibrarian::formEditUserReserve($data),
                $this->sid,
                (isset($request['error'])),
                "update",
                "setUpdateUserReserve&copybook=".$data['copybook'].'&firstDateStart='.$data['date_start']
            )
        );
    }



    public function setInsertUser($request)
    {
        $_SESSION['menu'] = "Users";

        if($_SESSION['typeUser'] != 'Admin')
        {
            $request['typeUser'] = "User";
        }

        $request['registered'] = date('Y-m-d');
        $request['pwd'] = md5($request['pwd']);

        return $this->insert("users",$request);
    }

    public function setInsertBook($request, $file)
    {
        if (!$this->issetBook($request['isbn']) && $this->uploadIMG($file,$request['isbn']))
            return $this->insert("books",$request);
    }

    public function setInsertCopy($copy)
    {
        return $this->insert("copybooks",$copy);
    }

    public function setInsertUserPersonalizedReserve($request)
    {
        return $this->insertPersonalizedReserve($request);
    }

    public function setInsertUserDefaultReserve($request)
    {
        return $this->insertDeffaultReserve($request);
    }



    public function setUpdateUser($request)
    {
        if($_SESSION['typeUser'] != 'Admin')
        {
            unset($request['typeUser']);
        }

        unset($request['registered']);


        if(isset($request['pwd']) && ($request['pwd'] == "" || $request['pwd'] == " "))
        {
            unset($request['pwd']);
        }
        else
            $request['pwd'] = md5($request['pwd']);

        

        $where = "email = '".$request['previousEmail']."'";
        unset($request['previousEmail']);


        return ($this->update("users",$request,$where));
    }

    public function setUpdateBook($request, $file)
    {
        if(is_uploaded_file($_FILES['cover']['tmp_name']))
        {
            unlink("../img/books/".$request['primaryISBN'].".jpg");
            $this->uploadIMG($file, $request['isbn']);
        }

        $where = "isbn = '".$request['primaryISBN']."'";
        unset($request['primaryISBN']);

        return $this->update("books",$request,$where);
    }

    public function setUpdateCopy($request)
    {
        $where = "id = '".$request['IDCopy']."'";
        unset($request['IDCopy']);

        return $this->update("copybooks", $request,$where);
    }

    public function setUpdateUserReserve($request)
    {
        if(isset($request['received']))
            $request['date_start'] = $request['received'];

        $where = "copybook = '".$request['copybook']."' AND date_start = '".$request['firstDateStart']."'";
        unset($request['copybook'], $request['firstDateStart']);

        $this->update("reserves", $request, $where);
    }



    public function setDeleteUser($email)
    {
        $isPosbible = false;

        if($_SESSION['typeUser'] != "Admin")
        {
            $tmp = mysqli_fetch_assoc($this->select("SELECT typeUser FROM users WHERE email = '".$email."'"));

            if($tmp['typeUser'] == "User")
                $isPosbible = true;
        }
        else
            $isPosbible = true;

        if($isPosbible)
           $this->delete("users","email = '".$email."'");

    }

    public function setDeleteBook($isbn)
    {
        $this->delete("books", "isbn = '".$isbn."'");
    }

    public function setDeleteCopy($request)
    {
        $this->delete("copybooks", "id = '".$request['IDCopy']."'");
    }

    public function setDeleteUserReserve($request)
    {
        $this->delete("reserves","copybook = '".$request['IDCopy']."' AND date_start = '".$request['Start']."'");
    }





    protected function uploadIMG($img, $isbn)
    {

        $cover = $img['cover']['tmp_name'];

        if (is_uploaded_file($cover))
        {
            copy($cover, "../img/books/".$isbn.".jpg");
            echo true;
            return true;
        }
        else {
            echo false;
            return false;
        }
    }

    protected function issetBook($isbn){
        $answer = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT title
                FROM books
                WHERE isbn='" . $isbn . "'
            ")
        );
echo count($answer);
        if (count($answer) != 0)
            return true;
        else
            return false;
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