<?php

trait DBController{

    private $connection;


    protected  function startConnection()
    {
        if($this->connection == NULL) {
            $this->connection = new mysqli("localhost", "root", "", "lpons_library");
        }
    }

    protected function select($sentence)
    {
        $this->startConnection();
        $return = $this->connection->query($sentence);
        return $return;
    }

    protected function insert($table, $data)
    {
        $this->startConnection();
        $columns = "";
        $valuesColumns ="";

        foreach($data as $column => $value){
            $columns .= $column.", ";
            $valuesColumns .= "'".$value."', ";
        }

        $columns = trim($columns, ", ");
        $valuesColumns = trim($valuesColumns, ", ");

        $return = $this->connection->query
        ("
            INSERT INTO {$table} ({$columns})
            VALUES ({$valuesColumns});
        ");

        return $return;
    }

    protected function update($table, $data, $primaryKey, $valuePrimaryKey)
    {
        $this->startConnection();
        $set="";

        foreach($data as $column => $value){
            $set .= $column."=".$value.", ";
        }

        $set = trim($set, ", ");

        $return = $this->connection->query
        ("
            UPDATE {$table}
            SET    {$set}
            WHERE  {$primaryKey} = {$valuePrimaryKey};
        ");


        return $return;
    }

    protected function delete($table, $where)
    {
        $this->startConnection();
        $return = $this->connection->query
        ("
            DELETE FROM {$table}
            WHERE  {$where};
        ");


        return $return;
    }

    protected function close(){
        $this->connection->close();
    }
}