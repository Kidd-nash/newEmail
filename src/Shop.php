<?php

namespace Root\NewEmail;

use \PDO;

class Shop
{

    protected $connection;

    public const UPLOADS_DIR = 'uploads/';

    public function __construct()
    {

        $hostname = 'db_postgres_lab';

        $dbname = 'first';

        $username = 'pguser';

        $password = 'pgpwd';

        $dsn = "pgsql:host=$hostname;dbname=$dbname";

        $this->connection = new PDO($dsn, $username, $password);
    }

    public function merchantProducts() {

        $productId = $_GET['productId'];

        $shopQuery = $this->connection->prepare(
            'SELECT * FROM merchant_shop_products WHERE id = :id'
        );

        $shopQuery->execute([
            'id' => $productId
        ]);

        $shopProduct = $shopQuery->fetch(PDO::FETCH_ASSOC);

        $productName = $shopProduct['product_name'];
        $productPrice = $shopProduct['price_usd'];
        $productQuantity = $shopProduct['quantity'];
        $productDesc = $shopProduct['product_desc'];
        $productImg = $shopProduct['img_path'];

        // echo '<pre>' . print_r($shopProduct) . '</pre>';
        // die();

        ob_start();

        include_once("./src/shop/products-tpl.php");

        return ob_get_clean();

    }

    public function purchaseProducts() {

        //create a new user table for shop

        session_start();
        
        $userId = 23; 
        $productId = $_POST['id'];
        $productPrice = $_POST['product-price'];
        $date = date('Y-m-d');

        //TODO: add status to order table

        $this->handlePayment([
            'user_id' => $userId,
            'product_id' => $productId,
            'price' => $productPrice,
        ]);

        echo "you've purhcased the product: " . $productId . " and price of: " . $productPrice;

        $orderQuery = $this->connection->prepare(
            'INSERT INTO user_order (user_id, product_id, price, date) VALUES (:user_id, :product_id, :price, :date)' 
        );

        $orderQuery->execute([
            'user_id' => $userId,
            'product_id' => $productId,
            'price' => $productPrice,
            'date' => $date
        ]);

    }

    private function handlePayment($details) : void
    {
        //query for user,
        //query product

        //create stripe customer
        //create stripe product
        //create stripe payment
    }
};