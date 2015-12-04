<?php

class Librarian extends User{

    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
    }

    public function registerUser(){

    }

    protected function myContentMenu()
    {
        $users = "default";
        $books = "default";
        $reserves = "default";

        if(isset($_SESSION['menu']))
        {
            switch($_SESSION['menu'])
            {

                case "Users":

                    $users = "primary";
                    break;

                case "Books":

                    $books = "primary";
                    break;

                case "Reserves":

                    $books = "primary";
                    break;
            }
        }

        return '
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="btn-group btn-menu sub-menu">
                    <a class="btn btn-'.$users.' btn-lg  dropdown-toggle" >
                        Users
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#">
                                Show
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Morosos
                            </a>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="btn-group btn-menu sub-menu">
                    <a class="btn btn'.$books.' btn-lg  dropdown-toggle" >
                        Books
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#">
                                Show
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Administrate
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="btn-group btn-menu sub-menu">
                    <a class="btn btn'.$reserves.' btn-lg  dropdown-toggle" >
                        Reserves
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#">
                                My reserves
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Administrate
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            ';
    }

    public function showUsers()
    {
        $_SESSION['menu'] = "users";
        $this->getTable(
            $this->select("SELECT * FROM USERS")
        );

    }

    public function __toString()
    {
        $this->setContentMenu($this->myContentMenu());
        if($this->getContent() == "")
            $this->setContent("<h1 style='text-align: center'>ERROR: action not found</h1>");
        return utf8_encode($this->html());
    }
}