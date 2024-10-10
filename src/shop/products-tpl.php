<!DOCTYPE html>
<html>
    <head>
        <title>
            Product <?php echo $productId ?>
        </title>
        <link rel="stylesheet" href="/src/shop/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    </head>
    <body class="products_page">
        <div class="header_div">
            <div class="header_div_container">
                <div class="header_shop_buttons header_ui">
                    <ul class="nav_list">
                        <li>
                            <a class="nav_links">
                                <span>Home</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav_links">
                                <span>Products</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav_links">
                                <span>Contact</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav_links">
                                <span>About us</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="header_shop_logo header_ui">
                    <div class="logo_wrapper">
                        <a class="logo_link">
                            <img class="header_logo" src="/uploads/icon_sss.jpg" />
                        </a>
                    </div>
                </div>
                <div class="header_user_buttons header_ui">
                    <ul class="user_list">
                        <li>
                            <a class="user_links">
                                <span>Search</span>
                            </a>
                        </li>
                        <li>
                            <a class="user_links">
                                <span>Account</span>
                            </a>
                        </li>
                        <li>
                            <a class="user_links">
                                <span>Cart</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product_div div_positioning">
            <div class="product_image product_div_parts">
                <!-- <p><?php //echo $productName ?></p> -->
                <img src="/<?php echo $productImg ?>" />
            </div>
            <div class="product_info product_div_parts">
                <div class="each_product_info">
                    <p><?php echo $productName ?></p>
                </div>
                <div class="each_product_info">
                    <p>$<?php echo $productPrice ?></p>
                </div>
                <div class="each_product_info">
                    <p><?php echo $productQuantity ?></p>
                </div>
                <div>
                    <form method="POST" action="/merchant/checkout">
                        <input type="hidden" name="product-price" value="<?php echo $productPrice ?>">
                        <input type="hidden" name="id" value="<?php echo $productId ?>"> 
                        <input type="submit" value="Checkout 2">
                    </form>
                    <form method="POST" action="/merchant-shop/product/purchase">
                        <input type="hidden" name="product-price" value="<?php echo $productPrice ?>">
                        <input type="hidden" name="id" value="<?php echo $productId ?>"> 
                        <input type="submit" value="Stipe">
                    </form>
                </div>
            </div>
        </div>
        <div class="product_desc div_positioning">
            <div class="product_description product_desc_div">
                <?php echo $productDesc?>
            </div>
            <div class="product_information product_desc_div"></div>
            <div class="product_policies product_desc_div"></div>
        </div>
        <div class="product_footer div_positioning"></div>
    </body>
</html>