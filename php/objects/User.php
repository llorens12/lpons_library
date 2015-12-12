<?php

class User extends Template{

    use DBController;

    protected $DEFAULT_DAYS_RESERVE = 20;
    protected $MAX_DAYS_RESERVE     = 59;//The reserve is the 60 days because the 0 count

    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
    }




    public function showBook($isbn)
    {

        $_SESSION['menu']="Books";

        $this->setContent
        (
            stylesUser::filterMenu
            (
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
                        WHERE isbn = ".$isbn
                    )
                )
                ,
                $this->DEFAULT_DAYS_RESERVE,
                $this->sid
            )
        );
    }

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
                $this->MAX_DAYS_RESERVE,
                $this->sid
            )
        );
    }

    public function showMyProfile($error){

        $this->myProfile($error);
    }



    public function showEditReserve($request){

        $_SESSION['menu'] = "Reserves";

        if(!isset($request['email']))
            $email = $this->emailUser;

        else
            $email = $request['email'];

        $reserve = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT isbn, title, author, category, date_start, date_finish, copybook, sent
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
            stylesUser::contentFormEditReserves
            (
                $reserve,
                $_SESSION['typeUser'],
                (isset($request['error'])),
                $this->sid
            )
        );
    }



    public function setInsertDefaultReserve($request){

        $request['user'] = $_SESSION['email'];

        $returnament = $this->insertDeffaultReserve($request);

        if($returnament === true)
        {
            return true;
        }
        elseif($returnament === false)
        {
            $this->showError("It hasn't been possible perform the reserve");
        }
        else
            $this->showError($returnament);

        return false;
    }

    public function setInsertPersonalizedReserve($request)
    {
        $request['user'] = $_SESSION['email'];


        $returnament = $this->insertPersonalizedReserve($request);

        if($returnament === true)
        {
            return true;
        }
        elseif($returnament === false)
        {
            $this->showError("It hasn't been possible perform the reserve");
        }
        else
            $this->showError($returnament);

        return false;
    }



    public function setUpdateReserve($request){

        $request['email'] = $this->emailUser;
        return $this->updateReserve($request);
    }

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



    public function setDeleteReserve($request){
        $request['email'] = $this->emailUser;

        return $this->deleteReserve($request);
    }




    public function logOut()
    {
        session_destroy();

        if(isset($_COOKIE['email'], $_COOKIE['pwd'])){
            setcookie("email", "", 0, "/");
            setcookie("pwd"  , "", 0, "/");
        };
    }



    protected function myProfile($error){

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
                $error,
                "update",
                "setUpdateMyProfile"
            )
        );
    }

    protected function updateReserve($reserve){

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

        $select = $this->select($sentence);

        if(mysqli_num_rows($select) != 0)
            return false;

        $where =
        "
                user = '".$reserve['email']."'
            AND
                date_start = '".$reserve['firstDateStart']."'
            AND
                copybook = '".$reserve['copyBook']."'
        ";

        unset($reserve['email'], $reserve['firstDateStart'], $reserve['copyBook']);

        return $this->update("reserves", $reserve, $where);
    }

    protected function deleteReserve($request){

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

    protected function insertDeffaultReserve($request){

        $existsReserve = mysqli_fetch_assoc($this->select
        ("
            SELECT copybook
            FROM reserves
            JOIN copybooks ON copybook = id
            WHERE
                user =  '".$request['user']."'
            AND
                book =  '".$request['isbn']."'
            AND
                date_finish > ".str_replace("-","",date('Y-m-d'))."
         "));

        if(count($existsReserve) > 0){
            return "You have already reserved book";
        }
        unset($existsReserve);


        if(isset($request['days_reserve']))
        {
            $days_reserve = ($request['days_reserve']-1);
            unset($request['days_reserve']);
        }
        else
            $days_reserve = $this->DEFAULT_DAYS_RESERVE-1;

        $request['date_start'] = date('Y-m-d');
        $request['date_finish']= date('Y-m-d', strtotime ('+'.$days_reserve.' day', strtotime($request['date_start'])));

        /**
         * if it's now possible, insert reserve
         */

        $copyBookAvailable = mysqli_fetch_assoc($this->getBookDisponibility($request['isbn'], $request['date_start'],$request['date_finish']))['id'];

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

    protected function insertPersonalizedReserve($reserve)
    {
        $existsReserve = mysqli_fetch_assoc($this->select
        ("
            SELECT copybook
            FROM reserves
            JOIN copybooks ON copybook = id
            WHERE
                user =  '".$reserve['user']."'
            AND
                book =  '".$reserve['isbn']."'
            AND
                date_finish > ".str_replace("-","",date('Y-m-d'))."
         "));

        if(count($existsReserve) > 0){
            return "You have already reserved book";
        }
        unset($existsReserve);


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

    protected function getDateDifference($dateStart, $dateFinish){
        return str_replace("-", "", $dateFinish) - str_replace("-", "", $dateStart);
    }

    protected function getArrayToResult($result){

        $array = array();

        while($p = mysqli_fetch_assoc($result))
        {
            foreach($p as $value)
            {
                array_push($array,$value);
            }
        }

        return $array;
    }

    protected function getKeyToValue($array, $value){

        foreach($array as $k => $v){
            if($v == $value)
                return $k;
        }
        return "";
    }

    protected function getQueryNamesFormat($array){

        $nameFormat = "";

        foreach($array as $key => $value){
            $nameFormat .= $key." AS ".$value.", ";
        }

        $nameFormat = trim($nameFormat,", ");
        return $nameFormat;
    }

    protected function getSearchLikeFormat($filterData, $search, $type = ""){

        if($type == "")
            $type = "AND";

        $sentence =  " ".$type." ( ";

        foreach($filterData as $key =>$value){
            if(strpos($key,'(') || strpos($key,'()'))
                $sentence .= " LOWER(".$value.")   LIKE LOWER('%" . $search . "%') OR";

            else
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

        ($this->getContent() == "")?

            $this->showError("ERROR: action not found")
            :
            NULL;

        return utf8_encode($this->html());
    }
}