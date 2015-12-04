<?php

class User extends Template{

    use DBController;

    protected $DEFAULT_DAYS_RESERVE = 20;
    protected $MAX_DAYS_RESERVE     = 60;

    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
    }



    public function showReserves(){

        $_SESSION['menu']="Reserves";



        $reserves = $this->select(
            "
                SELECT isbn AS  'ISBN', title AS  'Title', author AS  'Author', category AS  'Category', date_start AS  'Start', date_finish AS  'End', sent AS  'Sent', received AS  'Received'
                FROM (reserves LEFT JOIN copybooks ON copybook = id)
                  LEFT JOIN books ON book = isbn
                WHERE user =  '".$this->emailUser."'
            ");
        $this->close();

        $this->setContent($this->getTable($reserves, true, true, "Reserve","",$this->emailUser,true,false));
    }

    public function showBooks($category = "", $search = ""){

        $_SESSION['menu']="Books";

        $sentence = "SELECT isbn, title, description, category, author FROM books where isbn in (select book from copybooks group by book)";

        if($category != "" && $category != "*") {
            $sentence .= " AND category='" . $category . "'";
        }
        elseif($search != "") {
            $sentence .= $this->getSubSentenceSearch("AND", $search);
        }

        $content = "";

        $filterData = $this->getArrayToResult($this->select("SELECT category FROM books GROUP BY category ORDER BY category"));
        $content .= $this->styleFilterMenu("Select Category","All",$filterData,"ISBN Title Author...","showBooks");

        $books = $this->select($sentence);

        while($book = $books->fetch_assoc()){
            $content .= $this->styleBooks($book);
        }

        $books->close();
        $this->close();
        $this->setContent($content);

    }

    public function showBook($isbn)
    {

        $_SESSION['menu']="Books";

        $book = $this->select("SELECT isbn, title, summary, category, author FROM books WHERE isbn = ".$isbn)->fetch_assoc();

        $this->setContent($this->styleBooksMenu().$this->styleBook($book));
    }

    public function logOut()
    {
        session_destroy();

        if(isset($_COOKIE['email'], $_COOKIE['pwd'])){
            setcookie("email", "", 0, "/");
            setcookie("pwd"  , "", 0, "/");
        };
    }


    protected function getTable($data, $edit = false, $drop = false, $typeObject = "", $primaryKey = "", $valuePrimaryKey = "", $reserveDelimiter = false, $info = false)
    {

        /**
         * @var $contentThead: This save the <thead> content
         * @var $objectNumber: This save the number of current object
         * @var $contentTbody: This save the <tbody> content
         * @var $thead: This is a sentinel, controls if it has been inserted the <thead>
         */
        $contentThead   = "<th>#</th>";
        $objectNumber   = 1;
        $contentTbody   = "";
        $thead          = true;
        $stateEdit      = "";
        $stateDelete    = "";
        $valueLink      = "";




        ($valuePrimaryKey)? $valueLink = $valuePrimaryKey : $valueLink = "";

        /**
         * Keeps track of each row
         */
        while ($object = $data->fetch_assoc())
        {
            $contentTr = "";

            foreach ($object as $column => $value)
            {
                if ($thead)
                {
                    $contentThead .= "<th>" . $column . "</th>";
                }
                    $contentTr      .= '<td title="'.$column.'">' . $value  . '</td>';
            }

            $thead = false;

            $contentTbody .=
            '    <tr>

                    <th scope="row">'
                        . $objectNumber .
                    '</th>'

                    . $contentTr;


            if($reserveDelimiter
              && is_null($object['Received'])
              && date_create($object['Start'])->diff(date_create($object['End']))->format("d") < $this->MAX_DAYS_RESERVE )
            {
                $stateEdit = "";
            }
            else
            {
                $stateEdit = "not-active";
            }


            if($reserveDelimiter
              && is_null($object['Sent']) && is_null($object['Received'])
              && date_create($object['Start'])->diff(date_create($object['End']))->format("d") < $this->MAX_DAYS_RESERVE )
            {
                $stateDelete = "";
            }
            else
            {
                $stateDelete = "not-active";
            }



            ($primaryKey != "")? $valueLink = $primaryKey : NULL;


            if($info){
                $contentTbody .=
                    '
                    <td>
                        <a href="controller.php?method=showInfo' . $typeObject . '&primaryKey=' . $valueLink . '" title="Show more info">
                            <i class="fa fa-info-circle"></i>
                        </a>
                    </td>
                ';
            }


            if($edit) {
                $contentTbody .=
                    '
                    <td>
                        <a href="controller.php?method=showEdit' . $typeObject . '&primaryKey=' . $valueLink . '" title="Edit" class="'.$stateEdit.'">
                            <span class="glyphicon glyphicon-edit"></span>
                        </a>
                    </td>
                    ';
             }


            if($drop) {
                $contentTbody .=
                '
                    <td>
                        <a href="controller.php?delete=' . $typeObject . '&primaryKey=' . $valueLink . '" title="Delete" class="'.$stateDelete.'">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </td>
                ';
            }

            $contentTbody .=
                 '
                  </tr>
                ';


            $objectNumber++;
        }


        if($info){
            $contentThead .=
                "
                <th>
                    Info
                </th>
            ";
        }

        if($edit) {
            $contentThead .=
                "
                <th>
                    Edit
                </th>
            ";
        }

        if($drop) {
            $contentThead .=
                "
                <th>
                    Remove
                </th>
            ";
        }


        return '
        <table class="table table-striped">
            <thead>
                <tr>
                    ' . $contentThead . '
                </tr>
            </thead>
            <tbody>
                ' . $contentTbody . '
            </tbody>
        </table>
        ';
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
    protected function styleFilterMenu($nameFilter, $filterDefault, $filterData, $placeHolderSearch, $method){

        $filter = "";

        foreach ($filterData as $data){
            $filter .=
                '
                        <li>
                            <a href="controller.php?method='.$method.'&category='.$data.$this->sid.'">
                                '.$data.'
                            </a>
                        </li>
                    ';
        }


        $filter .=
            '
                <li role="separator" class="divider">
                <li>
                    <a href="controller.php?method='.$method.'&category=*'.$this->sid.'">
                        '.$filterDefault.'
                    </a>
                </li>
            ';

        return
            '
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="options-books">
                        <div class="btn-group sub-menu" id="btn-category">

                            <button class="btn btn-default active btn-md dropdown-toggle" >
                                '.$nameFilter.'
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">

                                '.$filter.'

                            </ul>

                        </div>


                        <form action="controller.php?method='.$method.'" method="POST" id="books-search">

                            <div class="input-group">

                                <input type="search" name="search" class="form-control" placeholder="'.$placeHolderSearch.'" required="">
                                <span class="input-group-addon icons"><i class="fa fa-search"></i></span>
                            </div>
                        </form>
                    </div>
                ';
    }
    protected function getSubSentenceSearch($sentenceType, $valueSearch){

        return
            "
                  ".$sentenceType." (LOWER(author) LIKE LOWER('%" . $valueSearch . "%')
                   OR LOWER(title) LIKE LOWER('%" . $valueSearch . "%')
                   OR LOWER(isbn) LIKE LOWER('%" . $valueSearch . "%'))";

    }


    private function styleBook($book){
        return
            '
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div-details-books">

                        <div id="img-details-books">

                            <img class="details-img" src="../img/books/'.$book["isbn"].'.jpg"/>

                            <label class="label label-default">ISBN: <label id="book-isbn">'.$book["isbn"].'</label></label>
                            <br><br>
                            <label class="label label-default">Category: '.$book["category"].'</label>
                            <br><br>
                            <label class="label label-default">Author: '.$book["author"].'</label>

                        </div>

                        <div id="text-details-books">


                            <h1>'.$book["title"].'</h1>


                            <p>'.$book["summary"].'</p>

                            <div id="options-reserves">

                                <a class="btn btn-primary" id="btn-reserve-20-days" title="Reserve as soon as possible">Reserve '.$this->DEFAULT_DAYS_RESERVE.' days</a>

                                <button class="btn btn-default active" id="btn-personalized-reserve" title="Personalized my reserve">
                                    <span>Personalized reserve
                                        <span class="caret"></span>
                                    </span>
                                </button>

                            </div>

                            <div class="hidden" id="personalized-reserve">

                                <form action="#" method="post" onsubmit="return checkReserveDisponibility()">

                                    <div class="input-group" id="start-date-reserve-personalized">
                                        <span class="input-group-addon icons"><i class="fa fa-calendar-plus-o"></i></span>
                                        <input type="date" class="form-control" placeholder="dd/mm/aaaa" name="date-start" id="date-start" required="">
                                    </div>
                                    <div class="input-group" id="finish-date-reserve-personalized">
                                        <span class="input-group-addon icons"><i class="fa fa-calendar-times-o"></i></span>
                                        <input type="date" class="form-control" placeholder="dd/mm/aaaa" name="date-finish" id="date-finish" required="">
                                    </div>

                                    <label class="label label-danger hidden" id="label-error-personalized-reserve">The reserve is not available</label>
                                    <br>
                                    <button type="submit" class="btn btn-default">Reserve</button>
                                </form>

                            </div>

                        </div>
                    </div>
            ';
    }

    private function styleBooks($book){

        return
            '
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 show-books">
                <div>
                    <a href="controller.php?method=showBook&isbn='.$book["isbn"].'" title="Show details">
                        <div class="img-show-books">
                            <img src="../img/books/'.$book["isbn"].'.jpg" alt="Img of '.$book["title"].'">
                        </div>
                        <div class="data-show-books">
                            <div class="content-data-show-books">
                                <h2>'.$book["title"].'</h2>

                                <p class="listado">'.$book["description"].'</p>
                                <br>
                                <label class="label label-default">Category: '.$book["category"].'</label>
                                <br><br>
                                <label class="label label-default">Author: '.$book["author"].'</label>

                            </div>
                            <a href="#" class="btn btn-primary button-show-books" title="Reserve as soon as possible">Reserve '.$this->DEFAULT_DAYS_RESERVE.' days</a>
                        </div>
                    </a>
                </div>
            </div>
        ';

    }

    private function myUserMenuTop(){
        return
        '
            <span class="dropdown-toggle">
                ' . $this->nameUser . '
                <span class="caret"></span>
            </span>
            <ul class="dropdown-menu">
                <li>
                    <a href="#">
                        My profile
                    </a>

                </li>
                <li>
                    <a href="#">
                        Configuration
                    </a>
                </li>
            </ul>
        ';
    }

    private function myContentMenu(){

        $books = "default";
        $reserves = "default";

        if(isset($_SESSION['menu']))
        {
            switch($_SESSION['menu'])
            {

                case "Books":

                    $books = "primary active";
                    break;

                case "Reserves":

                    $reserves = "primary active";
                    break;
            }
        }
        return
        '
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <a href="controller.php?method=showBooks'.$this->sid.'" class="btn btn-lg btn-'.$books.' btn-menu" title="Show books">
                    Books
                </a>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 border-left" title="Show my reserves">
                <a href="controller.php?method=showReserves" class="btn btn-'.$reserves.' btn-lg btn-menu">
                    My reserves
                </a>
            </div>
        ';
    }

    public function __toString()
    {
        $this->setUserMenuTop($this->myUserMenuTop());
        $this->setContentMenu($this->myContentMenu());
        if($this->getContent() == "")
            $this->showError("ERROR: action not found");

        return utf8_encode($this->html());
    }
}