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

        // echo '<pre>' . print_r($shopProduct) . '</pre>';
        // die();

        ob_start();

        include_once("./src/shop/products-tpl.php");

        return ob_get_clean();

    }
};