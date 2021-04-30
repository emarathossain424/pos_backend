<?php


class OrderHasProducts
{
    private $conn;
    private $table = 'ordered_products';

    public $id;
    public $order_id;
    public $product_id;
    public $unit_price;
    public $quantity;
    public $final_price;

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
     * Will store ordered products
     * @param $order_id
     * @param $ordered_products
     * @return bool
     */
    public function populate($order_id,$ordered_products){
        $query_string=$this->prepareQueryStringToPopulate(sizeof($ordered_products));

        $data=$this->prepareDataToPopulate($order_id,$ordered_products);

        $stmt = $this->conn->prepare($query_string);
        $result=$stmt->execute($data);

        if($result){
            return true;
        }
        return false;
    }

    /**
     * Will prepare query string to store ordered products
     * @param $total_rows
     * @return string
     */
    public function prepareQueryStringToPopulate($total_rows){
        $query = 'INSERT INTO '.$this->table.' (product_id, unit_price, quantity, final_price, order_id) VALUES ';

        for($i=0;$i<$total_rows;$i++){
            if($i!=$total_rows-1){
                $query= $query.'(?,?,?,?,?), ';
            }
            else{
                $query= $query.'(?,?,?,?,?) ';
            }
        }
        return $query;
    }

    /**
     * Will prepare data to store ordered products
     * @param $order_id
     * @param $data
     * @return array
     */
    public function prepareDataToPopulate($order_id,$data){
        $insert_values = [];
        foreach($data as $d){
            $data_array=[$d->product_id, $d->price, $d->quantity, $d->final_price, $order_id];
            $insert_values = array_merge($insert_values, array_values($data_array));
        }
        return $insert_values;
    }

    /**
     * Will read all products of order
     * @return array
     */
    public function readAll(){
        $query = 'SELECT products.*, op.* FROM '.$this->table.' as op JOIN products ON op.product_id = products.id  WHERE op.order_id = :order_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':order_id', $this->order_id, PDO::PARAM_INT);
        $stmt->execute();

        $ordered_products = array();
        $num = $stmt->rowCount();
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($ordered_products, $row);
            }
        }
        return $ordered_products;
    }
}