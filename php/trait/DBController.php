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
        return $this->connection->query($sentence);
    }

    protected function insert($table, $data)
    {
        $this->startConnection();
        $columns = "";
        $valuesColumns ="";

        foreach($data as $column => $value){
            $columns .= $column.", ";
            $valuesColumns .= '"'.str_replace('"',"'",$value).'", ';
        }

        $columns = trim($columns, ", ");
        $valuesColumns = trim($valuesColumns, ", ");


        return $this->connection->query
        ("
            INSERT INTO {$table} ({$columns})
            VALUES ({$valuesColumns});
        ");
    }

    protected function update($table, $data, $where)
    {
        $this->startConnection();
        $set="";

        foreach($data as $column => $value){
            $set .= $column.'="'.str_replace('"',"'",$value).'", ';
        }

        $set = trim($set, ", ");

        return $this->connection->query
        ("
            UPDATE {$table}
            SET    {$set}
            WHERE  {$where};
        ");
    }

    protected function delete($table, $where)
    {
        $this->startConnection();

        return $this->connection->query
        ("
            DELETE FROM {$table}
            WHERE  {$where};
        ");
    }

    protected function close(){
        if($this->connection != NULL) {
            $this->connection->close();
        }
    }
}