<?php

trait Controller{

    private $connection;


    protected  function startConnection()
    {
        $this->connection = new mysqli("localhost", "root", "", "lpons_library");
    }

    protected function select($sentence)
    {
        $this->startConnection();
        $return = $this->connection->query($sentence);
        $this->connection->close();
        return $return;
    }

    protected function insert($table, $data)
    {
        $this->startConnection();
        $columns = "";
        $valuesColumns ="";

        foreach($data as $column => $value){
            $columns .= $column.", ";
            $valuesColumns .= $value.", ";
        }

        $columns = trim($columns, ", ");
        $valuesColumns = trim($valuesColumns, ", ");

        $return = $this->connection->query
        ("
            INSERT INTO {$table} ({$columns})
            VALUES ({$valuesColumns});
        ");
        $this->connection->close();
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
        $this->connection->close();

        return $return;
    }

    protected function delete($table, $primaryKey, $valuePrimaryKey)
    {
        $this->startConnection();
        $return = $this->connection->query
        ("
            DELETE FROM {$table}
            WHERE  {$primaryKey} = {$valuePrimaryKey};
        ");
        $this->connection->close();

        return $return;
    }


}