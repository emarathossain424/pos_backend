<?php

function config(){
    return [
        'server_name' => "localhost",//set your host name

        //set your database credentials
        'database' => [
            'db_name' => "product_order_system",
            'username' => "root",
            'password' => ""
        ],

        //jwt token information
        'jwt' => [
            'secret_key' => "badhon424",
            'token_activate_after' => 0,
            'token_duration_in_seconds' => 30000,
        ],
        'order_status' => [
            'processing' => 1,
            'shipped' => 2,
            'delivered' => 3,
        ],
        'user_type' => [
            'customer' => 1,
            'admin' => 2
        ],
    ];
}
