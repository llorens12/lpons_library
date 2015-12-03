<?php

class User extends Template{
    use DBController;

    protected $DEFAULT_DAYS_RESERVE = 20;

    public function __construct($nameUser, $emailUser, $typeUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $typeUser, $home, $sid);
    }


    protected function tableStyle($data, $edit = true, $info = false)
    {

        /**
         * @var $contentTheadTr: This save the <thead> content
         * @var $objectNumber: This save the number of current object
         * @var $contentTbody: This save the <tbody> content
         * @var $thead: This is a sentinel, controls if it has been inserted the <thead>
         */
        $contentTheadTr = "<th>#</th>";
        $objectNumber   = 1;
        $contentTbody   = "";
        $thead          = true;
        $editContentTr  ="";


        if($edit)
            $editContentTr =
            "
                <td>
                    <a href=\"#\">
                        <span class=\"glyphicon glyphicon-edit\"></span>
                    </a>
                </td>

                <td>
                    <a href=\"#\">
                        <span class=\"glyphicon glyphicon-remove\"></span>
                    </a>
                </td>
            ";


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
                    $contentTheadTr .= "<th>" . $column . "</th>";
                }
                    $contentTr      .= "<td>" . $value  . "</td>";
            }

            $thead = false;
            $contentTbody .=
            '    <tr>

                    <th scope="row">'
                        . $objectNumber .
                    '</th>'

                    . $contentTr
                    . $editContentTr .

                 '</tr>
            ';
            $objectNumber++;
        }


        if($edit)
            $contentTheadTr .=
            "
                <th>
                    Edit
                </th>
                <th>
                    Remove
                </th>
            ";


        $this->setContent( '
        <table class="table table-striped">
            <thead>
                <tr>
                    ' . $contentTheadTr . '
                </tr>
            </thead>
            <tbody>
                ' . $contentTbody . '
            </tbody>
        </table>
    ');
    }

    public function showBooks($category = "", $search = ""){

        $_SESSION['menu']="Books";

        $sentence = "SELECT isbn, title, description, category, author FROM books";

        if($category != "" && $category != "*") {
            $sentence .= " WHERE category='" . $category . "'";
        }
        elseif($search != "") {
            $sentence .=
                "
                  WHERE LOWER(author) LIKE LOWER('%" . $search . "%')
                   OR LOWER(title) LIKE LOWER('%" . $search . "%')
                   OR LOWER(isbn) LIKE LOWER('%" . $search . "%')";
        }

        $content = "";


        $content .= $this->booksMenuStyle();
        $books = $this->select($sentence);

        while($book = $books->fetch_assoc()){
            $content .= $this->booksStyle($book);
        }

        $books->close();
        $this->close();
        $this->setContent($content);

    }

    public function showBook($isbn)
    {

        $_SESSION['menu']="Books";

        $book = $this->select("SELECT isbn, title, summary, category, author FROM books WHERE isbn = ".$isbn)->fetch_assoc();

        $this->setContent($this->bookStyle($book));
    }

    private function bookStyle($book){
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

                                <a class="btn btn-primary" id="btn-reserve-20-days">Reserve '.$this->DEFAULT_DAYS_RESERVE.' days</a>
                                <button class="btn btn-default active" id="btn-personalized-reserve">
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

    private function booksMenuStyle(){

        $categories = "";

        $result = $this->select("SELECT category FROM books GROUP BY category ORDER BY category");


        while ($category = $result->fetch_assoc()){
            $categories .=
                '
                        <li>
                            <a href="controller.php?method=showBooks&category='.$category['category'].$this->sid.'">
                                '.$category['category'].'
                            </a>
                        </li>
                    ';
        }

        $result->close();

        $categories .=
            '
                <li role="separator" class="divider">
                <li>
                    <a href="controller.php?method=showBooks&category=*'.$this->sid.'">
                        All
                    </a>
                </li>
            ';

        return
            '
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="options-books">
                        <div class="btn-group sub-menu" id="btn-category">

                            <button class="btn btn-default active btn-md dropdown-toggle" >
                                Select Category
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">

                                '.$categories.'

                            </ul>

                        </div>


                        <form action="controller.php?method=showBooks" method="POST" id="books-search">

                            <div class="input-group">

                                <input type="search" name="search" class="form-control" placeholder="ISBN Title Author..." required="">
                                <span class="input-group-addon icons"><i class="fa fa-search"></i></span>
                            </div>
                        </form>
                    </div>
                ';
        $this->close();
    }

    private function booksStyle($book){

        return
            '
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 show-books">
                <div>
                    <a href="controller.php?method=showBook&isbn='.$book["isbn"].'">
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
                            <a href="#" class="btn btn-primary button-show-books">Reserve '.$this->DEFAULT_DAYS_RESERVE.' days</a>
                        </div>
                    </a>
                </div>
            </div>
        ';

    }

    public function logOut()
    {
        session_destroy();

        if(isset($_COOKIE['email'], $_COOKIE['pwd'])){
            setcookie("email", "", 0, "/");
            setcookie("pwd"  , "", 0, "/");
        };
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
                <a href="controller.php?method=showBooks'.$this->sid.'" class="btn btn-lg btn-'.$books.' btn-menu">
                    Books
                </a>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 border-left">
                <a href="#" class="btn btn-'.$reserves.' btn-lg btn-menu">
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