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

        $productQuery = $this->connection->prepare(
            'SELECT * FROM merchant_shop_products WHERE id = :id' 
        );

        $productQuery->execute([
            'id' => $productId
        ]);

        $product = $productQuery->fetch(PDO::FETCH_ASSOC);

        $orderQuery = $this->connection->prepare(
            'INSERT INTO user_order (user_id, product_id, price, date) VALUES (:user_id, :product_id, :price, :date)' 
        );



        $order = $orderQuery->execute([
            'user_id' => $userId,
            'product_id' => $productId,
            'price' => $productPrice,
            'date' => $date
        ]);


        $orderId = $this->connection->lastInsertId();

        

        $customerStripeKey = $this->handleCustomerStripe($userId); 
        
        $productStripeKey = $this->handleProductStripe($product, $userId);

        $priceStripeKey = $this->handleProudctPrice($productStripeKey, $product['price_usd']);


        // SKIP INVOICE AND RESPONSE PAYMENT, PYAMENT
        $invoiceStripeKey = $this->handleInvoiceStripe($customerStripeKey, $priceStripeKey);

        $stripe = new \Stripe\StripeClient($this->secretKey);
        $responsePayment = $stripe->invoices->pay($invoiceStripeKey, []); 

        echo '<pre>';
        var_dump($responsePayment);
        echo '</pre>';

        $paymentStripeKey = $responsePayment->id;

        $orderUpdateQuery = $this->connection->prepare(
            'UPDATE
                user_order 
            SET 
                invoice_stripe_key = :invoice_key , 
                status = :status, 
                payment_stripe_key = :payment_key
            WHERE
                id = :order_id'
        );

        $orderUpdateQuery->execute([
            'invoice_key' => $customerStripeKey,
            'payment_key' => $paymentStripeKey,
            'status' => $responsePayment->status,
            'order_id' => $orderId
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

    public function handleCustomerStripe($userId): string
    {

        $userQuery = $this->connection->prepare(
            'SELECT * FROM email_users WHERE id = :id'
        );

        $userQuery->execute([
            'id' => $userId
        ]);

        $user = $userQuery->fetch(PDO::FETCH_ASSOC);


        $customerStripeKey = $user['stripe_key'];
        $customerName = $user['username'];
        $customerEmail = $user['email'];
        
        $paymentType = 'pm_card_visa';
        // $paymentType = 'us_bank_account';

        if (empty($customerStripeKey)) {
            // echo 'no customer key';

            $stripe = new \Stripe\StripeClient($this->secretKey); 
            $customer = $stripe->customers->create([
                'description' => $customerName,
                'email' => $customerEmail, 
                'payment_method' => $paymentType,
            ]);
            // var_dump($customer);

            $customerStripeKey = $customer->id;

            // echo 'your stipe key is: ' . $customerStripeKey;

            $updateUser = $this->connection->prepare(
                'UPDATE email_users SET stripe_key = :stripe_key WHERE id = :id'
            );

            $updateUser->execute([
                'stripe_key' => $customerStripeKey,
                'id' => $userId
            ]);
        } else {
            // echo 'your stripe key is: ' . $customerStripeKey;
        }

        // todo return customer tripe key


        $stripe = new \Stripe\StripeClient($this->secretKey); 
        
        // $paymentType = 'us_bank_account';
        // $paymentMethodKey = $this->handlePaymentMethod($paymentType, $customerName, $customerStripeKey);

        // echo 'payment key:' . $paymentMethodKey . '<br/><br/>' ;



        $customerPayments = $stripe->customers->allPaymentMethods($customerStripeKey, ['limit' => 3]);

        $customerPaymentKey = $customerPayments['data'][0]->id;

        echo $customerPaymentKey;

        echo '<pre>';
        var_dump($customerPayments);
        echo '</pre>';

        $stripe = new \Stripe\StripeClient($this->secretKey); 

        // $stripe->paymentMethods->attach(
        //     $paymentMethodKey,
        //     ['customer' => $customerStripeKey]
        //   );

        //   die('id: ' . $paymentMethodKey);


          $stripe = new \Stripe\StripeClient($this->secretKey); 
        $customerUpdate = $stripe->customers->update(
            $customerStripeKey,
            ['invoice_settings' => ['default_payment_method' => $customerPaymentKey]]
        );

        var_dump($customerUpdate);



        return $customerStripeKey;

    }

    public function handlePaymentMethod($paymentType, $userName, $customerStripeKey): string
    {

        $stripe = new \Stripe\StripeClient($this->secretKey);
        $customerPaymentMethod = $stripe->paymentMethods->create([
            'type' => $paymentType,
            $paymentType => [
              'account_holder_type' => 'individual',
              'account_number' => '000123456789',
              'routing_number' => '110000000',
            ],
            'billing_details' => ['name' => $userName],
            // 'customer' => $customerStripeKey
          ]);



          return $customerPaymentMethod->id;
    }

    public function handleProductStripe($product, $userId): string
    {

        //TODO: add status to order table

        // echo "you've added stripe key to the product: " . $product['id'];



        $productStripe = $product['stripe_key'];
        $productName = $product['product_name'];
        $productDesc = $product['product_desc'];
        $selectedProductPrice = $product['price_usd'];

        if (empty($productStripe)) {
            // echo 'no key';
            $stripe = new \Stripe\StripeClient($this->secretKey);
            $product = $stripe->products->create([
                'name' => $productName,  
                'description' => $productDesc
            ]);
            var_dump($product);

            $productStripe = $product->id;

            // echo 'you\'ve made a stripe for the product' . $productStripe;

            $updateQuery = $this->connection->prepare(
                'UPDATE merchant_shop_products SET stripe_key = :stripe_key WHERE id = :id'
            );

            $updateQuery->execute([
                'stripe_key' => $productStripe,
                'id' => $product['id']
            ]);
        } else {
            // echo 'has key value: ' . $productStripe;
        }
        
        // echo '<pre>' . print_r($product) . '</pre>';
        return $productStripe;

    }

    public function handleProudctPrice($productStripe, $price): string
    {

        $stripe = new \Stripe\StripeClient($this->secretKey);
        $productPrice = $stripe->prices->create([
            'currency' => 'usd',
            'unit_amount' => $price * 100, 
            'product' => $productStripe, 
        ]);
        // var_dump($productPrice);

        $productPriceStripe = $productPrice->id;

        // echo 'product price key: ' . $productPriceStripe;

        return $productPriceStripe;

        
    }

    public function handleInvoiceStripe($customerStripeKey, $productPriceStripe): string
    {
        $stripe = new \Stripe\StripeClient($this->secretKey);
        $invoiceCustomer = $stripe->invoices->create([
            'customer' => $customerStripeKey 
        ]);
        // var_dump($invoiceCustomer);

        $invoiceStripe = $invoiceCustomer->id;

        $invoiceItemsCustomer = $stripe->invoiceItems->create([
            'customer' => $customerStripeKey,
            'price' => $productPriceStripe, 
            'invoice' => $invoiceStripe
        ]);

        // var_dump($invoiceItemsCustomer);

        

        return $invoiceStripe;
    }

    public function checkOut()
    {
        $stripe = new \Stripe\StripeClient($this->secretKey);

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount' => 2000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://email.api:8080/checkout-success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://email.api:8080/checkout-cancel',
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
    }

};

