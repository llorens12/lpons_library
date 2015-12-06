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

        while($book = $books->fetch_assoc()){
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
                $this->select
                ("
                    SELECT isbn, title, summary, category, author
                    FROM books
                    WHERE isbn = ".$isbn
                )->fetch_assoc()
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


    protected function getArrayToResult($result){

        $array = array();

        while($p = $result->fetch_row())
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
                $this->nameUser
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