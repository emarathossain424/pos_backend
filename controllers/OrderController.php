<?php


class OrderController
{
    public $data;
    public $config;
    public $product;
    public $order;
    public $order_has_products;
    public $user;

    public function __construct($data, $config, $file = null)
    {
        $database = new database($config);
        $db = $database->connect();

        $this->product = new Product($db);
        $this->order = new Order($db);
        $this->order_has_products = new OrderHasProducts($db);
        $this->user = new User($db);

        $this->data = $data;
        $this->config = $config;
    }

    /**
     * Will store new order
     * @return false|string
     */
    public function storeOrder()
    {
        $validation = $this->validateOrder();

        try {
            if (!$validation['is_validated']) {
                return validationErrorMessages(500, $validation['errors']);
            } else {
                $this->order->ordered_products=$this->data->orders;
                $this->order->status = $this->config['order_status']['processing'];
                $this->order->customer_id = $this->data->customer_id;
                $this->order->total_price = $this->getTotalPrice();

                $response = $this->order->create();

                if ($response) {
                    return successMessagesWithoutData("Order placed successfully");
                } else {
                    return errorMessages(500, ['error' => "Order place unsuccessful"]);
                }
            }
        } catch (Exception $e) {
            $error = ['error'=>$e->getMessage()];
            return errorMessagesForExceptions(500, $error, 'Order place unsuccessful');
        }
    }

    /**
     * Will validate order request
     * @return array
     */
    public function validateOrder()
    {
        $orders = $this->data->orders;
        if (sizeof($orders)<=0) {
            return [
                'is_validated' => false,
                'errors' => ['error' => 'You did not order any product']
            ];
        }

        if (empty($this->data->customer_id)) {
            return [
                'is_validated' => false,
                'errors' => ['error' => 'Invalid customer id']
            ];
        } else {
            if (!$this->user->isValidUser($this->data->customer_id)) {
                return [
                    'is_validated' => false,
                    'errors' => ['error' => 'Invalid customer id ']
                ];
            }
        }

        for ($i = 0; $i < sizeof($orders); $i++) {
            if (empty($orders[$i]->product_id)) {
                return [
                    'is_validated' => false,
                    'errors' => ['error' => 'You have selected invalid product for order in index number '.($i + 1)]
                ];
            } else {
                if (!$this->product->isValidProduct($orders[$i]->product_id)) {
                    return [
                        'is_validated' => false,
                        'errors' => ['error' => 'You have selected invalid product for order in index number '.($i + 1)]
                    ];
                }
            }

            if (empty($orders[$i]->price)) {
                return [
                    'is_validated' => false,
                    'errors' => ['error' => 'invalid product price in index number '.($i + 1)]
                ];
            } else {
                if (!$this->product->isValidProductPrice($orders[$i]->product_id, $orders[$i]->price)) {
                    return [
                        'is_validated' => false,
                        'errors' => ['error' => 'invalid product price in index number '.($i + 1)]
                    ];
                }
            }

            if (empty($orders[$i]->quantity) || $orders[$i]->quantity <= 0) {
                return [
                    'is_validated' => false,
                    'errors' => ['error' => 'invalid quantity in index number '.($i + 1)]
                ];
            }

            $final_price = $orders[$i]->price * $orders[$i]->quantity;
            $final_price=round($final_price,2);
            if (empty($orders[$i]->final_price)) {
                return [
                    'is_validated' => false,
                    'errors' => ['error' => 'invalid final price in index number '.($i + 1)]
                ];
            } elseif ($final_price != $orders[$i]->final_price) {
                return [
                    'is_validated' => false,
                    'errors' => ['error' => 'invalid final price in index number '.($i + 1)]
                ];
            }
        }
        return [
            'is_validated' => true
        ];
    }

    /**
     * Will calculate total price
     * @return false|float|int
     */
    public function getTotalPrice(){
        $orders = $this->data->orders;
        $total_price=0;
        for ($i = 0; $i < sizeof($orders); $i++) {
            $total_price = $total_price+$orders[$i]->final_price;
            $total_price = round($total_price,2);
        }

        return $total_price;
    }

    /**
     * Will return all orders
     * @return false|string
     */
    public function getOrders(){
        try {
            if($this->data->user_type == $this->config['user_type']['customer']){
                $this->order->customer_id=$this->data->customer_id;
                return successMessages($this->order->readAll(false));
            }
            return successMessages($this->order->readAll());
        } catch (Exception $e) {
            $error = ['error'=>$e->getMessage()];
            return errorMessagesForExceptions(500, $error, "Unable to extract orders");
        }
    }

    /**
     * Will return single order details
     * @return false|string
     */
    public function getSingleOrder(){
        try {
            $this->order_has_products->order_id=$this->data->order_id;
            return successMessages( $this->order_has_products->readAll());
        } catch (Exception $e) {
            $error = ['error'=>$e->getMessage()];
            return errorMessagesForExceptions(500, $error, "Unable to extract order details");
        }
    }

    /**
     * Will update requested order
     * @return false|string
     */
    public function updateOrder(){

        $validation=$this->validateOrderOnUpdate();

        try {
            if (!$validation['is_validated']) {
                return validationErrorMessages(500, $validation['errors']);
            } else {
                $this->order->id = $this->data->order_id;
                $this->order->customer_id = $this->data->customer_id;
                $this->order->status = $this->data->status;
                $response = $this->order->update();
                if ($response) {
                    return successMessagesWithoutData("Order updated successful");
                } else {
                    return errorMessages(500, ['error' => "Order update unsuccessful"]);
                }
            }
        } catch (Exception $e) {
            $error = ['error'=>$e->getMessage()];
            return errorMessagesForExceptions(500, $error, 'Product creation unsuccessful');
        }
    }

    /**
     * Will validate order on update
     * @return array
     */
    public function validateOrderOnUpdate()
    {
        if ($this->data->user_type != $this->config['user_type']['admin']) {
            return [
                'is_validated' => false,
                'errors' => ['error' => 'Tou do not have permission to update order status']
            ];
        }
        if ($this->data->status != $this->config['order_status']['processing'] &&
            $this->data->status != $this->config['order_status']['shipped'] &&
            $this->data->status != $this->config['order_status']['delivered']
        ) {
            return [
                'is_validated' => false,
                'errors' => ['error' => 'Invalid order status']
            ];
        }
        return [
            'is_validated' => true
        ];
    }
}