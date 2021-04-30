<?php


class Product
{
    private $conn;
    private $table = 'products';

    public $id;
    public $name;
    public $sku;
    public $description;
    public $category;
    public $price;
    public $image;

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
     * Will execute sql query to store new products
     * @return bool
     */
    public function create()
    {
        $query = 'INSERT INTO '.$this->table.' SET name = :name, sku = :sku, price = :price, category = :category, description = :description, image = :image';

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->sku = htmlspecialchars(strip_tags($this->sku));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image = htmlspecialchars(strip_tags($this->image));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':sku', $this->sku);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Will execute product update query
     * @return bool
     */
    public function update(){
        $query = 'UPDATE '.$this->table.
            ' SET name = :name, sku = :sku, price = :price,
            category = :category, description = :description, image = :image WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->sku = htmlspecialchars(strip_tags($this->sku));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image = htmlspecialchars(strip_tags($this->image));

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':sku', $this->sku);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Will check the uniqueness of sku
     * @param $sku
     * @return bool
     */
    public function isUniqueSKU($sku)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE sku = :sku';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':sku', $sku, PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return false;
        }
        return true;
    }

    /**
     * Will return unique sku
     * @return string
     */
    public function createSKU()
    {
        return uniqid();
    }

    /**
     * Will fetch all products from database
     * @return array
     */
    public function readAll(){
        $query = 'SELECT * FROM '.$this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $products = array();
        $num = $stmt->rowCount();
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($products, $row);
            }
        }
        return $products;
    }

    /**
     * Will fetch single product details
     * @return mixed
     */
    public function readSingle(){
        $query = 'SELECT * FROM '.$this->table.' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Will execute delete request
     * @return bool
     */
    public function delete(){
        $query = 'DELETE FROM '.$this->table.' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Will check the validity of a product
     * @param $product_id
     * @return mixed
     */
    public function isValidProduct($product_id)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Will check the validity of product price
     * @param $product_id
     * @param $price
     * @return mixed
     */
    public function isValidProductPrice($product_id,$price)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE id = :id and price = :price';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);
        $stmt->bindValue(':price', $price, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}