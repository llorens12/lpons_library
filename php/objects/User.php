<?php
/**
 * Class User, this class extends of template and contains all options to custom user.
 */
class User extends Template
{
    /**
     * Used to connect the Database.
     */
    use DBController;

    /**
     * @var int *Description*: this var contains the global value of default reserve.
     */
    protected $DEFAULT_DAYS_RESERVE = 20;

    /**
     * @var int *Description*: this var contains the global value of delimiter max days of reserve
     * (personalized or default).
     */
    protected $MAX_DAYS_RESERVE     = 59;//The reserve is the 60 days because the 0 count


    /**
     * User constructor.
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
     * This method show details of specific book and contains the form to reserve book.
     * @param string $isbn *Description*: contains the book isbn to see.
     * @void method.
     */
    public function showBook($isbn)
    {

        $_SESSION['menu']="Books";

        $this->setContent
        (
            stylesUser::filterMenu
            (
                "",
                "Select Category",
                "Default",
                $this->getArrayToResult
                (
                    $this->select
                    ("
                        SELECT category
                        FROM books
                        GROUP BY category
                        ORDER BY category
                    ")
                ),
                "Search...",
                "showBooks",
                $this->sid
            ).

            stylesUser::book
            (
                mysqli_fetch_assoc
                (
                    $this->select
                    ("
                        SELECT isbn, title, summary, category, author
                        FROM books
                        WHERE isbn = '".$isbn."'"
                    )
                )
                ,
                $this->DEFAULT_DAYS_RESERVE,
                $this->sid
            )
        );
    }

    /**
     * This method print all information to the books.
     * @param string $category *Description*: execute a filter of category.
     * @param string $search *Description*: search a specific user.
     * @void method.
     */
    public function showBooks($category = "", $search = "")
    {
        $_SESSION['menu']="Books";

        $sentence =
        "
            SELECT isbn, title, description, category, author
            FROM   books
            WHERE  isbn IN
            (
              SELECT   book
              FROM     copybooks
              GROUP BY book
            )
        ";

        if ($category != "" && $category != "*" && $category != "Select Category") {
            $sentence .= " AND category='" . $category . "'";
        }

        if($search != "") {
            $sentence .=
            "
                AND
                (    LOWER(author) LIKE LOWER('%" . $search . "%')
                OR   LOWER(title)  LIKE LOWER('%" . $search . "%')
                OR   LOWER(isbn)   LIKE LOWER('%" . $search . "%')
                )
            ";
        }

        $content = "";

        $content .= stylesUser::filterMenu
        (
            "",
            ($category != "" && $category != "*")? $category : "Select Category",
            "All",
            $this->getArrayToResult
            (
                $this->select
                ("
                    SELECT   category
                    FROM     books
                    GROUP BY category
                    ORDER BY category
                ")
            ),
            "ISBN Title Author...",
            "showBooks",
            $this->sid
        );

       $books = $this->select($sentence);

        if(mysqli_num_rows($books) == 0){
            $content .= "<h1 style='width: 100%; text-align: center'>Haven't located any books</h1>";
        }

        while($book = mysqli_fetch_assoc($books)){
            $content .= stylesUser::books
            (
                $book,
                $this->DEFAULT_DAYS_RESERVE,
                $this->sid
            );
        }

        $this->setContent($content);
    }

    /**
     * This method print all information of the user reserves.
     * @param string $category *Description*: execute a filter of category.
     * @param string $search *Description*: search a specific user.
     * @void method.
     */
    public function showReserves($category = "",$search = "")
    {
        $_SESSION['menu'] = "Reserves";

        $filterData = array
        (
            "isbn"          => "ISBN",
            "title"         => "Title",
            "author"        => "Author",
            "category"      => "Category",
            "date_start"    => "Start",
            "date_finish"   => "End",
            "sent"          => "Sent",
            "received"      => "Received"
        );


        $sentence =
            "
                SELECT ".$this->getQueryNamesFormat($filterData)."
                FROM
                (
                    reserves
                    LEFT JOIN copybooks
                    ON copybook = id
                )
                LEFT JOIN books
                ON book = isbn
                WHERE user =  '".$this->emailUser."'
            ";

        unset($filterData['sent'], $filterData['received']);

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
                "My Reserves",
                "Order by",
                "Default",
                $filterData,
                "Search...",
                "showReserves",
                $this->sid
            ).

            stylesUser::table
            (
                $this->select($sentence),
                true,
                true,
                false,
                "",
                "Reserve",
                ["ISBN","Start"],
                true,
                true,
                $this->MAX_DAYS_RESERVE,
                $this->sid
            )
        );
    }

    /**
     * This method print the form to edit user profile.
     * @param array $request *Description*: contains all user data.
     * @void method.
     */
    public function showMyProfile($request)
    {
        $_SESSION['menu'] = "";

        $currentUser = mysqli_fetch_assoc
        (
            $this->select
            ("
                        SELECT email, name, surname, telephone, home, typeUser
                        FROM users
                        WHERE email = '{$this->emailUser}'
                    ")
        );

        $this->setContent
        (
            stylesUser::contentForm
            (
                "My Profile",
                stylesUser::formAdministrateUser($currentUser),
                $this->sid,
                (isset($request['error'])),
                "update",
                "setUpdateMyProfile"
            )
        );
    }


    /**
     * This method print the form to edit user reserve.
     * @param array $request *Description*: contains all reserve information.
     */
    public function showEditReserve($request){

        $_SESSION['menu'] = "Reserves";

        $email = $this->emailUser;


        $reserve = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT isbn, title, author, category, date_start, date_finish, copybook, sent, received
                FROM (
                reserves
                JOIN copybooks ON copybook = id
                )
                JOIN books ON book = isbn
                WHERE
                user =  '".$email."'
                    AND
                book =  '".$request['ISBN']."'
                    AND
                date_start =  '".$request['Start']."'
             ")
        );

        $this->setContent
        (
            stylesUser::contentForm
            (
                "Edit Reserve",
                stylesUser::formEditReserves($reserve),
                $this->sid,
                (isset($request['error'])),
                "update",
                'setUpdateReserve&copyBook='.$reserve['copybook'].'&firstDateStart='.$reserve['date_start']
            )
        );
    }


    /**
     * This method insert a new default reserve.
     * @param array $request *Description*: contains all reserve data.
     * @return bool *Description*: if insert is success or not.
     */
    public function setInsertDefaultReserve($request)
    {
        $request['user'] = $_SESSION['email'];
        return $this->insertDeffaultReserve($request);
    }

    /**
     * This method insert a new personalized reserve.
     * @param array $request *Description*: contains all reserve data.
     * @return bool *Description*: if insert is success or not.
     */
    public function setInsertPersonalizedReserve($request)
    {
        $request['user'] = $_SESSION['email'];
        return $this->insertPersonalizedReserve($request);
    }


    /**
     * This method update an existing reserve.
     * @param array $request *Description*: contains all new data of reserve.
     * @return bool *Description*: if update is success or not.
     */
    public function setUpdateReserve($request){

        $request['email'] = $this->emailUser;
        return $this->updateReserve($request);
    }

    /**
     * This method update a user profile.
     * @param array $request *Description*: contains all new data of user profile.
     * @return bool *Description*: if update is success or not.
     */
    public function setUpdateMyProfile($request){

        unset($request['typeUser'], $request['registered']);


        if(isset($request['pwd']) && ($request['pwd'] == "" || $request['pwd'] == " ")){
            unset($request['pwd']);
        }
        else
            $request['pwd'] = md5($request['pwd']);

        $_SESSION['name'] = $request['name'];
        $_SESSION['home'] = "controller.php?method=".$request['home'];

        $where = "email = '".$this->emailUser."'";

        if($this->update("users",$request,$where)){
            (isset($request['email']))? $_SESSION['email'] = $request['email'] : NULL;
            return true;
        }

        return false;
    }


    /**
     * This method delete an a user reserve.
     * @param array $request *Description*: contains all reserve data.
     * @return bool *Description*: if delete is success or not.
     */
    public function setDeleteReserve($request)
    {
        $request['email'] = $this->emailUser;

        $copyBook = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT id
                FROM reserves
                JOIN copybooks ON copybook = id
                WHERE user =  '".$request['email']."'
                AND book =  '".$request['ISBN']."'
                AND date_start =  '".$request['Start']."'
            ")
        )['id'];

        return $this->delete("reserves","copybook = '".$copyBook."' AND date_start = '".$request['Start']."'");
    }


    /**
     * This method broken the current session.
     * @void method.
     */
    public function logOut()
    {
        session_destroy();

        if(isset($_COOKIE['email'], $_COOKIE['pwd'])){
            setcookie("email", "", 0, "/");
            setcookie("pwd"  , "", 0, "/");
        };
    }



    /**
     * This method is common with Librarian and Admin and update reserves.
     * @param array $reserve *Description*: contains all reserve data.
     * @return bool *Description*: if update is succes or not.
     */
    protected function updateReserve($reserve)
    {
        if(!isset($reserve['date_start']))
            $reserve['date_start'] = $reserve['firstDateStart'];

        $difference = $this->getDateDifference($reserve['date_start'],$reserve['date_finish']);

        if($difference < 0)
            return false;

        if($difference > ($this->MAX_DAYS_RESERVE)){
           $reserve['date_finish'] = date('Y-m-d', strtotime (($this->MAX_DAYS_RESERVE - $difference).' day', strtotime($reserve['date_finish'])));
        }

        $sentence =
        "
            SELECT date_start
            FROM reserves
            WHERE
                copybook = '".$reserve['copyBook']."'
            AND
                date_start != '".$reserve['firstDateStart']."'
            AND
                (
                    ('". $reserve['date_start'] ."' < date_start AND '". $reserve['date_finish'] ."' > date_finish)
                OR
                    '". $reserve['date_start'] ."' BETWEEN date_start AND date_finish
                OR
                    '". $reserve['date_finish'] ."' BETWEEN date_start AND date_finish
                )
        ";

        if(mysqli_num_rows($this->select($sentence)) != 0)
            return false;

        $where =
        "
                user = '".$reserve['email']."'
            AND
                date_start = '".$reserve['firstDateStart']."'
            AND
                copybook = '".$reserve['copyBook']."'
        ";


        if(isset($request['received']))
            $request['date_finish'] = $request['received'];

        unset($reserve['email'], $reserve['firstDateStart'], $reserve['copyBook']);

        return $this->update("reserves", $reserve, $where);
    }

    /**
     * This method is common and insert an a default reserve.
     * @param array $request *Description*: contains all default reserve data.
     * @return bool *Description*: if insert is success or not.
     */
    protected function insertDeffaultReserve($request)
    {
        if($this->existsReserve($request['user'], $request['isbn']))
            return false;

        if(isset($request['days_reserve']))
        {
            if($request['days_reserve'] > 0)
                $days_reserve = ($request['days_reserve']-1);

            else
                $days_reserve = ($this->DEFAULT_DAYS_RESERVE-1);

            unset($request['days_reserve']);
        }
        else
            $days_reserve = ($this->DEFAULT_DAYS_RESERVE-1);

        $request['date_start'] = date('Y-m-d');
        $request['date_finish']= date('Y-m-d', strtotime ('+'.$days_reserve.' day', strtotime($request['date_start'])));

        /**
         * if it's now possible, insert reserve
         */

        $copyBookAvailable = mysqli_fetch_assoc
        (
            $this->getBookDisponibility
            (
                $request['isbn'],
                $request['date_start'],
                $request['date_finish']
            )
        )['id'];

        if(count($copyBookAvailable) > 0)
        {
            unset($request['isbn']);
            $request['copybook'] = $copyBookAvailable;
            $this->insert("reserves",$request);
            return true;
        }
        unset($copyBookAvailable);

        /**
         * Insert reserve as soon possible
         */

        $betterAvailability = array();          //Contains possible reserves between two reserves

        $copyBooks = $this->getArrayToResult    //Get all copies of the isbn
        (
            $this->select
            ("
                SELECT id
                FROM copybooks
                WHERE book = '".$request['isbn']."'
            ")
        );


        /**
         * Travel all copies and all reserves of copies
         */
        foreach($copyBooks as $copy)
        {
            $sentinel = true;
            $lastDate = "";
            $reserves = $this->select
            ("
                SELECT date_start, date_finish
                FROM reserves
                WHERE
                    copybook = '".$copy."'
                AND
                    date_finish > ".str_replace("-","",date('Y-m-d'))."
                ORDER BY date_start
            ");

            while($reserve = mysqli_fetch_assoc($reserves) and $sentinel)
            {
                if($lastDate == "")
                    $lastDate = date('Y-m-d', strtotime($reserve['date_finish'].' +1 days'));
                else
                {
                    $actualDate = date('Y-m-d', strtotime($reserve['date_start'].' -1 days'));
                    if($this->getDateDifference($lastDate, $actualDate) >= $days_reserve)
                    {
                        $betterAvailability[$copy] = $lastDate;
                        $sentinel = false;
                    }
                    else
                    {
                        $lastDate = date('Y-m-d', strtotime($reserve['date_finish'].' +1 days'));
                    }
                }
            }
        }
        unset($copyBooks);

        /**
         * Get the last reserve of the copies
         */
        $lastDateFinishReserves = $this->select
        ("
            SELECT copybook, MAX(date_finish) as date_finish
            FROM reserves
            JOIN copybooks ON copybook = id
            WHERE book = '".$request['isbn']."'
            GROUP BY copybook
        ");

        while($reserve = mysqli_fetch_assoc($lastDateFinishReserves))
        {
            if(!isset($betterAvailability[$reserve['copybook']]))
            {
                $betterAvailability[$reserve['copybook']] =  date('Y-m-d', strtotime($reserve['date_finish'].' +1 days'));
            }
        }

        $betterReserve = array();

        /**
         *  Travel all possibles reserves and get the closest
         */
        foreach($betterAvailability as $copy => $better)
        {
            if(!isset($betterReserve['copybook']) || $this->getDateDifference($betterReserve['date_start'], $better) < 0)
            {
                $betterReserve['copybook'] = $copy;
                $betterReserve['date_start'] = $better;
            }
        }

        $request['copybook'] = $betterReserve['copybook'];
        $request['date_start'] = $betterReserve['date_start'];
        $request['date_finish']= date('Y-m-d', strtotime($betterReserve['date_start'].' + '.$days_reserve.' days'));
        unset($request['isbn']);

        return $this->insert("reserves",$request);

    }

    /**
     * This method is common and insert an a personalized reserve.
     * @param array $reserve *Description*: contains all data of the personalized reserve.
     * @return bool *Description*: if insert is success or not.
     */
    protected function insertPersonalizedReserve($reserve)
    {
        if($this->existsReserve($reserve['user'], $reserve['isbn']))
            return false;


        $reserve = $this->getReserveToformatValid($reserve);
        if($reserve == false){
            return false;
        }


        $reserve['copybook'] = mysqli_fetch_assoc
        (
            $this->getBookDisponibility
            (
                $reserve['isbn'],
                $reserve['date_start'],
                $reserve['date_finish']
            )
        )['id'];

        unset($reserve['isbn']);
        return $this->insert("reserves", $reserve);
    }


    /**
     * This function is common and return the reserve to valid format.
     * @param array $reserve *Description*: contains all data of reserve.
     * @return object  *Description*: return a reserve in a valid format.
     */
    protected function getReserveToformatValid($reserve)
    {

        $difStartCurrent = $this->getDateDifference(date('Y-m-d'), $reserve['date_start']);
        if($difStartCurrent < 0)
            $reserve['date_start'] = date('Y-m-d');


        $difStartFinish = $this->getDateDifference($reserve['date_start'], $reserve['date_finish']);
        if($difStartFinish < 0){
            return false;
        }

        if($difStartFinish > $this->MAX_DAYS_RESERVE)
        {
            $substract = $this->MAX_DAYS_RESERVE - $difStartFinish;
            $reserve['date_finish'] = date('Y-m-d', strtotime($reserve['date_finish'].$substract.' days'));
        }

        return $reserve;
    }

    /**
     * This method check if the user already have one reserve of this book.
     * @param string $user *Description*: contains the email of user.
     * @param string $isbn *Description*: contains the isbn of the book.
     * @return bool *Description*: if the user has already reserve of this book or not.
     */
    protected function existsReserve($user, $isbn)
    {
        $existsReserve = mysqli_fetch_assoc($this->select
        ("
            SELECT copybook
            FROM reserves
            JOIN copybooks ON copybook = id
            WHERE
                user =  '".$user."'
            AND
                book =  '".$isbn."'
            AND
                date_finish > ".str_replace("-","",date('Y-m-d'))."
         "));


        if(count($existsReserve) > 0)
            return true;

        else
            return false;
    }

    /**
     * This method obtains all books copies that is available.
     * @param string $isbn *Description*: contains book isbn.
     * @param string $dateStart *Description*: contains the start date.
     * @param string $dateFinish *Description*: contains the finish date.
     * @return array *Description*: all books copies available.
     */
    protected function getBookDisponibility($isbn, $dateStart, $dateFinish){

        $dateFinish = str_replace("-","",$dateFinish);
        $dateStart  = str_replace("-","",$dateStart);


        return $this->select
        ("
                SELECT id
                FROM reserves RIGHT JOIN copybooks ON copybook = id
                WHERE book = '".$isbn."' AND
                id NOT IN
                (
                    SELECT copybook
                    FROM reserves JOIN copybooks ON copybook = id
                    WHERE book = '".$isbn."' AND
                    (
                        ('". $dateStart ."' < date_start AND '". $dateFinish ."' > date_finish)
                    OR
                        '". $dateStart ."' BETWEEN date_start AND date_finish
                    OR
                        '". $dateFinish ."' BETWEEN date_start AND date_finish
                    )
                )
                ORDER BY status DESC
        ");
    }

    /**
     * This method calculate the difference of two dates.
     * @param string $dateStart *Description*: contains the start date.
     * @param string $dateFinish *Description*: contains the finish date.
     * @return int *Description*: the difference to the dates.
     */
    protected function getDateDifference($dateStart, $dateFinish)
    {
        $datetime1 = new DateTime($dateStart);
        $datetime2 = new DateTime($dateFinish);

        return $datetime2->diff($datetime1)->format('Y-m-d');
    }

    /**
     * This method transform mysqli object on a simple array
     * @param array $result *Description*: is an mysqli object.
     * @return array *Description*: simple array
     */
    protected function getArrayToResult($result){

        $array = array();

        while($p = mysqli_fetch_assoc($result))
            foreach($p as $value)
            {
                array_push($array,$value);
            }


        return $array;
    }

    /**
     * This method return the unknown array key of the know value.
     * @param array $array *Description*: contains the key to search.
     * @param string $value *Description*: contains the know value.
     * @return string *Description*: key of the value.
     */
    protected function getKeyToValue($array, $value){

        foreach($array as $k => $v)
        {
            if($v == $value)
                return $k;
        }
        return "";
    }

    /**
     * Transform array on a sql select string.
     * @param array $array *Description*: contains the values to transform.
     * @return string *Description*: simple select string. (Ejem: books AS 'Books', user AS 'User',...)
     */
    protected function getQueryNamesFormat($array){

        $nameFormat = "";

        foreach($array as $key => $value)
            $nameFormat .= $key." AS '".$value."', ";


        $nameFormat = trim($nameFormat,", ");
        return $nameFormat;
    }

    /**
     * This method generate sql LIKE sentence.
     * @param array $filterData *Description*: array to contains the columns to search.
     * @param string $search *Description*: value to search.
     * @param string $type *Description*: if not contains string,
     * generate "AND" search else, insert options "WHERE" or "OR".
     * @return string *Description*: contains the sentence.
     */
    protected function getSearchLikeFormat($filterData, $search, $type = ""){

        if($type == "")
            $type = "AND";

        $sentence =  " ".$type." ( ";

        foreach($filterData as $key =>$value)
        {
            if(!strpos($key,'(') && !strpos($key,'()'))
                $sentence .= " LOWER(".$key.")   LIKE LOWER('%" . $search . "%') OR";
        }

        $sentence = trim($sentence, "OR");

        $sentence .= ")";

        return $sentence;
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
            stylesUser::menuTop
            (
                $this->nameUser,
                $this->sid
            )
        );

        $this->setMenuContent
        (
            stylesUser::menuContent
            (
                $this->sid
            )
        );

        if($this->getContent() == "")
            $this->showError("ERROR: action not found");

        return utf8_encode($this->html());
    }

}