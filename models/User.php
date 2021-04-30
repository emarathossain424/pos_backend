<?php


class user
{
    private $conn;
    private $table = 'users';

    public $id;
    public $name;
    public $email;
    public $password;
    public $created_at;

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
     * Will search user details by user email
     * @return mixed
     */
    public function getUserDetailsByEmailAddress()
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE email = :email';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Will check the validity of a user
     * @param $user_id
     * @return mixed
     */
    public function isValidUser($user_id){
        $query = 'SELECT * FROM '.$this->table.' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}