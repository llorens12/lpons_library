<?php

/**
 * Class Template: This class is the base of the structure with page
 */
class Template
{
    /**
     * @var string: This var contains the name of the project.
     */
    protected $nameProject = "Alaior Library";

    protected $nameUser, $emailUser, $home, $sid;

    protected $includeSection, $textButton, $linkButton, $linkUser, $linkTerms, $linkContact;

    private   $contentMenu = "";
    private   $userMenuTop = "";

    private   $content = "";

    /**
     * @param $typePage:                 Specific type of page (Common,Login,Register)
     * @param string $nameUser:          Name of User
     * @param string $emailUser:         E-mail of User
     * @param string $permision:         If is user, libraryan or Admin
     * @param string $currentOptionMenu: Actual section of menu
     */
    function __construct($nameUser, $emailUser, $home, $sid)
    {

        $this->nameUser          = $nameUser;
        $this->emailUser         = $emailUser;
        $this->home              = $home;
        $this->sid               = htmlspecialchars($sid);


        $this->includeSection = true;
        $this->textButton     = "Log Out";
        $this->linkButton     = "controller.php?method=logOut";



        $this->linkUser          = "#";
        $this->linkTerms         = "#";
        $this->linkContact       = "#";


    }

    protected function html()
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
                    ' . $this->nameProject . '
                </title>
                <!-- Latest compiled and minified CSS -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
                <!--http://fortawesome.github.io/Font-Awesome/icons/-->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


                <!-- My css -->
                <link href="../css/mycss.css" rel="stylesheet">
                <!-- jQuery -->
                <script src="../js/jquery-2.1.4.min.js"></script>
                <!-- My js  -->
                <script src="../js/myjs.js"></script>

                <!-- Bootstrap -->
                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

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
                    <a type="link" id="link-logo" href="'.$this->home.'" title="Go my home">
                        <h2 class="navbar-text">
                            ' . $this->nameProject . '
                        </h2>
                    </a>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs hidden-phone text-center" id="nav-btn-home">
                    <a class="btn btn-default navbar-btn" id="btn-home" href="'.$this->home.'"  title="Go my home">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-right">
                    ' . $this->userMenuTop . '
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

                '.$this->contentMenu.'

                <div class="row row-centered" id="content">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        '.$this->content.'
                    </div>

                </div>

            </section>
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

    /**
     * @param string $contentMenu
     */
    protected function setMenuContent($contentMenu)
    {
        $this->contentMenu =
            '
                <div class="row row-centered">
                    '.$contentMenu.'
                </div>
                <hr id="menu-separator">';
    }

    /**
     * @param string $userMenuTop
     */
    protected function setMenuTop($userMenuTop)
    {
        $this->userMenuTop =
        '
            <div class="btn-group  sub-menu" id="nameUser">
                '.$userMenuTop.'
            </div>
        ';
    }

    /**
     * @return string
     */
    protected function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    protected function setContent($content)
    {
        $this->content = $content;
    }



    public function showError($error = "Has occurred an error, please try again"){
        $this->content = "<h1 class='text-center'><span class='label label-danger'>".$error."</span></h1>";
    }

    public function __toString()
    {
        return utf8_encode($this->html());
    }

}