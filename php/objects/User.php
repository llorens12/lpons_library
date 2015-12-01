<?php
include_once "../styles/Template.php";
include_once "../trait/DBController.php";

class User extends Template{
    use DBController;

    public function __construct($nameUser, $emailUser, $typeUser, $home, $currentOptionMenu, $sid)
    {
        parent::__construct($nameUser, $emailUser, $typeUser, $home, $currentOptionMenu, $sid);
    }

    /**
     * @param $data
     * @param bool|true $edit
     */
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
}