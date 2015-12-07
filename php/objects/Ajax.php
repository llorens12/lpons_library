<?php

class Ajax
{
    use DBController;

    public function email($email)
    {
        $answer = $this->select
        ("
            select *
            from users
            WHERE email='".$email."'"
        )->fetch_assoc();
        $this->close();

        if(count($answer) == 0)
            echo "true";
        else
            echo "false";
    }

    public function reserveDisponibility($isbn,$dateStart,$dateFinish){

        $dateFinish = str_replace("-","",$dateFinish);
        $dateStart  = str_replace("-","",$dateStart);

        $result =$this->select
        ('
                select copybook
                from reserves JOIN copybooks on copybook = id
                where book = '.$isbn.' AND
                copybook not in
                (
                    select copybook
                    from reserves JOIN copybooks on copybook = id
                    where book = ' . $isbn . ' AND
                    (
                        (' . $dateStart . ' < date_start AND ' . $dateFinish . ' > date_finish)
                    OR
                        ' . $dateStart . ' between date_start and date_finish
                    OR
                        ' . $dateFinish . ' between date_start AND date_finish
                    )
                )



        ');

        if(count($result->fetch_row()) > 0)
        {
            return "true";
        }
        else
        {
            return "false";
        }
    }
}
