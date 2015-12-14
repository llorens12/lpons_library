<?php
/**
 * Class Template, this class is the base of the structure with page
 */
class Template
{
    /**
     * @var string $nameProject *Description*: this var contains the name of the project.
     */
    protected $nameProject = "Alaior Library";

    /**
     * @var string $nameUser *Description*: contains the name of the user.
     * @var string $emailUser *Description*: contains the email of user.
     * @var string $home *Description*: contains the url at home.
     * @var string $sid *Description*: contains the session id.
     */
    protected $nameUser, $emailUser, $home, $sid;

    /**
     * @var bool $includeSection *Description*: if is true, add section in the main, else not.
     * @var string $textButton *Description*: contains the text of the right top button.
     * @var string $linkButton *Description*: contains the url of the right top button.
     * @var string $linkTerms *Description*: contains the url of the bottom link Terms&Conditions
     * @var string $linkContact *Description*: contains the url of the bottom link Contact.
     */
    protected $includeSection, $textButton, $linkButton, $linkTerms, $linkContact;

    /**
     * @var string $contentMenu *Description*: contains the user menu.
     */
    private   $contentMenu = "";

    /**
     * @var string $userMenuTop *Description*: contains the options of the right top menu.
     */
    private   $userMenuTop = "";

    /**
     * @var string $content *Description*: contains the content web page.
     */
    private   $content = "";


    /**
     * Template constructor.
     *
     * *Description*: this object is the base of the web page, contains the base of web page.
     *
     * @param string $nameUser *Description*: contains the name of the user.
     * @param string $emailUser *Description*: contains the email of the user.
     * @param string $home *Description*: contains the url home of this user.
     * @param $sid  *Description*: contains the session id of the user.
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



        $this->linkTerms         = "#";
        $this->linkContact       = "#";


    }



    /**
     * This method contains the all web page.
     * @return string *Description*: return all web page.
     */
    protected function html()
    {
        return
            '<html lang="en">' .
            $this->head() .
            $this->body() .
            '</html>';
    }

    /**
     * This method contains the head of web page.
     * @return string *Description*: return the web page head.
     */
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

    /**
     * This method contains all styles of the body.
     * @return string *Description*: return the web page body.
     */
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

    /**
     * This method contains the nav bar.
     * @return string *Description*: return the nav bar.
     */
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

    /**
     * This method contains all styles of tag main.
     * @return string *Description*: return the web page main.
     */
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

    /**
     * This method contains the web page section.
     * @return string *Description*: return the web page section.
     */
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

    /**
     * This method contains the footer of web page.
     * @return string *Description*: return the web page footer.
     */
    private function footer()
    {
        return
            '
            <footer class="navbar navbar-inverse text-center">
                <p class="col-lg-4 col-md-4 col-sm-4 col-xs-12 navbar-text">
                    Signed in as
                    <a href="controller.php?method=showMyProfile" class="navbar-link">
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
     *  This method add content menu of the web page.
     * @param string $contentMenu *Description*: set the content menu.
     * @void method.
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
     * This method add the top right menu, this menu contains the options of my profile.
     * @param string $userMenuTop *Description*: set the value  menu top.
     * @void method.
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
     * This method return the var content.
     * @return string *Description*: return var content.
     */
    protected function getContent()
    {
        return $this->content;
    }

    /**
     * This method set the web page content.
     * @param string $content *Description*: set value of var content.
     * @void method.
     */
    protected function setContent($content)
    {
        $this->content = $content;
    }


    /**
     * This method send an error at the web page content.
     * @param string $error *Description*: contains the text of error.
     * @void method.
     */
    public function showError($error = "Has occurred an error, please try again"){
        $this->content = "<h1 class='text-center'><span class='label label-danger'>".$error."</span></h1>";
    }

    /**
     * Override the parent method, this insert the content of web page and close the connection of Database.
     * @return string *Description*: return all content web page.
     */
    public function __toString()
    {
        return utf8_encode($this->html());
    }

}