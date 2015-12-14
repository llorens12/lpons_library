<?php

/**
 * Class Librarian, this class extends of User, get all methods and contains the tools of custom users administrator.
 */
class Librarian extends User
{
    /**
     * Librarian constructor.
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
     * Override the parent method and show my profile form.
     * @param array $request *Description*: contains request array and check error.
     * @void method.
     */
    public function showMyProfile($request)
    {
        $_SESSION['menu'] = "";

        $currentUser = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT
                  email,
                  name,
                  surname,
                  telephone,
                  home,
                  typeUser
                FROM users
                WHERE email = '{$this->emailUser}'
            ")
        );

        $this->setContent
        (
            stylesUser::contentForm
            (
                "My Profile",
                stylesLibrarian::formAdministrateUser($currentUser),
                $this->sid,
                (isset($request['error'])),
                "update",
                "setUpdateMyProfile"
            )
        );
    }

    /**
     * This method print all information of the users.
     * @param string $category *Description*: execute a filter of category.
     * @param string $search *Description*: search a specific user.
     * @void method.
     */
    public function showTableUsers($category = "", $search = "")
    {
        $_SESSION['menu'] = "Users";

        $filterData = array
        (
            "name"          => "Name",
            "surname"       => "Surname",
            "email"         => "Email",
            "telephone"     => "Telephone",
            "typeUser"      => "Type User",
            "registered"    => "Registered",
            "CONCAT((curdate() - registered), ' days')" => "Seniority",
            "COUNT(user)"   => "Total Reserves",
            "SUM(if(((curdate() between date_start AND date_finish) AND sent IS NOT null AND received IS null), 1, 0))"  => "Books in House",
            "SUM(IF((curdate() < date_start), 1, 0))"   => "Next Reserves",
            "IF((SUM(IF((date_finish < curdate() AND sent IS NOT null AND received IS null), 1, 0)) != 0), 'Yes', 'No')" => "Defaulter?"
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
            $sentence .= "WHERE typeUser = 'User'";

        else
            $where = "WHERE";



        if($search != "")
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, $where);


        $sentence .= " GROUP BY `email`";

        if ($category != "" && $category != "*")
            $sentence .= " ORDER BY `" . $category."`";



        $this->setContent
        (
            stylesUser::filterMenu
            (
                "Info Users",
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

    /**
     * This method print all information of defaulters.
     * @param string $category *Description*: execute a filter of category.
     * @param string $search *Description*: search a specific user.
     * @void method.
     */
    public function showTableDefaulters($category = "", $search = "")
    {
        $_SESSION['menu'] = "Users";

        $filterData = array
        (
            "name"          => "Name",
            "surname"       => "Surname",
            "email"         => "Email",
            "telephone"     => "Telephone",
            "typeUser"      => "Type User",
            "isbn"          => "ISBN",
            "id"            => "ID Copy",
            "title"         => "Title",
            "sent"          => "Date Send",
            "date_finish"   => "Teoric Received",
            'CONCAT(trim("-" FROM (date_finish - curdate())), " days")' => "Days Elapsed"
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
            $sentence .=  $this->getSearchLikeFormat($filterData, $search);


        if ($category != "" && $category != "*")
            $sentence .= " ORDER BY `" . $category."` DESC";

        else
            $sentence .= " ORDER BY `Days Elapsed` ASC";


        $this->setContent
        (
            stylesUser::filterMenu
            (
                "Info Defaulters",
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

    /**
     * This method print all information of books.
     * @param string $category *Description*: execute a filter of category.
     * @param string $search *Description*: search a specific user.
     * @void method.
     */
    public function showTableBooks($category = "", $search = "")
    {
        $_SESSION['menu'] = "Books";

        $filterData = array
        (
            "isbn"                           => "ISBN",
            "title"                          => "Title",
            "author"                         => "Author",
            "category"                       => "Category",
            "COUNT(copybook)"                => "Total Reserved",
            "COUNT(DISTINCT id)"             => "Copies",
            "sum(DISTINCT IF(((curdate() between date_start AND date_finish) AND sent IS NOT null),1,0))" => "Sent",
            "((COUNT(DISTINCT id))-(sum(DISTINCT if(((curdate() between date_start AND date_finish) and sent is not null),1,0))))" => "Not Sent",
            'SUM(DISTINCT IF(status = "New",1,0))'    => "New",
            'SUM(DISTINCT IF(status = "Good", 1, 0))' => "Good",
            'SUM(DISTINCT IF(status = "Bad", 1, 0))'  => "Bad"
        );


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM (copybooks RIGHT JOIN books ON book = isbn) LEFT JOIN reserves ON copybook = id
            ";

        if($search != "")
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, "WHERE");


        $sentence .= " GROUP BY book";

        if ($category != "" && $category != "*")
            $sentence .= " ORDER BY `" . $category."` DESC";



        $this->setContent
        (
            stylesUser::filterMenu
            (
                "Info Books",
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

    /**
     * This method print all information of copies books.
     * @param string $category *Description*: execute a filter of category.
     * @param string $search *Description*: search a specific user.
     * @void method.
     */
    public function showTableCopies($category = "", $search = "")
    {
        $_SESSION['menu'] = "Books";

        $filterData = array
        (
            "isbn"            => "ISBN",
            "id"              => "ID Copy",
            "title"           => "Title",
            "author"          => "Author",
            "category"        => "Category",
            "status"          => "Status",
            "COUNT(copybook)" => "Total Reserved",
            'IF(((curdate() between date_start AND date_finish) AND sent IS NOT null AND received IS null),"Yes","No")' => "Sent?"
        );


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM (copybooks LEFT JOIN books ON book = isbn) LEFT JOIN reserves ON copybook = id
            ";

        if($search != "")
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, "WHERE");


        $sentence .= " GROUP BY id";

        if ($category != "" && $category != "*")
            $sentence .= " ORDER BY `" . $category."` DESC";

        else
            $sentence .= " ORDER BY `isbn`,`id`";


        $this->setContent
        (
            stylesUser::filterMenu
            (
                "Info Copies",
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



    /**
     * This method print a user administrator.
     * @param string $category *Description*: execute a filter of category.
     * @param string $search *Description*: search a specific user.
     * @void method.
     */
    public function showAdministrateUsers($category = "", $search = "")
    {
        $_SESSION['menu'] = "Users";

        $filterData = array
        (
            "name"          => "Name",
            "surname"       => "Surname",
            "email"         => "Email",
            "telephone"     => "Telephone",
            "typeUser"      => "Type User",
            "registered"    => "Registered",
            "home"          => "Home"
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
            $sentence .= "WHERE typeUser = 'User'";

        else
            $where = "WHERE";


        if ($category != "" && $category != "*")
            $sentence .= " ORDER BY `" . $category."` DESC";

        elseif($search != "")
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, $where);



        $this->setContent
        (
            stylesUser::filterMenu
            (
                "Administrate Users",
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

    /**
     * This method print a books administrator.
     * @param string $category *Description*: execute a filter of category.
     * @param string $search *Description*: search a specific user.
     * @void method.
     */
    public function showAdministrateBooks($category = "", $search = "")
    {
        $_SESSION['menu'] = "Books";

        $filterData = array
        (
            "isbn"        => "ISBN",
            "title"       => "Title",
            "author"      => "Author",
            "description" => "Description",
            "category"    => "Category"
        );


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM books
            ";

        if($search != "")
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, "WHERE");



        if ($category != "" && $category != "*")
            $sentence .= " ORDER BY `" . $category."` DESC";



        $this->setContent
        (
            stylesUser::filterMenu
            (
                "Administrate Books",
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

    /**
     * This method print a copies books administrator.
     * @param string $category *Description*: execute a filter of category.
     * @param string $search *Description*: search a specific user.
     * @void method.
     */
    public function showAdministrateCopies($category = "", $search = "")
    {
        $_SESSION['menu'] = "Books";

        $filterData = array
        (
            "isbn"            => "ISBN",
            "id"              => "ID Copy",
            "title"           => "Title",
            "author"          => "Author",
            "category"        => "Category",
            "status"          => "Status"
        );


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM (copybooks LEFT JOIN books ON book = isbn) LEFT JOIN reserves ON copybook = id
            ";

        if($search != "")
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, "WHERE");


        $sentence .= " GROUP BY id";

        if ($category != "" && $category != "*")
            $sentence .= " ORDER BY `" . $category."` DESC";

        else
            $sentence .= " ORDER BY `isbn`,`id`";

        $this->setContent
        (
            stylesUser::filterMenu
            (
                "Administrate Copies",
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

    /**
     * This method print a users reserves administrator.
     * @param string $category *Description*: execute a filter of category.
     * @param string $search *Description*: search a specific user.
     * @void method.
     */
    public function showAdministrateUsersReserves($category = "", $search = "")
    {
        $_SESSION['menu'] = "Reserves";

        $filterData = array
        (
            "user"          => "User",
            "typeUser"      => "Type User",
            "copyBook"      => "ID Copy",
            "isbn"          => "ISBN",
            "title"         => "Title",
            "author"        => "Author",
            "category"      => "Category",
            "date_start"    => "Start",
            "date_finish"   => "End",
            "sent"          => "Sent",
            "received"      => "Received"
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
            $sentence .= "WHERE typeUser = 'User'";

        else
            $where = "WHERE";


        if ($category != "" && $category != "*")
            $sentence .= " ORDER BY `" . $category."` DESC";


        if($search != "")
            $sentence .=  $this->getSearchLikeFormat($filterData, $search, $where);


        $this->setContent
        (
            stylesUser::filterMenu
            (
                "Administrate Users Reserves",
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



    /**
     * This method print a form to add new user.
     * @void method.
     */
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

    /**
     * This method print a form to add new book.
     * @void method.
     */
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

    /**
     * This method print a form to add new copy book.
     * @param string $isbn *Description*: contains the isbn to add copy book.
     * @void method.
     */
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

    /**
     * This method print a form to add new user reserve.
     * @param array $request *Description*: contains request array to verify if exists an error.
     * @void method.
     */
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



    /**
     * This method print a form to edit existing user.
     * @param array $request *Description*: contains all user data.
     * @void method.
     */
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
                stylesLibrarian::formAdministrateUser($user),
                $this->sid,
                (isset($request['error'])),
                "update",
                "setUpdateUser&previousEmail=".$user['email']
            )
        );
    }

    /**
     * This method print a form to edit existing book.
     * @param array $request *Description*: contains all book data.
     * @void method.
     */
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

    /**
     * This method print a form to edit existing book copy.
     * @param array $request *Description*: contains all book copy data.
     * @void method.
     */
    public function showEditCopy($request)
    {
        $_SESSION['menu'] = "Books";


        $data = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT *
                FROM copybooks
                WHERE id = '".$request['IDCopy']."'
            ")
        );


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

    /**
     * This method print a form to edit existing user reserve.
     * @param array $request *Description*: contains all user reserve data.
     * @void method.
     */
    public function showEditUserReserve($request)
    {
        $_SESSION['menu'] = "Reserves";


        $data = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT isbn, title, author, category, date_start, date_finish, copybook, user, sent, received
                FROM (reserves JOIN copybooks ON copybook = id)
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
                "setUpdateUserReserve&copyBook=".$data['copybook'].'&firstDateStart='.$data['date_start']."&email=".$data['user']
            )
        );
    }



    /**
     * This method insert a new User.
     * @param array $request *Description*: contains all user data.
     * @return boolean *Description*: if insert success or not
     */
    public function setInsertUser($request)
    {
        $_SESSION['menu'] = "Users";

        if($_SESSION['typeUser'] != 'Admin')
            $request['typeUser'] = "User";


        $request['registered'] = date('Y-m-d');
        $request['pwd'] = md5($request['pwd']);

        return $this->insert("users",$request);
    }

    /**
     * This method insert a new Book.
     * @param array $request *Description*: contains all book data.
     * @param array $file *Description*: contains cover book.
     * @return bool *Description*: if insert is success or not.
     */
    public function setInsertBook($request, $file)
    {
        if (!$this->issetBook($request['isbn']) && $this->uploadIMG($file,$request['isbn']))
            return $this->insert("books",$request);

        return false;
    }

    /**
     * This method insert a new Book copy.
     * @param array $copy *Description*: contains all book copy data.
     * @return bool *Description*: if insert is success or not.
     */
    public function setInsertCopy($copy)
    {
        return $this->insert("copybooks",$copy);
    }

    /**
     * This method insert a new personalized reserve.
     * @param array $request *Description*: contains all reserve data.
     * @return bool *Description*: if insert is success or not.
     */
    public function setInsertUserPersonalizedReserve($request)
    {
        return $this->insertPersonalizedReserve($request);
    }

    /**
     * This method insert a new default reserve.
     * @param array $request *Description*: contains all default reserve data.
     * @return bool *Description*: if insert is success or not.
     */
    public function setInsertUserDefaultReserve($request)
    {
        return $this->insertDeffaultReserve($request);
    }



    /**
     * This method update an existing user.
     * @param array $request *Description*: contains all new user data.
     * @return bool *Description*: if update is success or not.
     */
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

    /**
     * This method update an existing book.
     * @param array $request *Description*: contains all new book data.
     * @param array $file *Description*: contains or not a new cover book.
     * @return bool *Description*: if update is success or not.
     */
    public function setUpdateBook($request, $file)
    {
        if(is_uploaded_file($_FILES['cover']['tmp_name']))
        {
            unlink("../img/books/".$request['primaryISBN'].".jpg");
            $this->uploadIMG($file, $request['isbn']);
        }
        elseif($request['primaryISBN'] != $request['isbn'])
        {
            rename("../img/books/".$request['primaryISBN'].".jpg", "../img/books/".$request['isbn'].".jpg");
        }
        else
            return false;

        $where = "isbn = '".$request['primaryISBN']."'";
        unset($request['primaryISBN']);

        return $this->update("books",$request,$where);
    }

    /**
     * This method update an existing book copy.
     * @param array $request *Description*: contains all new book copy data.
     * @return bool *Description*: if update is success or not.
     */
    public function setUpdateCopy($request)
    {
        $where = "id = '".$request['id']."'";
        unset($request['IDCopy']);

        return $this->update("copybooks", $request,$where);
    }

    /**
     * This method update an existing user reserve.
     * @param array $request *Description*: contains all new user reserve data.
     * @return bool *Description*: if update is success or not.
     */
    public function setUpdateUserReserve($request)
    {
        return $this->updateReserve($request);
    }



    /**
     * This method delete an existing user.
     * @param string $email *Description*: contains the user email to delete.
     * @return bool *Description*: if delete is success or not.
     */
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
           return $this->delete("users","email = '".$email."'");

        return false;
    }

    /**
     * This method delete an existing book.
     * @param string $isbn *Description*: contains the book isbn to delete.
     * @return bool *Description*: if delete is success or not.
     */
    public function setDeleteBook($isbn)
    {
        unlink("../img/books/".$isbn.".jpg");
        return $this->delete("books", "isbn = '".$isbn."'");
    }

    /**
     * This method delete an existing book copy.
     * @param array $request *Description*: contains the id book to delete.
     * @return bool *Description*: if delete is success or not.
     */
    public function setDeleteCopy($request)
    {
        return $this->delete("copybooks", "id = '".$request['IDCopy']."'");
    }

    /**
     * This method delete an existing user reserve.
     * @param array $request *Description*: contains all data to user reserve delete.
     * @return bool *Description*: if delete is success or not.
     */
    public function setDeleteUserReserve($request)
    {
        return $this->delete("reserves","copybook = '".$request['IDCopy']."' AND date_start = '".$request['Start']."'");
    }



    /**
     * This method upload book cover.
     * @param array $img *Description*: contains the book cover image.
     * @param string $isbn *Description*: contains the isbn book to upload image.
     * @return bool *Description*: if the upload is success or not.
     */
    protected function uploadIMG($img, $isbn)
    {

        $cover = $img['cover']['tmp_name'];

        if (is_uploaded_file($cover))
        {
            if(file("../img/books/".$isbn.".jpg"))
                unlink("../img/books/".$isbn.".jpg");

            copy($cover, "../img/books/".$isbn.".jpg");
            return true;
        }
        else
            return false;

    }

    /**
     * This method check if exists book or not.
     * @param string $isbn *Description*: contains the book isbn.
     * @return bool *Description*: if exists book or not.
     */
    protected function issetBook($isbn)
    {
        $answer = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT title
                FROM books
                WHERE isbn='" . $isbn . "'
            ")
        );

        if (count($answer) != 0)
            return true;
        else
            return false;
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