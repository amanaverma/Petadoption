<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_pet'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $age = mysqli_real_escape_string($conn, $_POST['age']);
   $breed = mysqli_real_escape_string($conn, $_POST['breed']);
   $trained = mysqli_real_escape_string($conn, $_POST['trained']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/'.$image;

   $select_pet_name = mysqli_query($conn, "SELECT name FROM `pets` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_pet_name) > 0){
      $message[] = 'pet name already exist!';
   }else{
      $insert_pet = mysqli_query($conn, "INSERT INTO `pets`(name, age, breed, trained, details, image) VALUES('$name', '$age', '$breed', '$trained', '$details', '$image')") or die('query failed');

      if($insert_pet){
         if($image_size > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folter);
            $message[] = 'pet added successfully!';
         }
      }
   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = mysqli_query($conn, "SELECT image FROM `pets` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `pets` WHERE id = '$delete_id'") or die('query failed');
   mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');
   mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
   header('location:addpet.php');

}

?><!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pets</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="add-pets">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>add new pet</h3>
      <input type="text" class="box" required placeholder="enter pet name" name="name">
      <input type="text" class="box" required placeholder="enter pet age" name="age">
      <input type="text" class="box" required placeholder="enter pet breed" name="breed">
      <input type="text" class="box" required placeholder="Is your pet trained?" name="trained">
      <textarea name="details" class="box" required placeholder="enter pet details" cols="30" rows="10"></textarea>
      <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
      <input type="submit" value="add pet" name="add_product" class="btn">
   </form>

</section>
<script src="js/admin_script.js"></script>

</body>
</html>