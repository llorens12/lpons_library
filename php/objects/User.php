<?php

class User extends Template{
    use DBController;

    public function __construct($nameUser, $emailUser, $typeUser, $sid)
    {
        parent::__construct($nameUser, $emailUser, $typeUser, $sid);
        $this->setUserMenuTop($this->myUserMenuTop());
    }


    protected function showTable($data, $edit = true)
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


        $this->content = '
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
    ';
    }

    public function showBooks(){

        $_SESSION['menu']="Books";

        $m= '
        <a class="list-books" href="">

            <div class="img-book">
                <img src=""/>
            </div>
            <div class="content-book">
                <p class="title-book"></p>
                <p class="description-book"></p>
            </div>

        </a>


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

                    $books = "primary";
                    break;

                case "Reserves":

                    $books = "primary";
                    break;
            }
        }
        return
        '
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <a href="#" class="btn btn-lg btn-'.$books.' btn-menu">
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
        $this->setContentMenu($this->myContentMenu());
        return utf8_encode($this->html());
    }
}