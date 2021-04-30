<?php


class Order
{
    private $conn;
    private $table = 'orders';

    public $id;
    public $customer_id;
    public $status;
    public $total_price;
    public $ordered_products;

    public $order_has_products;

    /**
     * Will setup connection
     * user constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->conn = $db;
        $this->order_has_products = new OrderHasProducts($db);
    }

    /**
     * Will execute order creation query
     * @return bool
     */
    public function create()
    {
        $query = 'INSERT INTO '.$this->table.' (status, total_price, customer_id) VALUES (?, ?, ?)';

        $this->conn->beginTransaction();

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->status);
        $stmt->bindParam(2, $this->total_price);
        $stmt->bindParam(3, $this->customer_id);

        if ($stmt->execute()) {
            $order_id = $this->conn->lastInsertId();
            $result = $this->order_has_products->populate($order_id, $this->ordered_products);
            if ($result) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollback();
                return false;
            }
        }
        $this->conn->rollback();
        return false;
    }

    /**
     * Will execute order details fetch query
     * @param  bool  $is_for_admin
     * @return array
     */
    public function readAll($is_for_admin=true)
    {
        $query = 'SELECT orders.*, users.name as customer_name FROM '.$this->table.' as orders JOIN users ON orders.customer_id = users.id';

        if(!$is_for_admin){
            $query=$query.' WHERE orders.customer_id = :customer_id';
        }

        $stmt = $this->conn->prepare($query);

        if(!$is_for_admin){
            $stmt->bindValue(':customer_id', $this->customer_id, PDO::PARAM_INT);
        }

        $stmt->execute();

        $orders = array();
        $num = $stmt->rowCount();
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($orders, $row);
            }
        }
        return $orders;
    }

    /**
     * Will execute order update query
     * @return bool
     */
    public function update(){
        $query = 'UPDATE '.$this->table.
            ' SET status = :status WHERE id = :order_id';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':order_id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}