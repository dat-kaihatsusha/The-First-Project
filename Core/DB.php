<?php

class DB
{
    private $host = 'localhost:3306';
    private $user = 'root';
    private $pass = '';
    private $db = 'do_an';

    protected $connection = NULL;

    public function __construct()
    {
        $objConn = new mysqli($this->host, $this->user, $this->pass);

        if ($objConn->connect_errno)
        {
            die(' Connect Error (' . $objConn->connect_errno . ')' .$objConn->connect_error);
        }

        $objConn->select_db($this->db);

        if ($objConn->errno)
        {
            die(' Not Found DB (' . $objConn->errno . ')' .$objConn->error);
        }

        $objConn->set_charset("utf8");

        $this->connection = $objConn;

        return $this->connection;
    }

    public function Query($sql)
    {
        $res = $this->connection->query($sql);

        if ($this->connection->errno)
        {
            die("Loi thuc thi truy van: " .$this->connection->error);
        }

        return $res;
    }

    public function __destruct()
    {
        if (!empty($this->connection))
        {
            $this->connection->close();
        }
    }
}

?>

