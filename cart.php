<?php

session_start();

if (isset($_POST['add to cart'])) {


    //if user has already added a product to cart
    if(isset($_SESSION['cart'])) {

      $products_array_ids = array_column($_SESSION['cart'],"product_id");

      //if product has already been added to cart or not 
      if(!in_array($_POST['product_id'],$products_array_ids) ){

                
            $product_id = $_POST['product_id'];
                $product_array = array(
                                  'product_id'=> $_POST['product_id'],
                                  'product_name'=> $_POST['product_name'],
                                  'product_price'=> $_POST['product_price'],
                                  'product_image'=> $_POST['product_image'],
                                  'product_quantity'=> $_POST['product_quantity']

                );

                $_SESSION['cart'][$product_id] = $product_array;




       //product has already been added
      }else{
          
          echo'<script>alert("Product was already added to cart");</script>';
          
      }

    //if this is the first product  
    }else{

       $product_id=$_POST['product_id'];
       $product_name=$_POST['product_name'];
       $product_price=$_POST['product_price'];
       $product_image=$_POST['product_image'];
       $product_quantity=$_POST['product_quantity'];

       $product_array = array(
                        'product_id'=> $product_id,
                        'product_name'=> $product_name,
                        'product_price'=> $product_price,
                        'product_image'=> $product_image,
                        'product_quantity'=> $product_quantity,

       );

       $_SESSION['cart'][$product_id] = $product_array;

 

    }

    //calculate total
    calculateTotalCart();

//remove product from cart
}else if(isset($_POST['remove_product'])){

  $product_id=$_POST['product_id'];  
  unset($_SESSION['cart'][product_id]); 

  //calculate total
  calculateTotalCart();



}else if(isset($_POST['edit_quantity'])){

  //we get id and quantity from the form 
  $product_id=$_POST['product_id']; 
  $product_quantity=$_POST['product_quantity']; 
  
  //get the product array from the session 
  $product_array  = $_SESSION['cart'][$product_id];

  //update product quantity
  $product_array['product_quantity'] = $product_quantity;

  //return array back to its place
  $_SESSION['cart'][$product_id] = $product_array;

  //calculate total
  calculateTotalCart();

}else{
    header('location: index.php');

}


function calculateTotalCart(){

    $total = 0;

    foreach($_SESSION['cart'] as $key => $value){
        
       $product = $_SESSION['cart'][$key];

       $price = $product['product_price'];
       $quantity = $product['product_quantity'];

       $total = $total + ($price * $quantity);


    }

    $_SESSION['total'] = $total; 

}




?>

 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel='stylesheet' id='fontawesome-css'  href='https://use.fontawesome.com/releases/v5.0.1/css/all.css?ver=4.9.1' type='text/css' media='all' /> 

    <link rel="stylesheet" href="assets/css/style.css"/>
</head>


    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-white bg-body-tertiary py-3 fixed-top">
      <div class="container">
              <img class="Logo" src="assets/imgs/logo1.png"/>
          <h2 class="brand">BuyBay</h2>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            
            <li class="nav-item">
              <a class="nav-link" href="index.html">Home</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="shop.html">Shop</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#">Blog</a>
            </li>

            

            <li class="nav-item">
              <a class="nav-link" href="contact.html">Contact</a>
            </li>

            <li class="nav-item">
              <a href="cart.html"><i class="fas fa-shopping-bag"></i></a>
              <a href="account.html" ><i class="fas fa-user"></i></a>
            </li>
            
           
          </ul>
         
        </div>
      </div>
    </nav>

       <!-- Cart -->

       <section class="cart container my-5 py-5">
        <div class="container mt-5">
          <h2 class="font-weight-bold">Your Cart</h2>
        </div>
        <table class="mt-5 pt-5">
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Subtotal</th>
          </tr>


          <?php foreach($_SESSION['cart'] as $key =>$value) { ?>



          <tr>
            <td>
              <div class="product-info">
                <img src="assets/imgs/<?php echo $value['product_image'] ;?>" />
                <div>
                  <p> <?php echo $value['product_name']; ?> </p>
                  <small>
                    <span>$</span><?php echo $value['product_price'] ; ?>
                  </small>
                  <br>
                  <form method="POST" action="cart.php"> 
                      <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                      <input type="submit" name="remove_product" class="remove-btn" value="remove" />

                  </form>
                 
                </div>
              </div>
            </td>

            <td>
                
                <form method="POST" action="cart.php">
                      <input type="hidden" name="product_id" value=<?php echo $value['product_id'] ;?> />
                      <input type="number" name="product_quantity" value="<?php echo $value['product_quantity']; ?>" />
                      <input type="submit" class="edit-btn" value="edit" name="edit_quantity"/>
                
                </form>
                
              </td>
              <td>
                <span>$</span>
                <span class="product-price"><?php echo $value['product_quantity'] * $value['product_price'] ?></span>
              </td>
              
          </tr>

          <?php } ?>

          
        </table>

        <div class="cart-total">
            <table>
              
              <tr>
                <td>Total</td>
                <td><?php echo $_SESSION['total']; ?> </td>
              </tr>
            </table>
          </div>
          
          <div class="checkout-container">
            
            <form method="POST" action="checkout.php">  

                 <input type="submit" class="btn checkout-btn" value="Checkout" name = "checkout">

            </form>
            
          </div>


      </section>
      
      





       <!-- Footer -->
<footer class="mt-5 py-5">
    <div class="row container mx-auto pt-5">
      <!-- First Column -->
      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <img class="Logo" src="assets/imgs/logo1.png" />
       
        <p class="pt-2">We provide the best products for the most affordable prices</p>
      </div>
  
      <!-- Second Column -->
      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <h5 class="pb-2">Featured</h5>
        <ul class="text-uppercase">
          <li><a href="#">Men</a></li>
          <li><a href="#">Women</a></li>
          <li><a href="#">Boys</a></li>
          <li><a href="#">Girls</a></li>
          <li><a href="#">New Arrivals</a></li>
          <li><a href="#">Clothes</a></li>
        </ul>
      </div>
  
      <!-- Third Column -->
      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <h5 class="pb-2">Contact Us</h5>
        <div>
          <h6 class="text-uppercase">Address</h6>
          <p>1234 Street Name, City</p>
        </div>
        <div>
          <h6 class="text-uppercase">Phone</h6>
          <p>123-456-7890</p>
        </div>
        <div>
          <h6 class="text-uppercase">Email</h6>
          <p>Sanji@email.com</p>
        </div>
      </div>
  
      <!-- Fourth Column -->
      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <h5 class="pb-2">Instagram</h5>
        <div class="row">
          <img src="assets/imgs/footer1.jpeg" class="img-fluid w-25 h-100 m-2" />
          <img src="assets/imgs/footer1.jpeg" class="img-fluid w-25 h-100 m-2" />
          <img src="assets/imgs/footer1.jpeg" class="img-fluid w-25 h-100 m-2" />
          <img src="assets/imgs/footer1.jpeg" class="img-fluid w-25 h-100 m-2" />
          <img src="assets/imgs/footer1.jpeg" class="img-fluid w-25 h-100 m-2" />
          <img src="assets/imgs/footer1.jpeg" class="img-fluid w-25 h-100 m-2" />
        </div>
      </div>
    </div>

        <!-- Copyright -->
<div class="copyright mt-5">
    <div class="row container mx-auto">
      <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
        <img src="assets/imgs/payment.jpg"/>
      </div>
      <div class="col-lg-3 col-md-5 col-sm-12 mb-4 text-nowrap mb-2">
        <p>BuyBay @ 2024 All Rights Reserved</p>
      </div>
      <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
      </div>
    </div>
  </div>
  

  </footer>
  
  
  
  






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>