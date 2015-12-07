<?php

class User extends Template{

    use DBController;

    protected $DEFAULT_DAYS_RESERVE = 20;
    protected $MAX_DAYS_RESERVE     = 60;

    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
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


        if ($category != "" && $category != "*") {
            $sentence .= " ORDER BY '" . $this->getKeyToValue($filterData,$category) . "'";
        }

        elseif($search != "")
        {
            $sentence .=
            "
                AND
                (    LOWER(author)   LIKE LOWER('%" . $search . "%')
                OR   LOWER(title)    LIKE LOWER('%" . $search . "%')
                OR   LOWER(isbn)     LIKE LOWER('%" . $search . "%')
                OR   date_start      =           '" . $search . "'
                OR   date_finish     =           '" . $search . "'
                OR   sent            =           '" . $search . "'
                OR   received        =           '" . $search . "'
                )
            ";
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
                "Reserve",
                "",
                $this->emailUser,
                true,
                $this->MAX_DAYS_RESERVE
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

        if ($category != "" && $category != "*") {
            $sentence .= " AND category='" . $category . "'";
        }
        elseif($search != "") {
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
            "Select Category",
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

        while($book = mysqli_fetch_assoc($books)){
            $content .= stylesUser::books
            (
                $book,
                $this->DEFAULT_DAYS_RESERVE
            );
        }

        $this->setContent($content);
    }

    public function showBook($isbn)
    {

        $_SESSION['menu']="Books";

        $this->setContent
        (
            stylesUser::filterMenu
            (
                "Order by",
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
                "showReserves",
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
                $this->DEFAULT_DAYS_RESERVE
            )
        );
    }

    public function logOut()
    {
        session_destroy();

        if(isset($_COOKIE['email'], $_COOKIE['pwd'])){
            setcookie("email", "", 0, "/");
            setcookie("pwd"  , "", 0, "/");
        };
    }

    public function setPersonalizedReserve($request)
    {
        $request['user'] = $_SESSION['email'];
        if(!$this->insertPersonalizedReserve($request))
        {
            $this->showError("It hasn't been possible perform the reserve");
        }
    }

    public function setDefaultReserve($request){

        $request['user'] = $_SESSION['email'];
        if(!$this->insertDeffaultReserve($request))
        {
            $this->showError("It hasn't been possible perform the reserve");
        }

    }



    protected function insertDeffaultReserve($request){

        $days_reserve = 0;
        if(isset($request['days_reserve']))
        {
            $days_reserve = $request['days_reserve']-1;
            unset($request['days_reserve']);
        }
        else
            $days_reserve = $this->DEFAULT_DAYS_RESERVE-1;

        $request['date_start'] = date('Y-m-d');
        $request['date_finish']= date('Y-m-d', strtotime ('+'.$days_reserve.' day', strtotime($request['date_start'])));

        /**
         * if it's now possible, insert reserve
         */

        $copyBookAvailable = mysqli_fetch_assoc($this->getBookDisponibility($request['isbn'],$request['date_start'],$request['date_finish']))['id'];

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

        return $request;

    }

    protected function insertPersonalizedReserve($reserve)
    {

        $difStartCurrent = $this->getDateDifference(date('Y-m-d'), $reserve['date_start']);
        if($difStartCurrent < 0)
            $reserve['date_start'] = date('Y-m-d');


        $difStartFinish = $this->getDateDifference($reserve['date_start'], $reserve['date_finish']);
        if($difStartFinish < 0){
            $this->insertDeffaultReserve($reserve);
        }

        if($difStartFinish > $this->MAX_DAYS_RESERVE)
        {
            $substract = $this->MAX_DAYS_RESERVE - $difStartFinish;
            $reserve['date_finish'] = date('Y-m-d', strtotime($reserve['date_finish'].$substract.' days'));
        }


        $reserve['copybook'] = mysqli_fetch_assoc
        (
            $this->getBookDisponibility
            (
                $reserve['isbn'],
                $reserve['date_start'],
                $reserve['date_finish']
            )
        )['copybook'];

        unset($reserve['isbn']);
        return $this->insert("reserves",$reserve);

    }

    protected function getBookDisponibility($isbn, $dateStart,$dateFinish){

        $dateFinish = str_replace("-","",$dateFinish);
        $dateStart  = str_replace("-","",$dateStart);

        return $this->select
        ("
                select id
                from reserves RIGHT JOIN copybooks on copybook = id
                where book = '".$isbn."' AND
                id not in
                (
                    select copybook
                    from reserves JOIN copybooks on copybook = id
                    where book = '". $isbn ."' AND
                    (
                        ('". $dateStart ."' < date_start AND '". $dateFinish ."' > date_finish)
                    OR
                        '". $dateStart ."' between date_start and date_finish
                    OR
                        '". $dateFinish ."' between date_start AND date_finish
                    )
                )
                order by status desc
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