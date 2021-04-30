<?php


class Database
{
    // DB Params
    private $host = '';
    private $db_name = '';
    private $username = '';
    private $password = '';
    private $conn;
    public $config;

    public function __construct($config)
    {
        $this->config=$config;
        $this->host=$config['server_name'];
        $this->db_name=$config['database']['db_name'];
        $this->username=$config['database']['username'];
        $this->password=$config['database']['password'];
    }


    /**
     * Will make database connection
     * @return PDO|null
     */
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}