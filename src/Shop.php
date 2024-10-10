<?php

namespace Root\NewEmail;

use \PDO;

class Shop
{

    protected $connection;

    public const UPLOADS_DIR = 'uploads/';

    private $secretKey = 'sk_test_51Q7uZNA0QEiadHV66BfQeb38KQuoobRgWvnG1hxyhVTo8u5A96iG4bwus6vdDNnyKgp4OdRmyVIjJ9ZbbvfrBH5z00o0POGVEc';

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
        
        $userId = $_SESSION['userId']; 
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
        // session_start();
        //query for user,
        $id = $details['user_id'];

        $userQuery = $this->connection->prepare(
            'SELECT * FROM email_users WHERE id = :id'
        );

        $userQuery->execute([
            'id' => $id
        ]);

        $user = $userQuery->fetch(PDO::FETCH_ASSOC);

        echo '<pre>' . print_r($user) . '</pre>';
        //query product
        $productId = $details['product_id'];

        $productQuery = $this->connection->prepare(
            'SELECT * FROM merchant_shop_products WHERE id = :id'
        );

        $productQuery->execute([
            'id' => $productId
        ]);

        $product = $productQuery->fetch(PDO::FETCH_ASSOC);

        echo '<pre>' . print_r($product) . '</pre>';

        // setp 1:
        // add stripe_key to tables user from customer, product stipe key , order 2 keys: invoice key, payment key


        //create stripe customer

        //if ( no stripe key for customer)
        // $stripe = new \Stripe\StripeClient($this->secretKey); 
        // $customer = $stripe->customers->create([
        //     'description' => 'example customer',
        //     'email' => 'email@example.com', // cus_QzuqtrX8JG6cSJ
        //     'payment_method' => 'pm_card_visa',
        // ]);

        // TODO: SAVE KEY TO CUSTOMER
        // var_dump($customer);


        //create stripe product

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $product = $stripe->products->create([
        //     'name' => 'test product',  //prod_QzvCqkW58uIrED
        //     'description' => 'this is a trial product, for testing...'
        // ]);

        // SAVE KEY
        // var_dump($product);


        //fix product price

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $productPrice = $stripe->prices->create([
        //     'currency' => 'usd',
        //     'unit_amount' => 50, //in cents!!!  //price_1Q8BpGA0QEiadHV6CuypomYM
        //     'product_data' => ['name' => 'test product'],
        // ]);
        // var_dump($productPrice);

        // $response->id
        // $response->getId()

        //product price using prod id

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $productPrice = $stripe->prices->create([
        //     'currency' => 'usd',
        //     'unit_amount' => 500, //in cents!!!  //price_1Q8CwSA0QEiadHV6zgERchRt
        //     'product' => 'prod_QzvCqkW58uIrED',
        // ]);
        // var_dump($productPrice);


        //invoice

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $invoiceCustomer = $stripe->invoices->create([
        //     'customer' => 'cus_QzuqtrX8JG6cSJ' //in_1Q8CcTA0QEiadHV6ibzgfTPu
        // ]);
        // var_dump($invoiceCustomer);
        
        //invoice items

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $invoiceCustomer = $stripe->invoiceItems->create([
        //     'customer' => 'cus_QzuqtrX8JG6cSJ',
        //     'price' => 'price_1Q8CwSA0QEiadHV6zgERchRt', //in_1Q8CcTA0QEiadHV6ibzgfTPu
        // ]);

        // var_dump($invoiceCustomer);


        //another product trial

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $product = $stripe->products->create([
        //     'name' => 'test 2 product',  //prod_R0DUxD1mLcnh7r
        //     'description' => 'this is a second trial product, for testing...'
        // ]);
        // var_dump($product);

        //price for second product

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $productPrice = $stripe->prices->create([
        //     'currency' => 'usd',
        //     'unit_amount' => 800, //in cents!!!   !!! first two digits are for cents .00
        //     'product' => 'prod_R0DUxD1mLcnh7r', //price_1Q8D6OA0QEiadHV6ouQEtqqn
        // ]);
        // var_dump($productPrice);

        //2nd invoice item for 2nd product with new price

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $invoiceCustomer = $stripe->invoiceItems->create([
        //     'customer' => 'cus_QzuqtrX8JG6cSJ',
        //     'price' => 'price_1Q8D6OA0QEiadHV6ouQEtqqn', //ii_1Q8DAUA0QEiadHV6m0fItklp
        // ]);

        // var_dump($invoiceCustomer);
        
        //update customer payment method
        //pm_1Q7v0gA0QEiadHV68SMgnRql
        
        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $customerUpdate = $stripe->customers->update(
        //     'cus_QzuqtrX8JG6cSJ',
        //     ['invoice_settings' => ['default_payment_method' => 'pm_1Q7v0gA0QEiadHV68SMgnRql']]
        // );

        // var_dump($customerUpdate);


        //updating invoice item to link in invoice

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $updateInvoiceItem = $stripe->invoiceItems->update(
        //     'ii_1Q8DAUA0QEiadHV6m0fItklp',
        //     'invoice' => 'in_1Q8CcTA0QEiadHV6ibzgfTPu'
        // );

        // var_dump($updateInvoiceItem);

        //new invoice item

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $newInvoiceItem = $stripe->invoiceItems->create([
        //     'customer' => 'cus_QzuqtrX8JG6cSJ',
        //     'price' => 'price_1Q8D6OA0QEiadHV6ouQEtqqn',
        //     'invoice' => 'in_1Q8CcTA0QEiadHV6ibzgfTPu'
        // ]);

        // var_dump($newInvoiceItem);


        //payment 

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $responsePayment = $stripe->invoices->pay('in_1Q8CcTA0QEiadHV6ibzgfTPu', []); //in_1Q8CcTA0QEiadHV6ibzgfTPu

        // echo '<pre>';
        // var_dump($responsePayment);
        // echo '</pre>';


        // add status column to order


        //setting payment method

        // $stripe = new \Stripe\StripeClient($this->secretKey);
        // $customerPaymentMethod = $stripe->paymentMethods->create([
        //     'type' => 'us_bank_account',
        //     'us_bank_account' => [
        //       'account_holder_type' => 'individual',
        //       'account_number' => '000123456789',
        //       'routing_number' => '110000000',
        //     ],
        //     'billing_details' => ['name' => 'John Doe'],
        //   ]);

        // $customerKey = $this->handleStripeCustomer();
        // $productKey = $this
        // $this->handleInvoice($customerKey, $productKey);


        //create stripe payment
    }

    // handleStripeCustomer
      // query user table
      // check if stripe key exists
      // if not exist, submit to stripe
      // update user table with stripe key of customer
      // return stripe

    public function handleProductStripe()
    {
        session_start();
        
        $userId = $_SESSION['userId']; 
        $productId = $_POST['id'];
        $productPrice = $_POST['product-price'];

        //TODO: add status to order table

        $this->handlePayment([
            'user_id' => $userId,
            'product_id' => $productId,
            'price' => $productPrice,
        ]);

        echo "you've added stripe key to the product: " . $productId;

        $productQuery = $this->connection->prepare(
            'SELECT * FROM merchant_shop_products WHERE id = :id' 
        );

        $productQuery->execute([
            'id' => $productId
        ]);

        


    }
};

