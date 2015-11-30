<?php

function showTable($data)
{
    /**
     * This save the <thead> content
     */
    $contentTheadTr="<th>#</th>";

    /**
     * This save the number of current object
     */
    $objectNumber = 0;

    /**
     * This save the <tbody> content
     */
    $contentTbody="";

    /**
     * This is a sentinel, controls if it has been inserted the <thead>
     */
    $thead=true;


    /**
     * Keeps track of each row
     */
    while($object = $data->fetch_assoc())
    {
        $contentTr="";
        foreach($object as $column => $value)
        {
            if($thead) $contentTheadTr .= "<th>".$column."</th>";
            $contentTr .= "<td>".$value."</td>";

        }
        $thead = false;
        $contentTbody .=
            '<tr>'.

            '<th scope="row">'
            .$objectNumber.
            '</th>'

            .$contentTr.

            '</tr>';
        $objectNumber++;
    }

    return '
        <table class="table table-striped">
            <thead>
                <tr>
                  '.$contentTheadTr.'
                </tr>
            </thead>
            <tbody>
                '.$contentTbody.'
            </tbody>
        </table>
    ';
}


function showBooks()
{

}