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
    private $nameProject = "Library";


    private $typeUser, $nameUser, $emailUser, $home, $currentOptionMenu, $sid;

    private $includeSection, $spanUser, $textButton, $linkButton, $linkUser, $linkTerms, $linkContact;

    private $css, $jquery, $myjs;

    protected $permission = false;
    private $content = "";


    /**
     * @param $typePage:                 Specific type of page (Common,Login,Register)
     * @param string $nameUser:          Name of User
     * @param string $emailUser:         E-mail of User
     * @param string $permision:         If is user, libraryan or Admin
     * @param string $currentOptionMenu: Actual section of menu
     */
    function __construct($nameUser = "", $emailUser = "Anonimous", $typeUser = "", $home = "index.php", $sid = "", $currentOptionMenu = "")
    {

        $this->nameUser          = $nameUser;
        $this->emailUser         = $emailUser;
        $this->typeUser          = $typeUser;
        $this->home              = $home;
        $this->sid               = $sid;
        $this->currentOptionMenu = $currentOptionMenu;


        $this->includeSection = true;
        $this->css            = "../../css/mycss.css";
        $this->jquery         = "../../js/jquery-2.1.4.min.js";
        $this->myjs           = "../../js/myjs.js";
        $this->spanUser       = Menus::userConfig($this->nameUser);
        $this->textButton     = "Log Out";
        $this->linkButton     = "../logout.php";



        $this->linkUser          = "#";
        $this->linkTerms         = "#";
        $this->linkContact       = "#";


    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
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
                <!-- Optional theme -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
                <!--http://fortawesome.github.io/Font-Awesome/icons/-->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

                <!-- Bootstrap -->
                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

                <!-- My css -->
                <link href="'.$this->css.'" rel="stylesheet">
                <!-- jQuery -->
                <script src="'.$this->jquery.'"></script>
                <!-- My js  -->
                <script src="'.$this->myjs.'"></script>

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

    public function showLogin()
    {
        $this->optionsLoginRegister();
        $this->textButton     = "Register";
        $this->linkButton     = "index.php?register=''";

        echo $this->html();
    }

    public function showRegister()
    {
        $this->optionsLoginRegister();
        $this->textButton     = "Log In";
        $this->linkButton     = "index.php";

        echo $this->html();
    }

    private function optionsLoginRegister()
    {
        $this->includeSection = false;
        $this->css            = "css/mycss.css";
        $this->jquery         = "js/jquery-2.1.4.min.js";
        $this->myjs           = "js/myjs.js";
        $this->spanUser       = "";
    }

    public function __toString()
    {
        if(!$this->permission)
            $this->setContent("<h2>Permission denied</h2>");

        return utf8_encode($this->html());
    }

}