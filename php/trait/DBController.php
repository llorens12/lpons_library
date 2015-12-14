<?php

/**
 * Class DBController, this trait is responsible to the all connections of Database.
 */
trait DBController
{
    /**
     * @var mysqli $connection *Description* his var contains the mysqli connect.
     */
    private $connection;


    /**
     * This method starts the connection.
     * @void method.
     */
    protected  function startConnection()
    {
        if($this->connection == NULL)
            $this->connection = new mysqli("localhost", "root", "", "lpons_library");

    }

    /**
     * This method is the responsible of the all selects sql sentences.
     * @param string $sentence *Description* contains the SQL sentence.
     * @return bool|mysqli_result *Description* return the result of the sql sentence.
     */
    protected function select($sentence)
    {
        $this->startConnection();
        return $this->connection->query($sentence);
    }

    /**
     * This method is the responsible of the all inserts sql sentences.
     * @param string $table *Description* contains the name of the database table to insert.
     * @param array $data *Description* contains the all data to insert. The format is: column => data.
     * @return bool|mysqli_result *Description* returns the result of the sql insert.
     */
    protected function insert($table, $data)
    {
        $this->startConnection();
        $columns = "";
        $valuesColumns ="";

        foreach($data as $column => $value)
        {
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

    /**
     * This method is the responsible of the all updates sql sentences.
     * @param string $table *Description* contains the name of the database table to update.
     * @param array $data *Description* contains the all data to update. The format is: column => data.
     * @param string $where *Description* contains the where sentence.
     * @return bool|mysqli_result *Description* return the result of the sql update.
     */
    protected function update($table, $data, $where)
    {
        $this->startConnection();
        $set="";

        foreach($data as $column => $value)
            $set .= $column.'="'.str_replace('"',"'",$value).'", ';


        $set = trim($set, ", ");

        return $this->connection->query
        ("
            UPDATE {$table}
            SET    {$set}
            WHERE  {$where};
        ");
    }


    /**
     * This method is the responsible of the all deletes sql sentences.
     * @param string $table *Description* contains the name of the database table to delete.
     * @param string $where *Description* contains the where sentence.
     * @return bool|mysqli_result *Description* return the result of the sql delete.
     */
    protected function delete($table, $where)
    {
        $this->startConnection();

        return $this->connection->query
        ("
            DELETE FROM {$table}
            WHERE  {$where};
        ");
    }

    /**
     * This method close the SQL connection.
     * @void method.
     */
    protected function close()
    {
        if($this->connection != NULL)
            $this->connection->close();

    }
}