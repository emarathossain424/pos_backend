<?php


class Category
{
    private $conn;
    private $table = 'categories';

    public $id;
    public $name;

    /**
     * Will setup connection
     * user constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Will check the validity of selected category
     * @param $id
     * @return bool
     */
    public function isValidCategory($id){

        $query = 'SELECT * FROM '.$this->table.' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Will fetch all categories from database
     * @return array
     */
    public function readAll()
    {
        $query = 'SELECT * FROM '.$this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $categories = array();
        $num = $stmt->rowCount();
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($categories, $row);
            }
        }
        return $categories;
    }
}