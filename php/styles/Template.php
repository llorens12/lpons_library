<?php
include_once "CommonStyles.php";

/**
 * Class Template: This class is the base of the structure with page
 */
class Template
{
    /**
     * @var string: This var contains the name of the project.
     */
    protected $nameProject = "Library";

    protected $typeUser, $nameUser, $emailUser, $home, $currentOptionMenu, $sid;

    protected $includeSection, $spanUser, $textButton, $linkButton, $linkUser, $linkTerms, $linkContact;

    protected $content = "";




    /**
     * @param $typePage:                 Specific type of page (Common,Login,Register)
     * @param string $nameUser:          Name of User
     * @param string $emailUser:         E-mail of User
     * @param string $permision:         If is user, libraryan or Admin
     * @param string $currentOptionMenu: Actual section of menu
     */
    function __construct($nameUser = "", $emailUser = "Anonimous", $typeUser = "", $home = "Anonimous.php", $currentOptionMenu = "", $sid = "")
    {

        $this->nameUser          = $nameUser;
        $this->emailUser         = $emailUser;
        $this->typeUser          = $typeUser;
        $this->home              = $home;
        $this->sid               = $sid;
        $this->currentOptionMenu = $currentOptionMenu;


        $this->includeSection = true;
        $this->spanUser       = Menus::userConfig($this->nameUser);
        $this->textButton     = "Log Out";
        $this->linkButton     = "../logout.php";



        $this->linkUser          = "#";
        $this->linkTerms         = "#";
        $this->linkContact       = "#";


    }

    private function html()
    {
        return
            '<html lang="en">' .
            $this->head() .
            $this->body() .
            '</html>';
    }

    private function head()
    {
        return '
            <head>

                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

                <title>
                    ' . $this->currentOptionMenu . '
                </title>
                <!-- Latest compiled and minified CSS -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
                <!--http://fortawesome.github.io/Font-Awesome/icons/-->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

                <!-- Bootstrap -->
                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

                <!-- My css -->
                <link href="../../css/mycss.css" rel="stylesheet">
                <!-- jQuery -->
                <script src="../../js/jquery-2.1.4.min.js"></script>
                <!-- My js  -->
                <script src="../../js/myjs.js"></script>

            </head>
        ';
    }

    private function body()
    {
        return
            '<body>

                <div id="body-separator">' .
                    $this->header() .
                    $this->main() .
                '</div>'.
                $this->footer() .

            '</body>';
    }

    private function header()
    {
        return
            '
            <header class="navbar navbar-inverse">

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                    <a type="link" id="link-logo" href="' . $this->home . '">
                        <h2 class="navbar-text">
                            ' . $this->nameProject . '
                        </h2>
                    </a>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs hidden-phone text-center" id="nav-btn-home">
                    <a class="btn btn-default navbar-btn" id="btn-home" href="' . $this->home . '">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-right">
                    ' . $this->spanUser . '
                    <a id="nav-btn-right" class="btn btn-default navbar-btn" href="' . $this->linkButton . '">
                        ' . $this->textButton . '
                    </a>
                </div>

            </header>
            ';
    }

    private function main()
    {
        $section = "";

        //Include or not section.
        if ($this->includeSection)
        {
            $section = $this->section();
        }
        //If not include section and user addContent, insert content this.
        else
        {
            $section = $this->content;
        }

        return
            '
            <main class="container row-centered" id="main-tag">'
                .$section .
            '</main>
            ';
    }

    private function section()
    {
        return
            '
            <section class="row row-centered">

                '.$this->menu().'

                <div class="row row-centered" id="content">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        '.$this->content.'
                    </div>

                </div>

            </section>
            ';
    }

    private function menu(){

        $contentMenu="";

        switch($this->typeUser){

            case "librarian":
                $contentMenu = Menus::menuLibrarian();
                break;
            case "user":
                break;

            case "admin":
                break;

            default:
                return "";
        }

        return '
            <div class="row row-centered">
                '.$contentMenu.'
            </div>
            <hr id="menu-separator">
            ';
    }

    private function footer()
    {
        return
            '
            <footer class="navbar navbar-inverse text-center">
                <p class="col-lg-4 col-md-4 col-sm-4 col-xs-12 navbar-text">
                    Signed in as
                    <a href="' . $this->linkUser . '" class="navbar-link">
                        ' . $this->emailUser . '
                    </a>
                </p>

                <p class="col-lg-4 col-md-4 col-sm-4 col-xs-12 navbar-text">
                    <a href="' . $this->linkTerms . '" class="navbar-link">
                        Terms & conditions
                    </a>
                </p>

                <p class="col-lg-4 col-md-4 col-sm-4 col-xs-12 navbar-text">
                    <a href="' . $this->linkContact . '" class="navbar-link">
                        Contact
                    </a>
                </p>
            </footer>
            ';
    }

    public function showError($error = "Has occurred an error, please try again"){
        $this->content = $error;
    }

    public function __toString()
    {
        return utf8_encode($this->html());
    }

}