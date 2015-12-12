<?php

class Ajax
{
    use DBController;

    public function email($email)
    {
        $answer = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT *
                FROM users
                WHERE email='" . $email . "'
            ")
        );
        $this->close();

        if (count($answer) == 0)
            echo "true";
        else
            echo "false";
    }

    public function reserveDisponibility($request)
    {

        $isbn = $request['isbn'];

        $dateFinish = str_replace("-", "", $request['dateFinish']);
        $dateStart = str_replace("-", "", $request['dateStart']);

        $result = $this->select
        ("
                SELECT id
                FROM reserves RIGHT JOIN copybooks ON copybook = id
                WHERE book = '" . $isbn . "' AND
                id NOT IN
                (
                    SELECT copybook
                    FROM reserves JOIN copybooks ON copybook = id
                    WHERE book = '" . $isbn . "' AND
                    (
                        ('" . $dateStart . "' < date_start AND '" . $dateFinish . "' > date_finish)
                    OR
                        '" . $dateStart . "' BETWEEN date_start AND date_finish
                    OR
                        '" . $dateFinish . "' BETWEEN date_start AND date_finish
                    )
                )
                ORDER BY status DESC
        ");
        if (count(mysqli_fetch_assoc($result)) > 0) {
            echo "true";
        } else {
            echo "false";
        }
    }


    public function book($request)
    {
        $answer = mysqli_fetch_assoc
        (
            $this->select
            ("
                SELECT title
                FROM books
                WHERE isbn='" . $request['isbn'] . "'
            ")
        );
        $this->close();

        if (count($answer) == 0)
            echo "true";
        else
            echo "false";
    }
}
